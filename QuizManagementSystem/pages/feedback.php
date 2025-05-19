<?php
require '../includes/db_connection.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'];

// Fetch feedback for the teacher
if ($user_role === 'teacher') {
    $feedback_stmt = $conn->prepare("
        SELECT feedback.comment, feedback.created_at, users.username AS student_name
        FROM feedback
        JOIN users ON feedback.student_id = users.id
        WHERE feedback.teacher_id = ?
        ORDER BY feedback.created_at DESC
    ");
    $feedback_stmt->execute([$user_id]);
    $feedback_data = $feedback_stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Submit feedback for a professor
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user_role === 'student') {
    $teacher_id = $_POST['teacher_id'] ?? null;
    $comment = $_POST['comment'] ?? null;

    if ($teacher_id && $comment) {
        $stmt = $conn->prepare("INSERT INTO feedback (student_id, teacher_id, comment) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $teacher_id, htmlspecialchars($comment)]);
        header("Location: feedback.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center text-primary">Feedback</h1>

        <?php if ($user_role === 'teacher'): ?>
            <h2 class="text-secondary mt-4">Feedback from Students</h2>
            <?php if (count($feedback_data) > 0): ?>
                <ul class="list-group">
                    <?php foreach ($feedback_data as $feedback): ?>
                        <li class="list-group-item">
                            <strong><?= htmlspecialchars($feedback['student_name']); ?>:</strong> 
                            <?= htmlspecialchars($feedback['comment']); ?> 
                            <span class="text-muted">(<?= htmlspecialchars($feedback['created_at']); ?>)</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No feedback available.</p>
            <?php endif; ?>
        <?php elseif ($user_role === 'student'): ?>
            <form method="POST" class="mt-4">
                <div class="mb-3">
                    <label for="teacher_id" class="form-label">Select Professor</label>
                    <select id="teacher_id" name="teacher_id" class="form-select" required>
                        <?php
                        $teacher_stmt = $conn->prepare("SELECT id, username FROM users WHERE role = 'teacher'");
                        $teacher_stmt->execute();
                        $teachers = $teacher_stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($teachers as $teacher):
                        ?>
                            <option value="<?= $teacher['id']; ?>"><?= htmlspecialchars($teacher['username']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="comment" class="form-label">Your Feedback</label>
                    <textarea id="comment" name="comment" class="form-control" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-success">Submit Feedback</button>
            </form>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>