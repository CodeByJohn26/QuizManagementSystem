<?php
session_start();
require_once '../includes/db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    echo "<script>alert('You must be logged in to view quizzes.'); window.location.href = 'login.php';</script>";
    exit;
}

if (!isset($_GET['subject_id'])) {
    echo "<p class='text-gray-500'>No subject selected.</p>";
    exit;
}

$subject_id = intval($_GET['subject_id']);
$student_id = $_SESSION['user_id'];

// Fetch quizzes for the selected subject
$stmt = $conn->prepare("SELECT id, title, time_limit, max_attempts FROM quizzes WHERE subject_id = ?");
$stmt->execute([$subject_id]);
$quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quizzes and Activities</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/0aaabd993c.js" crossorigin="anonymous"></script>
    <style>
        body {
            background-color: #f3f4f6;
            font-family: 'Inter', sans-serif;
        }
        .gradient-header {
            background: linear-gradient(to right, #facc15, #3b82f6);
            color: white;
        }
        .disabled-button {
            background-color: #ef4444;
            color: white;
            cursor: not-allowed;
            opacity: 0.7;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="flex items-center justify-between p-4 gradient-header shadow-lg">
        <div class="text-2xl font-bold"><i class="fa fa-user" aria-hidden="true"></i> Student Panel</div>
        <ul class="flex gap-6 text-white">
            <li><a href="student_dashboard.php" class="hover:text-yellow-300"><i class="fa fa-home" aria-hidden="true"></i> Dashboard</a></li>
            <li><a href="enroll_subject.php" class="hover:text-yellow-300"><i class="fa fa-plus" aria-hidden="true"></i> Enroll</a></li>
            <li><a href="view_results.php" class="hover:text-yellow-300"><i class="fa fa-flag-checkered" aria-hidden="true"></i> Results</a></li>
            <li><a href="edit_student_profile.php" class="hover:text-yellow-300"><i class="fa fa-user" aria-hidden="true"></i> Profile</a></li>
            <li><a href="logout.php" class="hover:text-yellow-300"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a></li>
        </ul>
    </nav>

    <main class="p-6 max-w-6xl mx-auto">
        <section class="mb-6 bg-white rounded-xl shadow-md p-6 border-l-8 border-yellow-300">
            <h1 class="text-2xl font-bold text-blue-600">Quizzes and Activities</h1>
            <p class="text-gray-600">Available quizzes for your selected subject are listed below.</p>
        </section>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php if (empty($quizzes)) { ?>
                <div class="col-span-full bg-white p-4 rounded shadow-md">
                    <p class="text-gray-500 text-center">No quizzes or activities found for this subject.</p>
                </div>
            <?php } else {
                foreach ($quizzes as $quiz) {
                    $attempts_stmt = $conn->prepare("SELECT COUNT(*) FROM quiz_attempts WHERE quiz_id = ? AND student_id = ?");
                    $attempts_stmt->execute([$quiz['id'], $student_id]);
                    $attempt_count = $attempts_stmt->fetchColumn();
                    $remaining_attempts = max(0, $quiz['max_attempts'] - $attempt_count);
                    ?>
                    <div class="bg-white shadow-md p-5 rounded-xl border-t-4 border-blue-400">
                        <h2 class="text-lg font-bold text-blue-700 mb-2"><?= htmlspecialchars($quiz['title']); ?></h2>
                        <p class="text-gray-600">Time Limit: <span class="font-semibold"><?= htmlspecialchars($quiz['time_limit']); ?> minutes</span></p>
                        <p class="text-gray-600 mb-4">Attempts Left: <span class="font-semibold"><?= $remaining_attempts; ?></span> / <?= htmlspecialchars($quiz['max_attempts']); ?></p>
                        <?php if ($remaining_attempts > 0) { ?>
                            <a href="take_quiz.php?quiz_id=<?= $quiz['id']; ?>" class="block text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Take Quiz
                            </a>
                        <?php } else { ?>
                            <button class="block w-full text-center disabled-button font-bold py-2 px-4 rounded" disabled>
                                Max Attempts Reached
                            </button>
                        <?php } ?>
                    </div>
                <?php }
            } ?>
        </div>
    </main>
</body>
</html>
