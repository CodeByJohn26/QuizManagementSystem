<?php
session_start();
require '../includes/db_connection.php'; // Include database connection

// Ensure the logged-in user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Handle delete user request
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']); // Sanitize the ID

    try {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$delete_id]);
        echo "<script>alert('User deleted successfully!'); window.location.href = 'monitor_users.php';</script>";
    } catch (Exception $e) {
        echo "<script>alert('Error deleting user: " . $e->getMessage() . "'); window.location.href = 'monitor_users.php';</script>";
    }
}

// Handle add teacher request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_teacher'])) {
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    if (!empty($username) && !empty($email) && !empty($password)) {
        try {
            // Hash the password for security
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Insert the new teacher into the database
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role, created_at) VALUES (?, ?, ?, 'teacher', NOW())");
            $stmt->execute([$username, $email, $hashed_password]);

            echo "<script>alert('Teacher account created successfully!'); window.location.href = 'monitor_users.php';</script>";
        } catch (Exception $e) {
            echo "<script>alert('Error creating teacher account: " . $e->getMessage() . "'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('All fields are required to create a teacher account.'); window.history.back();</script>";
    }
}

// Fetch teachers
$teacher_query = $conn->prepare("SELECT id, username, email, created_at FROM users WHERE role = 'teacher'");
$teacher_query->execute();
$teachers = $teacher_query->fetchAll(PDO::FETCH_ASSOC);

// Fetch students
$student_query = $conn->prepare("SELECT id, username, email, created_at FROM users WHERE role = 'student'");
$student_query->execute();
$students = $student_query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Monitor Users</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://kit.fontawesome.com/0aaabd993c.js" crossorigin="anonymous"></script>
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f3f4f6;
    }
    .gradient-header {
      background: linear-gradient(to right, #facc15, #3b82f6);
      color: white;
    }
  </style>
</head>
<body>

  <!-- Header -->
  <nav class="flex items-center justify-between p-4 gradient-header shadow-lg">
    <div class="text-2xl font-bold"><i class="fa fa-user" aria-hidden="true"></i> Admin Panel</div>
    <ul class="flex gap-6 text-white">
      <li><a href="admin_dashboard.php" class="hover:text-yellow-300"><i class="fa fa-home" aria-hidden="true"></i> Dashboard</a></li>
      <li><a href="monitor_users.php" class="text-white text-yellow-300 font-semibold"><i class="fa fa-user" aria-hidden="true"></i> Users</a></li>
      <li><a href="assign_subject_admin.php" class="hover:text-yellow-300"><i class="fa fa-book" aria-hidden="true"></i> Subjects</a></li>
      <li><a href="view_feedback.php" class="hover:text-yellow-300"><i class="fa fa-commenting-o" aria-hidden="true"></i> Feedback</a></li>
      <li><a href="logout.php" class="hover:text-yellow-300"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a></li>
    </ul>
  </nav>

  <!-- Content -->
  <main class="max-w-7xl mx-auto p-6">
    <h1 class="text-center text-3xl font-bold text-blue-600 mb-6">Monitor Teachers and Students</h1>

    <!-- Flex Layout -->
    <div class="flex flex-col md:flex-row gap-6">

      <!-- Add Teacher Form -->
      <div class="add-user bg-white shadow-md rounded-xl p-6 border-t-4 border-green-400 w-full md:w-1/3">
        <h2 class="text-xl font-bold text-green-600 mb-4">Add New Teacher</h2>
        <form action="monitor_users.php" method="POST" class="space-y-4">
          <div>
            <label for="username" class="block text-gray-700">Username</label>
            <input type="text" name="username" id="username" required class="w-full border border-gray-300 rounded p-2">
          </div>
          <div>
            <label for="email" class="block text-gray-700">Email</label>
            <input type="email" name="email" id="email" required class="w-full border border-gray-300 rounded p-2">
          </div>
          <div>
            <label for="password" class="block text-gray-700">Password</label>
            <input type="password" name="password" id="password" required class="w-full border border-gray-300 rounded p-2">
          </div>
             <div class="flex items-center mt-2 mb-4">
            <input type="checkbox" id="showPassword" class="mr-2 cursor-pointer">
            <label for="showPassword" class="text-xs text-[#4a4a4a] cursor-pointer">Show Password</label>
          </div>

          <button type="submit" name="add_teacher" class="w-full bg-green-500 hover:bg-green-400 text-white font-bold py-2 rounded">
            Create Teacher Account
          </button>
        </form>
      </div>

      <!-- Teachers Table -->
      <div class="users-table bg-white shadow-md rounded-xl p-6 border-t-4 border-blue-400 w-full md:w-2/3 overflow-auto">
        <h2 class="text-xl font-bold text-green-600 mb-4"><i class="fa fa-user" aria-hidden="true"></i> Teachers</h2>
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm text-left border border-gray-200">
            <thead class="bg-blue-100 text-blue-700 font-bold">
              <tr>
                <th class="px-4 py-2 border">ID</th>
                <th class="px-4 py-2 border">Username</th>
                <th class="px-4 py-2 border">Email</th>
                <th class="px-4 py-2 border">Created At</th>
                <th class="px-4 py-2 border">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($teachers as $teacher): ?>
              <tr class="hover:bg-gray-100">
                <td class="px-4 py-2 border"><?php echo $teacher['id']; ?></td>
                <td class="px-4 py-2 border"><?php echo htmlspecialchars($teacher['username']); ?></td>
                <td class="px-4 py-2 border"><?php echo htmlspecialchars($teacher['email']); ?></td>
                <td class="px-4 py-2 border"><?php echo $teacher['created_at']; ?></td>
                <td class="px-4 py-2 border">
                  <a href="monitor_users.php?delete_id=<?php echo $teacher['id']; ?>"
                     class="bg-red-500 hover:bg-red-400 text-white px-3 py-1 rounded text-sm"
                     onclick="return confirm('Are you sure you want to delete this user?');">
                     Delete
                  </a>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Students Table -->
    <div class="bg-white shadow-md rounded-xl p-6 mt-6 border-t-4 border-yellow-400">
      <h2 class="text-xl font-bold text-blue-600 mb-4"><i class="fa fa-user" aria-hidden="true"></i> Students</h2>
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left border border-gray-200">
          <thead class="bg-yellow-100 text-yellow-700 font-bold">
            <tr>
              <th class="px-4 py-2 border">ID</th>
              <th class="px-4 py-2 border">Username</th>
              <th class="px-4 py-2 border">Email</th>
              <th class="px-4 py-2 border">Created At</th>
              <th class="px-4 py-2 border">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($students as $student): ?>
            <tr class="hover:bg-gray-100">
              <td class="px-4 py-2 border"><?php echo $student['id']; ?></td>
              <td class="px-4 py-2 border"><?php echo htmlspecialchars($student['username']); ?></td>
              <td class="px-4 py-2 border"><?php echo htmlspecialchars($student['email']); ?></td>
              <td class="px-4 py-2 border"><?php echo $student['created_at']; ?></td>
              <td class="px-4 py-2 border">
                <a href="monitor_users.php?delete_id=<?php echo $student['id']; ?>"
                   class="bg-red-500 hover:bg-red-400 text-white px-3 py-1 rounded text-sm"
                   onclick="return confirm('Are you sure you want to delete this user?');">
                   Delete
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>
</body>
<script>
    const showPasswordCheckbox = document.getElementById('showPassword');
    const passwordInput = document.getElementById('password');

    showPasswordCheckbox.addEventListener('change', function () {
      passwordInput.type = this.checked ? 'text' : 'password';
    });
  </script>
</html>