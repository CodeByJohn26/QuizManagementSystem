<?php
session_start();
require_once __DIR__ . '/../includes/db_connection.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    echo "<script>alert('You must be logged in to take a quiz.'); window.location.href = 'login.php';</script>";
    exit;
}

$student_id = $_SESSION['user_id'];

if (!isset($_GET['quiz_id'])) {
    echo "<p class='text-gray-500'>No quiz selected.</p>";
    exit;
}

$quiz_id = intval($_GET['quiz_id']);

// Fetch quiz details
$quiz_stmt = $conn->prepare("SELECT title, time_limit, subject_id, max_attempts FROM quizzes WHERE id = ?");
$quiz_stmt->execute([$quiz_id]);
$quiz = $quiz_stmt->fetch(PDO::FETCH_ASSOC);

if (!$quiz) {
    echo "<p class='text-gray-500'>Quiz not found.</p>";
    exit;
}

// Check existing quiz attempts (Only count completed ones)
$attempt_stmt = $conn->prepare("SELECT COUNT(*) FROM quiz_attempts WHERE quiz_id = ? AND student_id = ? AND status = 'completed'");
$attempt_stmt->execute([$quiz_id, $student_id]);
$attempt_count = (int) $attempt_stmt->fetchColumn();

if ($attempt_count >= (int) $quiz['max_attempts']) {
    echo "<script>alert('You have reached the maximum number of attempts.'); window.location.href = 'view_quizzes.php?subject_id={$quiz['subject_id']}';</script>";
    exit;
}

// Ensure quiz session starts
if (!isset($_SESSION["quiz_$quiz_id"])) {
    try {
        $conn->beginTransaction();
        $attempt_stmt = $conn->prepare("INSERT INTO quiz_attempts (quiz_id, student_id, start_time, status) VALUES (?, ?, NOW(), 'in_progress')");
        $attempt_stmt->execute([$quiz_id, $student_id]);
        $_SESSION["quiz_$quiz_id"] = (int)$quiz['time_limit'] * 60;
        $conn->commit();
    } catch (Exception $e) {
        $conn->rollBack();
        error_log("Quiz attempt error: " . $e->getMessage());
        echo "<script>alert('Error recording attempt.'); window.history.back();</script>";
        exit;
    }
}

// Track remaining time
$remaining_time = $_SESSION["quiz_$quiz_id"];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remaining_time'])) {
    $remaining_time = $_POST['remaining_time'];
    $_SESSION["quiz_$quiz_id"] = $remaining_time;
}

// Fetch quiz questions (Only multiple-choice)
$question_stmt = $conn->prepare("SELECT id, question_text, question_type, correct_answer, choices FROM questions WHERE quiz_id = ? AND question_type = 'multiple_choice'");
$question_stmt->execute([$quiz_id]);
$questions = $question_stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($questions)) {
    echo "<script>alert('No multiple-choice questions found for this quiz.'); window.history.back();</script>";
    exit;
}

// Process quiz submission (Only validating multiple-choice)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_quiz'])) {
    $total_questions = count($questions);
    $score = 0;

    foreach ($questions as $question) {
        $question_id = $question['id'];
        $correct_answer = trim($question['correct_answer']);
        $choices = json_decode($question['choices'], true);
        $student_answer = isset($_POST["question_$question_id"]) ? trim($_POST["question_$question_id"]) : null;

        // Validate student's answer exists within defined choices
        if ($student_answer !== null && in_array($student_answer, $choices, true)) {
            error_log("DEBUG | Valid answer received | Question ID: $question_id | Answer: '$student_answer'");
            if (strcasecmp($student_answer, $correct_answer) === 0) {
                $score++;
            }
        } else {
            error_log("DEBUG | Invalid answer provided for Question ID: $question_id");
        }
    }

    try {
        $conn->beginTransaction();
        $result_stmt = $conn->prepare("INSERT INTO quiz_results (student_id, quiz_id, score, total, completion_time) VALUES (?, ?, ?, ?, NOW())");
        $result_stmt->execute([$student_id, $quiz_id, $score, $total_questions]);

        // Mark attempt as completed
        $update_attempt = $conn->prepare("UPDATE quiz_attempts SET status = 'completed' WHERE quiz_id = ? AND student_id = ?");
        $update_attempt->execute([$quiz_id, $student_id]);

        unset($_SESSION["quiz_$quiz_id"]);
        $conn->commit();

        echo "<script>alert('Quiz submitted successfully! Your score: $score/$total_questions.'); window.location.href = 'view_results.php';</script>";
    } catch (Exception $e) {
        $conn->rollBack();
        error_log("Quiz result saving error: " . $e->getMessage());
        echo "<script>alert('Error saving quiz results.'); window.history.back();</script>";
    }
    exit;
}
?>
