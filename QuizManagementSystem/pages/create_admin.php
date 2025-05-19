<?php
session_start();
require_once '../includes/db_connection.php';

// Ensure the user is logged in as an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);

    // Check if an admin already exists
    $admin_check_stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE role = 'admin'");
    $admin_check_stmt->execute();
    $admin_count = $admin_check_stmt->fetchColumn();

    if ($admin_count > 0) {
        echo "<script>alert('An admin account already exists!'); window.history.back();</script>";
        exit;
    }

    // Insert the admin account
    try {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, profile_image, role) VALUES (?, ?, ?, 'default-profile.png', 'admin')");
        $stmt->execute([$username, $email, $password]);
        echo "<script>alert('Admin account created successfully!'); window.location.href = 'admin_dashboard.php';</script>";
    } catch (Exception $e) {
        echo "<script>alert('Error creating admin account: " . $e->getMessage() . "'); window.history.back();</script>";
    }
}
?>