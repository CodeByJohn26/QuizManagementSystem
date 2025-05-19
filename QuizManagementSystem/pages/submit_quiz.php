<?php
session_start();
require_once '../includes/db_connection.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$quiz_id = isset($_POST['quiz_id']) ? (int)$_POST['quiz_id'] : null;
$student_id = $_SESSION['user_id'];

if (!$quiz_id) {
    echo "<script>alert('Invalid quiz ID.'); window.history.back();</script>";
    exit;
}

// Fetch questions for the quiz
$stmt = $conn->prepare("SELECT id, question_type, correct_answer, choices, case_sensitive FROM questions WHERE quiz_id = ?");
$stmt->execute([$quiz_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($questions)) {
    echo "<script>alert('No questions found for this quiz.'); window.history.back();</script>";
    exit;
}

// Calculate the score
$total_questions = count($questions);
$score = 0;

foreach ($questions as $question) {
    $question_id = $question['id'];
    $correct_answer = trim($question['correct_answer']);
    $student_answer = isset($_POST["question_$question_id"]) ? trim($_POST["question_$question_id"]) : null;

    if ($student_answer !== null) {
        error_log("DEBUG | Answer received | Question ID: $question_id | Answer: '$student_answer' | Case-Sensitive: " . json_encode($question['case_sensitive']));

        if ($question['question_type'] === 'multiple_choice') {
            $choices = json_decode($question['choices'], true);
            if (!in_array($student_answer, $choices, true)) {
                error_log("DEBUG | Invalid answer selected for Question ID: $question_id | Answer: '$student_answer'");
                continue; // Skip scoring invalid answers
            }
        }

        if ($question['question_type'] === 'multiple_choice' || $question['question_type'] === 'true_false') {
            if (strcasecmp($student_answer, $correct_answer) === 0) {
                $score++;
            }
        } elseif ($question['question_type'] === 'identification') {
            if ((int)$question['case_sensitive'] == 1) {
                $normalized_student_answer = trim($student_answer);
                $normalized_correct_answer = trim($correct_answer);
                if (strcmp($normalized_student_answer, $normalized_correct_answer) === 0) {
                    $score++;
                }
            } else {
                if (strcasecmp($student_answer, $correct_answer) === 0) {
                    $score++;
                }
            }
        }
    } else {
        error_log("DEBUG | No answer provided for Question ID: $question_id");
    }
}

// Save results to the database
$stmt = $conn->prepare("INSERT INTO quiz_results (student_id, quiz_id, score, total) VALUES (?, ?, ?, ?)");
if (!$stmt->execute([$student_id, $quiz_id, $score, $total_questions])) {
    error_log("DEBUG | Database Insert Failed!");
} else {
    error_log("DEBUG | Quiz Results Saved!");
}

// Redirect with success message
echo "<script>alert('Quiz submitted successfully! Your score: $score / $total_questions.'); window.location.href = 'view_results.php';</script>";

?>