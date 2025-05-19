<?php
require '../includes/db_connection.php';
session_start();

// Ensure the user is logged in and is a teacher
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit;
}

// Get student ID from URL
$student_id = $_GET['student_id'] ?? null;

if (!$student_id) {
    echo "<script>alert('Student ID is missing!'); window.location.href = 'student_list.php';</script>";
    exit;
}

// Fetch student activities (e.g., quizzes taken and scores)
$stmt = $conn->prepare("
    SELECT quizzes.title, results.score, results.created_at
    FROM results
    JOIN quizzes ON results.quiz_id = quizzes.id
    WHERE results.student_id = ?");
$stmt->execute([$student_id]);
$activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Activities</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center text-primary">Student Activities</h1>
        <ul class="list-group mt-4">
            <?php if (!empty($activities)): ?>
                <?php foreach ($activities as $activity): ?>
                    <li class="list-group-item">
                        Quiz: <strong><?= htmlspecialchars($activity['title']); ?></strong> - 
                        Score: <strong><?= htmlspecialchars($activity['score']); ?></strong> - 
                        Taken on: <strong><?= htmlspecialchars($activity['created_at']); ?></strong>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="list-group-item text-center">No activities found for this student.</li>
            <?php endif; ?>
        </ul>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>