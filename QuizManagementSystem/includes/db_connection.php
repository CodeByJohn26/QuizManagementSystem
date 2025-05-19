<?php
$servername = "localhost";  // Your database server
$username = "root";         // Your database username
$password = "";             // Your database password
$dbname = "quiz_management_system";    // Your database name

// Create PDO connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}
?>
