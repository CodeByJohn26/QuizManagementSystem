<?php
require '../includes/db_connection.php'; // Include the database connection
session_start(); // Start the session

// Ensure the user is logged in and is a teacher
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit;
}

// Assign teacher ID from session
$teacher_id = $_SESSION['user_id'];

try {
    // Fetch teacher's profile information
    $profile_stmt = $conn->prepare("SELECT profile_image, username, email FROM users WHERE id = ? AND role = 'teacher'");
    $profile_stmt->execute([$teacher_id]);
    $profile = $profile_stmt->fetch(PDO::FETCH_ASSOC);

    if (!$profile) {
        throw new Exception('Teacher profile not found.');
    }

    // Fetch subjects assigned to the teacher
    $subject_stmt = $conn->prepare("SELECT id, name, description, code, color FROM subjects WHERE teacher_id = ?");
    $subject_stmt->execute([$teacher_id]);
    $subjects = $subject_stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$subjects) {
        $subjects = []; // Fallback in case no subjects are found
    }

    // Fetch quizzes created by the teacher
    $quiz_stmt = $conn->prepare("
        SELECT quizzes.id, quizzes.title, subjects.name AS subject_name
        FROM quizzes
        JOIN subjects ON quizzes.subject_id = subjects.id
        WHERE subjects.teacher_id = ?");
    $quiz_stmt->execute([$teacher_id]);
    $quizzes = $quiz_stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$quizzes) {
        $quizzes = []; // Fallback in case no quizzes are found
    }
} catch (Exception $e) {
    echo "<script>alert('" . htmlspecialchars($e->getMessage()) . "'); window.location.href = 'login.php';</script>";
    exit;
}

// Logout functionality
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/0aaabd993c.js" crossorigin="anonymous"></script>
    <style>
        body {
            background-color: #f3f4f6;
            font-family: 'Inter', sans-serif;
        }
        .profile-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin: 0 auto 15px;
            display: block;
        }
        .color-box {
            width: 50px;
            height: 20px;
            border-radius: 5px;
            margin: auto;
            display: block;
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
        <div class="text-2xl font-bold"><i class="fa fa-user" aria-hidden="true"></i> Teacher Panel</div>
        <ul class="flex gap-6 text-white">
            <li><a href="teacher_dashboard.php" class="text-yellow-300 font-semibold"><i class="fa fa-home" aria-hidden="true"></i> Dashboard</a></li>
            <li><a href="create_quiz.php" class="hover:text-yellow-300"><i class="fa fa-plus" aria-hidden="true"></i> Create Quiz</a></li>
            <li><a href="view_analytics.php" class="hover:text-yellow-300"><i class="fa fa-line-chart" aria-hidden="true"></i> Analytics</a></li>
            <li><a href="edit_teacher_profile.php" class="hover:text-yellow-300"><i class="fa fa-user" aria-hidden="true"></i> Profile</a></li>
            <li><a href="logout.php" class="hover:text-yellow-300"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a></li>
        </ul>
    </nav>

    <main class="p-6 max-w-6xl mx-auto">
        <!-- Welcome Section -->
        <section class="mb-6 bg-white rounded-xl shadow-md p-6 border-l-8 border-yellow-300">
            <h1 class="text-xl font-bold text-blue-600">Welcome, Professor <?= htmlspecialchars($profile['username']); ?>!</h1>
            <p class="text-gray-600">Manage your subjects, quizzes, and feedback efficiently.</p>
        </section>

        <!-- Subjects Section -->
        <section class="mb-6 bg-white p-6 rounded-xl shadow-md border-t-4 border-blue-400">
            <h2 class="text-xl font-bold text-blue-700 mb-4">Your Subjects</h2>
            <?php if (count($subjects) > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-center border border-gray-300">
                        <thead class="bg-blue-600 text-white">
                            <tr>
                                <th class="px-4 py-2">Subject Name</th>
                                <th class="px-4 py-2">Code</th>
                                <th class="px-4 py-2">Color</th>
                                <th class="px-4 py-2">Description</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            <?php foreach ($subjects as $subject): ?>
                                <tr class="border-t">
                                    <td class="px-4 py-2"><?= htmlspecialchars($subject['name']); ?></td>
                                    <td class="px-4 py-2"><?= htmlspecialchars($subject['code']); ?></td>
                                    <td class="px-4 py-2">
                                        <div class="color-box" style="background-color: <?= htmlspecialchars($subject['color'] ?? '#ffffff'); ?>;"></div>
                                    </td>
                                    <td class="px-4 py-2"><?= htmlspecialchars($subject['description']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-gray-500">No subjects available. Create your first subject!</p>
            <?php endif; ?>
        </section>

        <!-- Quizzes Section -->
        <section class="mb-6 bg-white p-6 rounded-xl shadow-md border-t-4 border-yellow-400">
            <h2 class="text-xl font-bold text-yellow-600 mb-4">Your Quizzes</h2>
            <?php if (count($quizzes) > 0): ?>
                <ul class="space-y-4">
                    <?php foreach ($quizzes as $quiz): ?>
                        <li class="flex justify-between items-center border-b pb-2">
                            <span><?= htmlspecialchars($quiz['title']) ?> (Subject: <?= htmlspecialchars($quiz['subject_name']); ?>)</span>
                            <a href="add_questions.php?quiz_id=<?= $quiz['id']; ?>" class="bg-blue-500 hover:bg-blue-400 text-white px-3 py-1 rounded">Add Questions</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-gray-500">No quizzes available. Create your first quiz!</p>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>