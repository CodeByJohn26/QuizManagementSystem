<?php
require_once '../includes/db_connection.php';
$pdo = $conn;

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// === Helper Functions ===
function getQuizzes($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM quizzes ORDER BY created_at DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getQuestions($quiz_id, $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM questions WHERE quiz_id = ? ORDER BY created_at ASC");
    $stmt->execute([$quiz_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function sanitizeInput($input) {
    return htmlspecialchars(trim($input));
}

// === Add Question ===
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_question"])) {
    $quiz_id = sanitizeInput($_POST["quiz_id"] ?? '');
    $question_text = sanitizeInput($_POST["question_text"] ?? '');
    $question_type = sanitizeInput($_POST["question_type"] ?? '');
    $correct_answer = null;
    $max_score = null;
    $choices = null;

    if (!$quiz_id || !$question_text || !$question_type) {
        $_SESSION['error_message'] = "Required fields are missing.";
        header("Location: add_questions.php?quiz_id=$quiz_id");
        exit();
    }

    switch ($question_type) {
        case "multiple_choice":
            $choicesArray = array_filter($_POST["choices"] ?? []);
            $choices = json_encode($choicesArray);
            $correct_answer = sanitizeInput($_POST["correct_choice"] ?? '');
            if (empty($choicesArray) || !$correct_answer) {
                $_SESSION['error_message'] = "Multiple choice questions require choices and a correct answer.";
                header("Location: add_questions.php?quiz_id=$quiz_id");
                exit();
            }
            break;

        case "true_false":
        case "identification":
            $correct_answer = sanitizeInput($_POST["correct_answer"] ?? '');
            if (!$correct_answer) {
                $_SESSION['error_message'] = "Correct answer is required for this question type.";
                header("Location: add_questions.php?quiz_id=$quiz_id");
                exit();
            }
            break;

        case "essay":
            $max_score = (int) sanitizeInput($_POST["max_score"] ?? 0);
            if (!$max_score) {
                $_SESSION['error_message'] = "Essay questions require a maximum score.";
                header("Location: add_questions.php?quiz_id=$quiz_id");
                exit();
            }
            break;
    }

    try {
        $stmt = $pdo->prepare("
            INSERT INTO questions (quiz_id, question_text, question_type, correct_answer, choices, max_score, created_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$quiz_id, $question_text, $question_type, $correct_answer, $choices, $max_score]);
        $_SESSION['success_message'] = "Question added.";
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Error adding question: " . $e->getMessage();
    }
    header("Location: add_questions.php?quiz_id=$quiz_id");
    exit();
}

// === Update Question ===
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_question"])) {
    $question_id = sanitizeInput($_POST["question_id"] ?? '');
    $quiz_id = sanitizeInput($_POST["quiz_id"] ?? '');
    $question_text = sanitizeInput($_POST["question_text"] ?? '');
    $question_type = sanitizeInput($_POST["question_type"] ?? '');
    $correct_answer = null;
    $max_score = null;
    $choices = null;

    if (!$question_id || !$quiz_id || !$question_text || !$question_type) {
        $_SESSION['error_message'] = "Required fields are missing.";
        header("Location: add_questions.php?quiz_id=$quiz_id");
        exit();
    }

    switch ($question_type) {
        case "multiple_choice":
            $choicesArray = array_filter($_POST["choices"] ?? []);
            $choices = json_encode($choicesArray);
            $correct_answer = sanitizeInput($_POST["correct_choice"] ?? '');
            if (empty($choicesArray) || !$correct_answer) {
                $_SESSION['error_message'] = "Multiple choice questions require choices and a correct answer.";
                header("Location: add_questions.php?quiz_id=$quiz_id");
                exit();
            }
            break;

        case "true_false":
    $correct_answer = strtolower(trim($_POST["correct_answer"] ?? ''));
    if (!in_array($correct_answer, ["true", "false"], true)) {
        $_SESSION['error_message'] = "True/False questions require a valid answer.";
        header("Location: add_questions.php?quiz_id=$quiz_id");
        exit();
    }
    break;
        case "identification":
            $correct_answer = sanitizeInput($_POST["correct_answer"] ?? '');
            if (!$correct_answer) {
                $_SESSION['error_message'] = "Correct answer is required.";
                header("Location: add_questions.php?quiz_id=$quiz_id");
                exit();
            }
            break;

        case "essay":
            $max_score = (int) sanitizeInput($_POST["max_score"] ?? 0);
            if (!$max_score) {
                $_SESSION['error_message'] = "Essay questions require a maximum score.";
                header("Location: add_questions.php?quiz_id=$quiz_id");
                exit();
            }
            break;
    }

    try {
        $stmt = $pdo->prepare("
            UPDATE questions SET 
                question_text = ?, question_type = ?, correct_answer = ?, choices = ?, max_score = ? 
            WHERE id = ?
        ");
        $stmt->execute([$question_text, $question_type, $correct_answer, $choices, $max_score, $question_id]);
        $_SESSION['success_message'] = "Question updated.";
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Error updating question: " . $e->getMessage();
    }
    header("Location: add_questions.php?quiz_id=$quiz_id");
    exit();
}

// === Delete Question ===
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_question"])) {
    $question_id = sanitizeInput($_POST["question_id"] ?? '');
    if (!$question_id) {
        $_SESSION['error_message'] = "No question selected for deletion.";
        header("Location: " . $_SERVER["HTTP_REFERER"]);
        exit();
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM questions WHERE id = ?");
        $stmt->execute([$question_id]);
        if ($stmt->fetch()) {
            $stmt = $pdo->prepare("DELETE FROM questions WHERE id = ?");
            $stmt->execute([$question_id]);
            $_SESSION['success_message'] = "Question deleted.";
        } else {
            $_SESSION['error_message'] = "Question not found.";
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Error deleting question: " . $e->getMessage();
    }
    header("Location: " . $_SERVER["HTTP_REFERER"]);
    exit();
}

// === End Quiz ===
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["end_quiz"])) {
    $quiz_id = sanitizeInput($_POST["quiz_id"] ?? '');
    if (!$quiz_id) {
        $_SESSION['error_message'] = "No quiz selected.";
        header("Location: view_quizzes.php");
        exit();
    }

    try {
        $stmt = $pdo->prepare("UPDATE quizzes SET quiz_completed = 1 WHERE id = ?");
        $stmt->execute([$quiz_id]);
        $_SESSION['success_message'] = "Quiz ended.";
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Error ending quiz: " . $e->getMessage();
    }
    header("Location: view_quizzes.php");
    exit();
}

// === Load Data for View ===
$quizzes = getQuizzes($pdo);
$quiz_id = $_GET["quiz_id"] ?? ($_POST["quiz_id"] ?? null);
$questions = $quiz_id ? getQuestions($quiz_id, $pdo) : [];

// Load Editing Question
$editing_question = null;
if (isset($_GET["edit_question_id"])) {
    $edit_id = sanitizeInput($_GET["edit_question_id"]);
    $stmt = $pdo->prepare("SELECT * FROM questions WHERE id = ?");
    $stmt->execute([$edit_id]);
    $editing_question = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
