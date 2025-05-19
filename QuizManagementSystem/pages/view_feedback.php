<?php
session_start();
require '../includes/db_connection.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

try {
    $feedbacks = [];
    $enrollments = [];

    // Fetch feedback
    $stmt = $conn->prepare("
        SELECT feedback.id, 
               feedback.message, 
               feedback.created_at, 
               students.username AS student_name, 
               teachers.username AS teacher_name, 
               subjects.name AS subject_name
        FROM feedback
        INNER JOIN users AS students ON feedback.student_id = students.id
        INNER JOIN users AS teachers ON feedback.teacher_id = teachers.id
        INNER JOIN subjects ON feedback.subject_id = subjects.id
        ORDER BY feedback.created_at DESC
    ");
    $stmt->execute();
    $feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch enrollments
    $enrollment_stmt = $conn->prepare("
        SELECT enrollments.id AS enrollment_id,
               students.username AS student_name,
               subjects.name AS subject_name,
               enrollments.enrolled_at
        FROM enrollments
        INNER JOIN users AS students ON enrollments.user_id = students.id
        INNER JOIN subjects ON enrollments.subject_id = subjects.id
        ORDER BY enrollments.enrolled_at DESC
    ");
    $enrollment_stmt->execute();
    $enrollments = $enrollment_stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Feedback & Enrollments</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/0aaabd993c.js" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .gradient-header {
            background: linear-gradient(to right, #facc15, #3b82f6);
            color: white;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Navbar -->
    <nav class="flex items-center justify-between p-4 gradient-header shadow-lg">
        <div class="text-2xl font-bold"><i class="fa fa-user"></i> Admin Panel</div>
        <ul class="flex gap-6 text-white">
            <li><a href="admin_dashboard.php" class="hover:text-yellow-300"><i class="fa fa-home"></i> Dashboard</a></li>
            <li><a href="monitor_users.php" class="hover:text-yellow-300"><i class="fa fa-user"></i> Users</a></li>
            <li><a href="assign_subject_admin.php" class="hover:text-yellow-300"><i class="fa fa-book"></i> Subjects</a></li>
            <li><a href="view_feedback.php" class="text-yellow-300 font-semibold"><i class="fa fa-commenting-o"></i> Feedback</a></li>
            <li><a href="logout.php" class="hover:text-yellow-300"><i class="fa fa-sign-out"></i> Logout</a></li>
        </ul>
    </nav>

    <!-- Main -->
    <main class="max-w-7xl mx-auto p-6">
        <h1 class="text-3xl font-bold text-blue-600 text-center mb-8">Feedback and Enrollments Overview</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Feedback Section -->
            <div class="bg-white p-8 rounded-xl shadow-md border-l-8 border-green-300 space-y-4">
                <h2 class="text-2xl font-bold text-green-600 mb-6"><i class="fa fa-commenting-o"></i> Feedback Records</h2>
                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-bold text-gray-600">ID</th>
                                <th class="px-4 py-2 text-left text-sm font-bold text-gray-600">Student</th>
                                <th class="px-4 py-2 text-left text-sm font-bold text-gray-600">Teacher</th>
                                <th class="px-4 py-2 text-left text-sm font-bold text-gray-600">Subject</th>
                                <th class="px-4 py-2 text-left text-sm font-bold text-gray-600">Created At</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (!empty($feedbacks)): ?>
                                <?php foreach ($feedbacks as $feedback): ?>
                                    <tr>
                                        <td class="px-4 py-2 text-sm text-gray-700 align-top"><?php echo $feedback['id']; ?></td>
                                        <td class="px-4 py-2 text-sm text-gray-700 align-top"><?php echo htmlspecialchars($feedback['student_name']); ?></td>
                                        <td class="px-4 py-2 text-sm text-gray-700 align-top"><?php echo htmlspecialchars($feedback['teacher_name']); ?></td>
                                        <td class="px-4 py-2 text-sm text-gray-700 align-top"><?php echo htmlspecialchars($feedback['subject_name']); ?></td>
                                        <td class="px-4 py-2 text-sm text-gray-700 align-top"><?php echo $feedback['created_at']; ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="px-4 pt-4 pb-6 text-sm text-gray-800 bg-gray-50">
                                            <p class="whitespace-pre-line"><?php echo nl2br(htmlspecialchars($feedback['message'])); ?></p>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="px-4 py-4 text-center text-gray-500">No feedback found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Enrollment Section -->
            <div class="bg-white p-8 rounded-xl shadow-md border-l-8 border-blue-300 max-h-[600px] overflow-auto space-y-4">
                <h2 class="text-2xl font-bold text-blue-600 mb-6">Enrollment Records</h2>
                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-bold text-gray-600">Enrollment ID</th>
                                <th class="px-4 py-2 text-left text-sm font-bold text-gray-600">Student</th>
                                <th class="px-4 py-2 text-left text-sm font-bold text-gray-600">Subject</th>
                                <th class="px-4 py-2 text-left text-sm font-bold text-gray-600">Enrolled At</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (!empty($enrollments)): ?>
                                <?php foreach ($enrollments as $enrollment): ?>
                                    <tr>
                                        <td class="px-4 py-2 text-sm text-gray-700"><?php echo $enrollment['enrollment_id']; ?></td>
                                        <td class="px-4 py-2 text-sm text-gray-700"><?php echo htmlspecialchars($enrollment['student_name']); ?></td>
                                        <td class="px-4 py-2 text-sm text-gray-700"><?php echo htmlspecialchars($enrollment['subject_name']); ?></td>
                                        <td class="px-4 py-2 text-sm text-gray-700"><?php echo $enrollment['enrolled_at']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="px-4 py-4 text-center text-gray-500">No enrollments found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
