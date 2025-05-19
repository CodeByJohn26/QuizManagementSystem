<?php
require '../includes/db_connection.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit;
}

try {
    $teacher_id = $_SESSION['user_id'];
    $profile_stmt = $conn->prepare("SELECT profile_image, username, email FROM users WHERE id = ? AND role = 'teacher'");
    $profile_stmt->execute([$teacher_id]);
    $profile = $profile_stmt->fetch(PDO::FETCH_ASSOC);

    if (!$profile) {
        $profile = [
            'profile_image' => 'default-profile.png',
            'username' => 'Unknown User',
            'email' => 'Not Available'
        ];
    }
} catch (Exception $e) {
    echo "<script>alert('Error fetching profile: " . htmlspecialchars($e->getMessage()) . "');</script>";
    $profile = [
        'profile_image' => 'default-profile.png',
        'username' => 'Unknown User',
        'email' => 'Not Available'
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quiz_title = htmlspecialchars($_POST['quiz_title']);
    $subject_id = (int)$_POST['subject_id'];
    $time_limit = (int)$_POST['time_limit'];
    $max_attempts = (int)$_POST['max_attempts'];
    $teacher_id = $_SESSION['user_id'];

    if ($max_attempts <= 0) {
        echo "<script>alert('Maximum attempts must be greater than zero.');</script>";
    } else {
        try {
            $stmt = $conn->prepare("INSERT INTO quizzes (title, subject_id, time_limit, max_attempts, created_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt->execute([$quiz_title, $subject_id, $time_limit, $max_attempts]);

            echo "<script>alert('Quiz created successfully!'); window.location.href = 'teacher_dashboard.php';</script>";
        } catch (Exception $e) {
            echo "<script>alert('Error creating quiz: " . htmlspecialchars($e->getMessage()) . "');</script>";
        }
    }
}

$subject_stmt = $conn->prepare("SELECT id, name FROM subjects WHERE teacher_id = ?");
$subject_stmt->execute([$teacher_id]);
$subjects = $subject_stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Quiz</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/0aaabd993c.js" crossorigin="anonymous"></script>
    <style>
        body {
            background-color: #f3f4f6;
            font-family: 'Inter', sans-serif;
        }
        .profile-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 0 auto 10px;
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
            <li><a href="teacher_dashboard.php" class="hover:text-yellow-300"><i class="fa fa-home" aria-hidden="true"></i> Dashboard</a></li>
            <li><a href="create_quiz.php" class="text-yellow-300 font-semibold"><i class="fa fa-plus" aria-hidden="true"></i> Create Quiz</a></li>
            <li><a href="view_analytics.php" class="hover:text-yellow-300"><i class="fa fa-line-chart" aria-hidden="true"></i> Analytics</a></li>
            <li><a href="edit_teacher_profile.php" class="hover:text-yellow-300"><i class="fa fa-user" aria-hidden="true"></i> Profile</a></li>
            <li><a href="logout.php" class="hover:text-yellow-300"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a></li>
        </ul>
    </nav>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-md border-t-4 border-yellow-400">
                <h2 class="text-2xl font-bold text-yellow-600 mb-6">Create a New Quiz</h2>
                <form action="create_quiz.php" method="POST" class="space-y-5">
                    <div>
                        <label for="quiz_title" class="block font-semibold mb-1">Quiz Title:</label>
                        <input type="text" name="quiz_title" id="quiz_title"
                               class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                               placeholder="Enter quiz title" required>
                    </div>

                    <div>
                        <label for="subject_id" class="block font-semibold mb-1">Select Subject:</label>
                        <select name="subject_id" id="subject_id"
                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                                required>
                            <option value="" disabled selected>Select Subject</option>
                            <?php foreach ($subjects as $subject): ?>
                                <option value="<?= htmlspecialchars($subject['id']); ?>">
                                    <?= htmlspecialchars($subject['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="time_limit" class="block font-semibold mb-1">Time Limit (minutes):</label>
                        <input type="number" name="time_limit" id="time_limit"
                               class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                               placeholder="Enter time limit" required>
                    </div>

                    <div>
                        <label for="max_attempts" class="block font-semibold mb-1">Maximum Attempts:</label>
                        <input type="number" name="max_attempts" id="max_attempts"
                               class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                               placeholder="Enter maximum attempts" required>
                    </div>

                    <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-500 text-white font-semibold py-2 px-4 rounded-md">
                        Create Quiz
                    </button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
