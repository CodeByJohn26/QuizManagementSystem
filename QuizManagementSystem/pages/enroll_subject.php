<?php
session_start();
require_once '../includes/db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $passcode = htmlspecialchars($_POST['passcode']);
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT id, name, color FROM subjects WHERE code = ?");
    $stmt->execute([$passcode]);
    $subject = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($subject) {
        $check = $conn->prepare("SELECT * FROM enrollments WHERE user_id = ? AND subject_id = ?");
        $check->execute([$user_id, $subject['id']]);
        if ($check->rowCount() > 0) {
            $message = "You are already enrolled in this subject!";
        } else {
            $insert = $conn->prepare("INSERT INTO enrollments (user_id, subject_id) VALUES (?, ?)");
            $insert->execute([$user_id, $subject['id']]);

            $notif = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
            $notif->execute([$user_id, "You have successfully enrolled in " . htmlspecialchars($subject['name']) . "."]);

            $message = "Successfully enrolled in " . htmlspecialchars($subject['name']) . "!";
        }
    } else {
        $message = "Invalid passcode. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enroll in Subject</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/0aaabd993c.js" crossorigin="anonymous"></script>
    <style>
        body {
            background-color: #f9fafb;
            font-family: 'Inter', sans-serif;
        }
        .gradient-header {
            background: linear-gradient(to right, #facc15, #3b82f6);
            color: white;
        }
        .subject-button {
            color: white;
            font-weight: bold;
            padding: 10px 15px;
            border-radius: 6px;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="flex items-center justify-between p-4 gradient-header shadow-lg">
        <div class="text-2xl font-bold"><i class="fa fa-user" aria-hidden="true"></i> Student Panel</div>
        <ul class="flex gap-6 text-white">
            <li><a href="student_dashboard.php" class="hover:text-yellow-300"><i class="fa fa-home" aria-hidden="true"></i> Dashboard</a></li>
            <li><a href="enroll_subject.php" class="text-yellow-300 font-semibold"><i class="fa fa-plus" aria-hidden="true"></i> Enroll</a></li>
            <li><a href="view_results.php" class="hover:text-yellow-300"><i class="fa fa-flag-checkered" aria-hidden="true"></i> Results</a></li>
            <li><a href="edit_student_profile.php" class="hover:text-yellow-300"><i class="fa fa-user" aria-hidden="true"></i> Profile</a></li>
            <li><a href="logout.php" class="hover:text-yellow-300"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a></li>
        </ul>
    </nav>

    <main class="max-w-4xl mx-auto p-6">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-md border-l-8 border-yellow-300 p-6 mb-6">
            <h1 class="text-2xl font-bold text-blue-600">Enroll in a Subject</h1>
            <p class="text-gray-600">Enter the subject passcode to enroll below.</p>
        </div>

        <!-- Message -->
        <?php if (!empty($message)) { ?>
            <div class="mb-4 p-4 rounded text-white font-medium <?= strpos($message, 'Successfully') !== false ? 'bg-green-500' : 'bg-red-500' ?>">
                <?= $message; ?>
            </div>
        <?php } ?>

        <!-- Enrollment Form -->
        <div class="bg-white p-6 rounded-xl shadow-md border-t-4 border-blue-400 mb-6">
            <h2 class="text-xl font-bold text-blue-700 mb-4">Enter Subject Passcode</h2>
            <form method="POST" class="space-y-4">
                <div>
                    <label for="passcode" class="block text-gray-700 font-medium mb-1">Passcode:</label>
                    <input type="text" name="passcode" id="passcode" class="w-full border border-gray-300 rounded p-2" required placeholder="Enter subject passcode">
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 px-4 rounded w-full">Enroll</button>
            </form>
        </div>

        <!-- Available Subjects -->
        <div class="bg-white p-6 rounded-xl shadow-md border-t-4 border-yellow-400">
            <h2 class="text-xl font-bold text-yellow-600 mb-4">Available Subjects</h2>
            <div class="flex flex-wrap gap-3">
                <?php
                $stmt = $conn->prepare("SELECT name, color FROM subjects");
                $stmt->execute();
                $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <?php if (!empty($subjects)) { ?>
                    <?php foreach ($subjects as $subject) { ?>
                        <button class="subject-button" style="background-color: <?= htmlspecialchars($subject['color']); ?>;">
                            <?= htmlspecialchars($subject['name']); ?>
                        </button>
                    <?php } ?>
                <?php } else { ?>
                    <p class="text-gray-500">No subjects available at the moment.</p>
                <?php } ?>
            </div>
        </div>
    </main>
</body>
</html>
