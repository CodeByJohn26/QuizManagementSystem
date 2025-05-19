<?php
session_start();
require '../includes/db_connection.php'; // Correct relative path

// Ensure the logged-in user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Fetch admin details
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, email, profile_image FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

// Generate a CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Update admin details
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token");
    }

    try {
        $username = htmlspecialchars(trim($_POST['username']));
        $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
        $password = !empty($_POST['password']) ? password_hash(trim($_POST['password']), PASSWORD_BCRYPT) : null;

        if (!$email) {
            throw new Exception("Invalid email address.");
        }

        // Handle image upload securely
        $profile_image = $admin['profile_image']; // Default to existing image
        if (!empty($_FILES['profile_image']['name'])) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $image_type = mime_content_type($_FILES['profile_image']['tmp_name']);
            if (!in_array($image_type, $allowed_types)) {
                throw new Exception("Invalid image type. Please upload a JPG, PNG, or GIF file.");
            }

            $image_temp = $_FILES['profile_image']['tmp_name'];
            $image_name = time() . "_" . basename($_FILES['profile_image']['name']);
            $image_dir = "../uploads/" . $image_name;
            move_uploaded_file($image_temp, $image_dir);
            $profile_image = $image_name; // Update image path
        }

        // Update user details securely
        $update_query = "UPDATE users SET username = ?, email = ?, profile_image = ?";
        $params = [$username, $email, $profile_image];
        if ($password) {
            $update_query .= ", password = ?";
            $params[] = $password;
        }
        $update_query .= " WHERE id = ?";
        $params[] = $user_id;

        $stmt = $conn->prepare($update_query);
        $stmt->execute($params);

        echo "<script>alert('Profile updated successfully!'); window.location.href = 'admin_dashboard.php';</script>";
    } catch (Exception $e) {
        echo "<script>alert('Error updating profile: " . $e->getMessage() . "'); window.history.back();</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard</title>
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

  <nav class="flex items-center justify-between p-4 gradient-header shadow-lg">
        <div class="text-2xl font-bold"><i class="fa fa-user" aria-hidden="true"></i> Admin Panel</div>
        <ul class="flex gap-6 text-white">
            <li><a href="admin_dashboard.php" class="text-yellow-300 font-semibold"><i class="fa fa-home" aria-hidden="true"></i> Dashboard</a></li>
            <li><a href="monitor_users.php" class="hover:text-yellow-300"><i class="fa fa-user" aria-hidden="true"></i> Users</a></li>
            <li><a href="assign_subject_admin.php" class="hover:text-yellow-300"><i class="fa fa-book" aria-hidden="true"></i> Subjects</a></li>
            <li><a href="view_feedback.php" class="hover:text-yellow-300"><i class="fa fa-commenting-o" aria-hidden="true"></i> Feedback</a></li>
            <li><a href="logout.php" class="hover:text-yellow-300"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a></li>
        </ul>
    </nav>

  <!-- Main Content -->
  <main class="max-w-6xl mx-auto p-6">
    
    <!-- Welcome Card -->
    <section class="bg-white p-6 rounded-xl shadow-md border-l-8 border-yellow-300 mb-6">
      <h1 class="text-xl font-bold text-blue-600">Welcome, Admin <?= htmlspecialchars($admin['username']); ?>!</h1>
      <p class="text-gray-600">Manage users, assign subjects, and review feedback here.</p>
    </section>

    <!-- Two-Column Layout -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      
      <!-- Edit Profile Card -->
      <div class="bg-white p-6 rounded-xl shadow-md border-t-4 border-blue-400">
        <h2 class="text-xl font-bold text-blue-700 mb-4">Edit Profile</h2>
        <form action="admin_dashboard.php" method="POST" enctype="multipart/form-data" class="space-y-4">
          <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
          <div class="flex gap-4 items-start">
            <img src="../uploads/<?= htmlspecialchars($admin['profile_image'] ?? 'default.png'); ?>" class="w-24 h-24 rounded-full shadow-lg border">
            <div class="flex-1 space-y-3">
              <div>
                <label class="block text-sm text-gray-700">Username</label>
                <input type="text" name="username" value="<?= htmlspecialchars($admin['username']); ?>" class="w-full border border-gray-300 rounded p-2" required>
              </div>
              <div>
                <label class="block text-sm text-gray-700">Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($admin['email']); ?>" class="w-full border border-gray-300 rounded p-2" required>
              </div>
              <div>
                <label class="block text-sm text-gray-700">New Password</label>
                <input type="password" name="password" placeholder="Optional" class="w-full border border-gray-300 rounded p-2">
              </div>
              <div>
                <label class="block text-sm text-gray-700">Profile Image</label>
                <input type="file" name="profile_image" class="w-full border border-gray-300 rounded p-2">
              </div>
            </div>
          </div>
          <button type="submit" name="update_profile" class="w-full bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 rounded">
            Update Profile
          </button>
        </form>
      </div>

      <!-- Quick Actions Card -->
      <div class="bg-white p-6 rounded-xl shadow-md border-t-4 border-yellow-400">
        <h2 class="text-xl font-bold text-yellow-600 mb-4">Quick Actions</h2>
        <div class="space-y-3">
          <a href="monitor_users.php" class="block w-full text-center bg-yellow-400 hover:bg-yellow-300 text-white font-bold py-2 rounded"><i class="fa fa-user" aria-hidden="true"></i> Monitor Users</a>
          <a href="assign_subject_admin.php" class="block w-full text-center bg-green-500 hover:bg-green-400 text-white font-bold py-2 rounded"><i class="fa fa-book" aria-hidden="true"></i> Assign Subjects</a>
          <a href="view_feedback.php" class="block w-full text-center bg-blue-400 hover:bg-blue-300 text-white font-bold py-2 rounded"><i class="fa fa-commenting-o" aria-hidden="true"></i>  View Feedback</a>
          <a href="logout.php" class="block w-full text-center bg-red-500 hover:bg-red-400 text-white font-bold py-2 rounded"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
        </div>
      </div>
    </div>
  </main>
</body>
</html>
