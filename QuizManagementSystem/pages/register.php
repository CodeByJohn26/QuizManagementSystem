<?php
require '../includes/db_connection.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input data to prevent XSS and SQL injection
    $username = htmlspecialchars(trim($_POST['username']));
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);
    $role = htmlspecialchars(trim($_POST['role'])); // Valid roles: 'admin', 'teacher', or 'student'
    $profile_image = $_FILES['profile_image'];

    // Validate mandatory inputs
    if (empty($username) || empty($email) || empty($_POST['password']) || empty($role)) {
        echo "<script>alert('All fields are required!'); window.history.back();</script>";
        exit;
    }

    // Validate role
    $valid_roles = ['admin', 'teacher', 'student'];
    if (!in_array($role, $valid_roles)) {
        echo "<script>alert('Invalid role selected.'); window.history.back();</script>";
        exit;
    }

    // Validate email format
    if (!$email) {
        echo "<script>alert('Invalid email address.'); window.history.back();</script>";
        exit;
    }

    // Validate the uploaded profile image
    $allowed_types = ['image/jpeg', 'image/png'];
    $upload_dir = '../uploads/profile_images/';
    $image_path = '';

    if (!empty($profile_image['name'])) {
        if (in_array($profile_image['type'], $allowed_types) && $profile_image['size'] <= 2000000) {
            $image_path = $upload_dir . time() . '_' . basename($profile_image['name']); // Unique image name
            if (!move_uploaded_file($profile_image['tmp_name'], $image_path)) {
                echo "<script>alert('Image upload failed. Please try again!'); window.history.back();</script>";
                exit;
            }
        } else {
            echo "<script>alert('Invalid image file. Only JPG and PNG formats are accepted and size must be below 2MB.'); window.history.back();</script>";
            exit;
        }
    } else {
        // Set a default profile image if none is uploaded
        $image_path = $upload_dir . 'default-profile.png';
    }

    // Check if admin already exists
    if ($role === 'admin') {
        $admin_check_stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE role = 'admin'");
        $admin_check_stmt->execute();
        $admin_count = $admin_check_stmt->fetchColumn();

        if ($admin_count > 0) {
            echo "<script>alert('An admin account already exists!'); window.history.back();</script>";
            exit;
        }
    }

    // Insert user details into the database
    try {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role, profile_image, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$username, $email, $password, $role, $image_path]);
        echo "<script>alert('Registration successful!'); window.location.href = 'login.php';</script>";
    } catch (Exception $e) {
        echo "<script>alert('Error registering user: " . $e->getMessage() . "'); window.history.back();</script>";
    }
}
?>