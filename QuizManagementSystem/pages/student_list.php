<?php
require '../includes/db_connection.php';
session_start();

// Ensure the user is logged in and is a teacher
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit;
}

$professor_id = $_SESSION['user_id'];

// Fetch student details and enrolled subjects
$stmt = $conn->prepare("
    SELECT users.id, users.name, COUNT(enrollments.subject_id) AS total_subjects
    FROM users
    JOIN enrollments ON users.id = enrollments.student_id
    WHERE enrollments.professor_id = ? AND users.role = 'student'
    GROUP BY users.id;
");
$stmt->execute([$professor_id]);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Students Under You</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center text-primary">Students Under Your Supervision</h1>
        <p class="text-center">Total Students: <strong><?= count($students); ?></strong></p>
        <ul class="list-group">
            <?php foreach ($students as $student): ?>
                <li class="list-group-item">
                    <strong><?= htmlspecialchars($student['name']); ?></strong> 
                    - Enrolled in <strong><?= htmlspecialchars($student['total_subjects']); ?></strong> subject(s)
                    <a href="track_student.php?student_id=<?= $student['id']; ?>" class="btn btn-info btn-sm">Track Activities</a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>