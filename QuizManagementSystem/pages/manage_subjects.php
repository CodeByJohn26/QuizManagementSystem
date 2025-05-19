<?php
session_start();
require '../includes/db_connection.php'; // Include the database connection

// Ensure the logged-in user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

try {
    // Initialize variables
    $subjects = [];
    $teachers = [];

    // Handle POST Requests for Create, Update, and Delete Operations
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['add_subject'])) {
            // Add New Subject
            $name = htmlspecialchars(trim($_POST['name']));
            $code = htmlspecialchars(trim($_POST['code']));
            $teacher_id = intval($_POST['teacher_id']);
            $color = htmlspecialchars(trim($_POST['color'])); // Capture color from input

            if (!empty($name) && !empty($code) && $teacher_id && !empty($color)) {
                // Check for duplicate code
                $code_check_query = $conn->prepare("SELECT id FROM subjects WHERE code = ?");
                $code_check_query->execute([$code]);
                if ($code_check_query->rowCount() > 0) {
                    throw new Exception("This subject code already exists. Please choose a unique code.");
                }

                // Insert subject into database
                $insert_query = $conn->prepare("INSERT INTO subjects (name, code, teacher_id, color) VALUES (?, ?, ?, ?)");
                $insert_query->execute([$name, $code, $teacher_id, $color]);
                echo "<script>alert('Subject added successfully!'); window.location.href = 'assign_subject_admin.php';</script>";
            } else {
                throw new Exception("All fields are required to add a subject.");
            }
        } elseif (isset($_POST['update_subject'])) {
            // Update Existing Subject
            $subject_id = intval($_POST['subject_id']);
            $name = htmlspecialchars(trim($_POST['name']));
            $code = htmlspecialchars(trim($_POST['code']));
            $teacher_id = intval($_POST['teacher_id']);
            $color = htmlspecialchars(trim($_POST['color'])); // Capture color from input

            if ($subject_id && !empty($name) && !empty($code) && $teacher_id && !empty($color)) {
                // Check for duplicate code excluding current subject
                $code_check_query = $conn->prepare("SELECT id FROM subjects WHERE code = ? AND id != ?");
                $code_check_query->execute([$code, $subject_id]);
                if ($code_check_query->rowCount() > 0) {
                    throw new Exception("This subject code already exists for another subject. Please choose a unique code.");
                }

                // Update subject in database
                $update_query = $conn->prepare("UPDATE subjects SET name = ?, code = ?, teacher_id = ?, color = ? WHERE id = ?");
                $update_query->execute([$name, $code, $teacher_id, $color, $subject_id]);
                echo "<script>alert('Subject updated successfully!'); window.location.href = 'assign_subject_admin.php';</script>";
            } else {
                throw new Exception("All fields are required to update a subject.");
            }
        } elseif (isset($_POST['delete_subject'])) {
            // Delete Subject
            $subject_id = intval($_POST['subject_id']);

            if ($subject_id) {
                // Delete subject from database
                $delete_query = $conn->prepare("DELETE FROM subjects WHERE id = ?");
                $delete_query->execute([$subject_id]);
                echo "<script>alert('Subject deleted successfully!'); window.location.href = 'assign_subject_admin.php';</script>";
            } else {
                throw new Exception("Invalid subject ID.");
            }
        }
    }

    // Fetch subjects for display
    $subject_query = $conn->prepare("SELECT * FROM subjects");
    $subject_query->execute();
    $subjects = $subject_query->fetchAll(PDO::FETCH_ASSOC);

    // Fetch all professors (teachers)
    $teacher_query = $conn->prepare("SELECT id, username FROM users WHERE role = 'teacher'");
    $teacher_query->execute();
    $teachers = $teacher_query->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    echo "<script>alert('Error: " . $e->getMessage() . "'); window.history.back();</script>";
}
?>