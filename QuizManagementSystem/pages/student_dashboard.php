<?php
session_start();
require_once '../includes/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
$user_stmt->execute([$_SESSION['user_id']]);
$user = $user_stmt->fetch(PDO::FETCH_ASSOC);

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("
    SELECT DISTINCT subjects.id AS subject_id, subjects.name AS course_name, subjects.color AS course_color
    FROM enrollments
    INNER JOIN subjects ON enrollments.subject_id = subjects.id
    WHERE enrollments.user_id = ?
");
$stmt->execute([$user_id]);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/0aaabd993c.js" crossorigin="anonymous"></script>
    <style> 
        body {
            background-color: #f3f4f6;
            font-family: 'Inter', sans-serif;
        }
        .course-button {
            color: white;
            font-weight: bold;
            padding: 10px;
            border-radius: 6px;
            width: 100%;
            margin-top: 10px;
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
            <li><a href="student_dashboard.php" class="text-yellow-300 font-semibold"><i class="fa fa-home" aria-hidden="true"></i> Dashboard</a></li>
            <li><a href="enroll_subject.php" class="hover:text-yellow-300"><i class="fa fa-plus" aria-hidden="true"></i> Enroll</a></li>
            <li><a href="view_results.php" class="hover:text-yellow-300"><i class="fa fa-flag-checkered" aria-hidden="true"></i> Results</a></li>
            <li><a href="edit_student_profile.php" class="hover:text-yellow-300"><i class="fa fa-user" aria-hidden="true"></i> Profile</a></li>
            <li><a href="logout.php" class="hover:text-yellow-300"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a></li>
        </ul>
    </nav>

    <main class="p-6 max-w-6xl mx-auto">
        <!-- Welcome Section -->
        <section class="mb-6 bg-white rounded-xl shadow-md p-6 border-l-8 border-yellow-300">
            <h1 class="text-2xl font-bold text-blue-600">Welcome, <?= htmlspecialchars($user['username']); ?>!</h1>
            <p class="text-gray-600">Manage your subjects, view quizzes, and send feedback below.</p>
        </section>

        <!-- Dashboard Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Courses -->
            <div class="bg-white p-6 rounded-xl shadow-md border-t-4 border-blue-400">
                <h2 class="text-xl font-bold text-blue-700 mb-4">My Courses</h2>
                <?php if (empty($courses)) { ?>
                    <p class="text-gray-500">You haven't enrolled in any courses yet!</p>
                <?php } else { ?>
                    <ul>
                        <?php foreach ($courses as $course) { ?>
                            <li class="mb-4 border-b pb-2">
                                <span class="text-lg font-semibold"><?= htmlspecialchars($course['course_name']); ?></span>
                                <button onclick="window.location.href='view_quizzes.php?subject_id=<?= $course['subject_id']; ?>'"
                                    class="course-button"
                                    style="background-color: <?= htmlspecialchars($course['course_color']); ?>;">
                                    View Quizzes
                                </button>
                            </li>
                        <?php } ?>
                    </ul>
                <?php } ?>
            </div>

            <!-- Notifications -->
            <div class="bg-white p-6 rounded-xl shadow-md border-t-4 border-yellow-400">
                <h2 class="text-xl font-bold text-yellow-600 mb-4" style="font-family: 'Inter', sans-serif;"><i class="fa-solid fa-bell"></i> Notifications</h2>
                <p class="text-gray-500">You don’t have any notifications right now.</p>
            </div>
        </div>

        <!-- Feedback Section -->
        <section class="mt-8 bg-white p-6 rounded-xl shadow-md border-l-4 border-blue-500">
            <h2 class="text-xl font-bold text-blue-600 mb-4"><i class="fa fa-commenting-o" aria-hidden="true"></i> Send Feedback</h2>
            <form action="submit_feedback.php" method="POST" class="space-y-4">
                <div>
                    <label for="subject_id" class="block text-sm font-medium text-gray-700">Select Course:</label>
                    <select name="subject_id" id="subject_id" class="w-full border border-gray-300 rounded p-2" required>
                        <?php foreach ($courses as $course) { ?>
                            <option value="<?= $course['subject_id']; ?>">
                                <?= htmlspecialchars($course['course_name']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700">Message:</label>
                    <textarea name="message" id="message" rows="4" class="w-full border border-gray-300 rounded p-2" required></textarea>
                </div>
                <button type="submit" class="bg-yellow-400 hover:bg-yellow-300 text-white font-bold py-2 px-4 rounded">
                    Send Feedback
                </button>
            </form>
        </section>
    </main>
</body>
</html>
