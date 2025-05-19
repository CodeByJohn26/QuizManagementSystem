<?php
session_start();
require_once '../includes/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$student_id = $_SESSION['user_id'];

try {
    $stmt = $conn->prepare("
        SELECT 
            qr.score, 
            qr.total, 
            qr.created_at, 
            quizzes.title AS quiz_title, 
            subjects.name AS subject_name,
            subjects.color AS subject_color
        FROM quiz_results qr
        INNER JOIN quizzes ON qr.quiz_id = quizzes.id
        INNER JOIN subjects ON quizzes.subject_id = subjects.id
        WHERE qr.student_id = ?
        GROUP BY qr.quiz_id
        ORDER BY qr.created_at DESC
    ");
    $stmt->execute([$student_id]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "<script>alert('Error fetching quiz results: " . htmlspecialchars($e->getMessage()) . "');</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz Results</title>
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
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="flex items-center justify-between p-4 gradient-header shadow-lg">
        <div class="text-2xl font-bold"><i class="fa fa-user" aria-hidden="true"></i> Student Panel</div>
        <ul class="flex gap-6 text-white">
            <li><a href="student_dashboard.php" class="hover:text-yellow-300"><i class="fa fa-home" aria-hidden="true"></i> Dashboard</a></li>
            <li><a href="enroll_subject.php" class="hover:text-yellow-300"><i class="fa fa-plus" aria-hidden="true"></i> Enroll</a></li>
            <li><a href="view_results.php" class="text-yellow-300 font-semibold"><i class="fa fa-flag-checkered" aria-hidden="true"></i> Results</a></li>
            <li><a href="edit_student_profile.php" class="hover:text-yellow-300"><i class="fa fa-user" aria-hidden="true"></i> Profile</a></li>
            <li><a href="logout.php" class="hover:text-yellow-300"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main class="p-6 max-w-5xl mx-auto">
        <section class="mb-6 bg-white rounded-xl shadow-md p-6 border-l-8 border-blue-400">
            <h1 class="text-2xl font-bold text-blue-600"><i class="fa fa-flag-checkered" aria-hidden="true"></i> Quiz Results</h1>
            <p class="text-gray-600">Track your performance and progress over time.</p>
        </section>

        <div class="bg-white rounded-xl shadow-md p-6 border-t-4 border-yellow-400">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-yellow-600">Your Results</h2>
                <a href="student_dashboard.php" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded font-semibold">
                    Take Another Quiz
                </a>
            </div>

            <?php if (empty($results)) { ?>
                <p class="text-center text-gray-500">No results found. Take some quizzes to see your progress!</p>
            <?php } else { ?>
                <ul class="space-y-4">
                    <?php foreach ($results as $result): 
                        $color = htmlspecialchars($result['subject_color'] ?? '#3b82f6'); // fallback color
                        ?>
                        <li class="p-4 rounded shadow-sm transition bg-gray-50 hover:bg-gray-100"
                            style="border-left: 6px solid <?= $color ?>;">
                            <p><strong>Quiz:</strong> <?= htmlspecialchars($result['quiz_title']); ?></p>
                            <p><strong>Subject:</strong> 
                                <span style="color: <?= $color ?>;">
                                    <?= htmlspecialchars($result['subject_name']); ?>
                                </span>
                            </p>
                            <p><strong>Score:</strong> <?= htmlspecialchars($result['score']) . " / " . htmlspecialchars($result['total']); ?></p>
                            <p class="text-sm text-gray-500 mt-2">Completed on: <?= htmlspecialchars($result['created_at']); ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php } ?>
        </div>
    </main>
</body>
</html>