<?php
require '../includes/db_connection.php';
session_start();

// Ensure the user is logged in and is a teacher
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit;
}

// Assign teacher ID from session
$teacher_id = $_SESSION['user_id'];

// Fetch teacher's profile information
$profile_stmt = $conn->prepare("SELECT profile_image, username, email FROM users WHERE id = ? AND role = 'teacher'");
$profile_stmt->execute([$teacher_id]);
$profile = $profile_stmt->fetch(PDO::FETCH_ASSOC);

// Ensure profile data is valid
if (!$profile) {
    echo "<script>alert('Teacher profile not found.'); window.location.href = 'login.php';</script>";
    exit;
}

// Update teacher's profile
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    try {
        $username = htmlspecialchars(trim($_POST['username']));
        $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
        $password = !empty($_POST['password']) ? password_hash(trim($_POST['password']), PASSWORD_BCRYPT) : null;

        if (!$email) {
            throw new Exception("Invalid email address.");
        }

        // Handle profile image upload
        $profile_image = $profile['profile_image']; // Use existing image as default
        if (!empty($_FILES['profile_image']['name'])) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $image_type = mime_content_type($_FILES['profile_image']['tmp_name']);
            if (!in_array($image_type, $allowed_types)) {
                throw new Exception("Invalid image type. Please upload JPG, PNG, or GIF.");
            }
            $image_temp = $_FILES['profile_image']['tmp_name'];
            $image_name = time() . "_" . basename($_FILES['profile_image']['name']);
            $image_dir = "../uploads/" . $image_name;
            move_uploaded_file($image_temp, $image_dir);
            $profile_image = $image_name;
        }

        // Update query
        $update_query = "UPDATE users SET username = ?, email = ?, profile_image = ?";
        $params = [$username, $email, $profile_image];
        if ($password) {
            $update_query .= ", password = ?";
            $params[] = $password;
        }
        $update_query .= " WHERE id = ?";
        $params[] = $teacher_id;

        $stmt = $conn->prepare($update_query);
        $stmt->execute($params);

        echo "<script>alert('Profile updated successfully!'); window.location.href = 'edit_teacher_profile.php';</script>";
    } catch (Exception $e) {
        echo "<script>alert('Error updating profile: " . $e->getMessage() . "'); window.history.back();</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
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
        .profile-image {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="flex items-center justify-between p-4 gradient-header shadow-lg">
        <div class="text-2xl font-bold"><i class="fa fa-user" aria-hidden="true"></i> Teacher Panel</div>
        <ul class="flex gap-6 text-white">
            <li><a href="teacher_dashboard.php" class="hover:text-yellow-300"><i class="fa fa-home" aria-hidden="true"></i> Dashboard</a></li>
            <li><a href="create_quiz.php" class="hover:text-yellow-300"><i class="fa fa-plus" aria-hidden="true"></i> Create Quiz</a></li>
            <li><a href="view_analytics.php" class="hover:text-yellow-300"><i class="fa fa-line-chart" aria-hidden="true"></i> Analytics</a></li>
            <li><a href="edit_teacher_profile.php" class="text-yellow-300 font-semibold"><i class="fa fa-user" aria-hidden="true"></i> Profile</a></li>
            <li><a href="logout.php" class="hover:text-yellow-300"><i class="fa fa-sign-out" aria-hidden="true"></i>Logout</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main class="p-6 max-w-5xl mx-auto">
        <section class="mb-6 bg-white rounded-xl shadow-md p-6 border-l-8 border-blue-400">
            <h1 class="text-2xl font-bold text-blue-600 text-center"><i class="fa fa-user" aria-hidden="true"></i> Edit Profile</h1>
        </section>

        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex justify-center">
                <img src="../uploads/<?= htmlspecialchars($profile['profile_image'] ?? 'default-profile.png'); ?>"  
                     alt="Profile Image" class="profile-image shadow-lg">
            </div>
            <form action="edit_teacher_profile.php" method="POST" enctype="multipart/form-data" class="space-y-4">

                <div>
                    <label for="username" class="block text-sm font-bold text-gray-700">Username:</label>
                    <input type="text" name="username" id="username" value="<?= htmlspecialchars($profile['username']); ?>" required
                        class="mt-1 w-full border border-gray-300 rounded-md p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label for="email" class="block text-sm font-bold text-gray-700">Email:</label>
                    <input type="email" name="email" id="email" value="<?= htmlspecialchars($profile['email']); ?>" required
                        class="mt-1 w-full border border-gray-300 rounded-md p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label for="password" class="block text-sm font-bold text-gray-700">Password (optional):</label>
                    <input type="password" name="password" id="password" placeholder="Enter new password"
                        class="mt-1 w-full border border-gray-300 rounded-md p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label for="profile_image" class="block text-sm font-bold text-gray-700">Upload Profile Image:</label>
                    <input type="file" name="profile_image" id="profile_image" accept="image/*"
                        class="mt-1 w-full text-sm text-gray-700">
                </div>

                <button 
                    type="submit" 
                    name="update_profile" 
                    class="w-full mt-4 bg-gradient-to-r from-yellow-400 to-blue-500 hover:from-yellow-500 hover:to-blue-600 text-white font-semibold py-2 px-4 rounded-xl shadow-md transition duration-200">
                    Update Profile
                </button>

                <a 
                    href="teacher_dashboard.php" 
                    class="block w-full mt-2 text-center bg-gray-300 hover:bg-gray-400 text-black font-medium py-2 px-4 rounded-xl shadow-md transition duration-200">
                    Go Back to Dashboard
                </a>
            </form>
        </div>
    </main>

</body>
</html>
