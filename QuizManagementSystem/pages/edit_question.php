<?php
require '../includes/db_connection.php';
session_start();

// Ensure the user is logged in and is a teacher
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit;
}

// Get the question ID
$question_id = isset($_GET['id']) ? intval($_GET['id']) : null;
if (!$question_id) {
    echo "<script>alert('Invalid question ID.'); window.location.href='add_questions.php';</script>";
    exit;
}

// Fetch the existing question details
$question_stmt = $conn->prepare("SELECT * FROM questions WHERE id = ?");
$question_stmt->execute([$question_id]);
$question = $question_stmt->fetch(PDO::FETCH_ASSOC);
if (!$question) {
    echo "<script>alert('Question not found.'); window.location.href='add_questions.php';</script>";
    exit;
}

// Handle form submission for updating the question
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_question'])) {
    $question_text = htmlspecialchars(trim($_POST['question_text']));
    $question_type = htmlspecialchars(trim($_POST['question_type']));
    $correct_answer = htmlspecialchars(trim($_POST['correct_answer'] ?? ''));

    try {
        $update_stmt = $conn->prepare("UPDATE questions SET question_text = ?, question_type = ?, correct_answer = ? WHERE id = ?");
        $update_stmt->execute([$question_text, $question_type, $correct_answer, $question_id]);

        echo "<script>alert('Question updated successfully.'); window.location.href='add_questions.php';</script>";
        exit;
    } catch (Exception $e) {
        echo "<script>alert('Error updating question: " . htmlspecialchars($e->getMessage()) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Question</title>
</head>
<body>
    <h2>Edit Question</h2>
    <form action="" method="POST">
        <label>Question Text:</label>
        <textarea name="question_text" required><?= htmlspecialchars($question['question_text']) ?></textarea>

        <label>Question Type:</label>
        <select name="question_type" required>
            <option value="multiple_choice" <?= $question['question_type'] === 'multiple_choice' ? 'selected' : '' ?>>Multiple Choice</option>
            <option value="true_false" <?= $question['question_type'] === 'true_false' ? 'selected' : '' ?>>True/False</option>
            <option value="essay" <?= $question['question_type'] === 'essay' ? 'selected' : '' ?>>Essay</option>
            <option value="identification" <?= $question['question_type'] === 'identification' ? 'selected' : '' ?>>Identification</option>
        </select>

        <label>Correct Answer:</label>
        <input type="text" name="correct_answer" value="<?= htmlspecialchars($question['correct_answer']) ?>">

        <button type="submit" name="edit_question">Update Question</button>
    </form>
</body>
</html>