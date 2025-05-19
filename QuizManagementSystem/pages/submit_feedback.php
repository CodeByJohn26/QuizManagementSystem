<?php
session_start(); // Initialize session
require_once '../includes/db_connection.php'; // Include database connection file

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Sanitize inputs
$student_id = $_SESSION['user_id'];
$subject_id = isset($_POST['subject_id']) ? (int)$_POST['subject_id'] : null;
$message = isset($_POST['message']) ? trim($_POST['message']) : null;

if (!$subject_id || empty($message)) {
    echo "<script>alert('Please fill out all fields.'); window.history.back();</script>";
    exit;
}

// Fetch teacher ID based on the subject
$stmt = $conn->prepare("SELECT teacher_id FROM subjects WHERE id = ?");
$stmt->execute([$subject_id]);
$teacher = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$teacher) {
    echo "<script>alert('Invalid course selected.'); window.history.back();</script>";
    exit;
}

$teacher_id = $teacher['teacher_id'];

try {
    // Insert feedback into the database
    $stmt = $conn->prepare("INSERT INTO feedback (student_id, teacher_id, subject_id, message) VALUES (?, ?, ?, ?)");
    $stmt->execute([$student_id, $teacher_id, $subject_id, $message]);

    echo "<script>alert('Feedback submitted successfully!'); window.location.href = 'student_dashboard.php';</script>";
} catch (Exception $e) {
    echo "<script>alert('Error submitting feedback: " . $e->getMessage() . "'); window.history.back();</script>";
}
?>