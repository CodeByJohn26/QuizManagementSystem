<?php
require '../includes/db_connection.php';
session_start();

// Access control
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit;
}

$teacher_id = $_SESSION['user_id'];

// Fetch teacher's profile info
try {
    $profile_stmt = $conn->prepare("SELECT profile_image, username, email FROM users WHERE id = ? AND role = 'teacher'");
    $profile_stmt->execute([$teacher_id]);
    $profile = $profile_stmt->fetch(PDO::FETCH_ASSOC) ?: [
        'profile_image' => 'default-profile.png',
        'username' => 'Unknown User',
        'email' => 'Not Available'
    ];
} catch (Exception $e) {
    error_log("Error fetching teacher profile: " . $e->getMessage());
    $profile = [
        'profile_image' => 'default-profile.png',
        'username' => 'Unknown User',
        'email' => 'Not Available'
    ];
}

// Fetch quiz analytics for this teacher's quizzes
$analytics_stmt = $conn->prepare("
    SELECT q.id, q.title, COUNT(r.id) AS attempts, MAX(r.total) AS total_possible_score,  
    COALESCE(AVG(NULLIF(r.score, 0)), NULL) AS avg_score
    FROM quizzes q
    LEFT JOIN quiz_results r ON q.id = r.quiz_id
    WHERE q.subject_id IN (SELECT id FROM subjects WHERE teacher_id = ?)
    GROUP BY q.id
");
$analytics_stmt->execute([$teacher_id]);
$analytics_data = $analytics_stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare chart data for each quiz
$chart_data = [];
foreach ($analytics_data as $quiz) {
    $student_stmt = $conn->prepare("
        SELECT u.username AS student_name, COALESCE(r.score, NULL) AS score, r.total AS total_possible_score
        FROM quiz_results r
        JOIN users u ON r.student_id = u.id
        WHERE r.quiz_id = ? AND u.role = 'student'
    ");
    $student_stmt->execute([$quiz['id']]);
    $students = $student_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Include total possible score for each quiz
    $chart_data[$quiz['id']] = [
        'labels' => array_column($students, 'student_name'),
        'scores' => array_map(fn($s) => round($s['score'], 2), $students),
        'total' => $quiz['total_possible_score'],  // Pass total quiz score for comparison
        'students' => $students
    ];
}

?>