<?php
session_start();
require_once '../includes/db_connection.php';

// Ensure a subject ID is provided.
if (!isset($_GET['subject_id'])) {
    echo "<p class='text-gray-500'>No subject selected.</p>";
    exit;
}

$subject_id = $_GET['subject_id'];

// Handle the enrollment form submission.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $passcode = htmlspecialchars(trim($_POST['passcode']));

    // Validate the passcode.
    $stmt = $conn->prepare("SELECT id FROM subjects WHERE id = ? AND passcode = ?");
    $stmt->execute([$subject_id, $passcode]);
    $subject = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$subject) {
        echo "<script>alert('Invalid passcode. Please try again.');</script>";
    } else {
        // Enroll the student.
        $enroll_stmt = $conn->prepare("INSERT INTO enrollments (user_id, subject_id, enrolled_at) VALUES (?, ?, NOW())");
        try {
            $enroll_stmt->execute([$user_id, $subject_id]);
            echo "<script>alert('Successfully enrolled in the subject!');</script>";
            echo "<script>window.location.href = 'enroll_subject.php';</script>";
        } catch (Exception $e) {
            echo "<script>alert('Enrollment failed. Please try again later.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Subject Enrollment</title>
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <!-- Back to Dashboard Button -->
    <button class="btn btn-secondary mb-3" onclick="window.location.href='student_dashboard.php';">Back to Dashboard</button>

    <!-- Back to Subjects Button -->
    <?php if (isset($_GET['teacher_id'])): ?>
      <button class="btn btn-secondary mb-3" onclick="window.location.href='teacher_subjects.php?teacher_id=<?php echo htmlspecialchars($_GET['teacher_id']); ?>';">Back to Subjects</button>
    <?php endif; ?>

    <h2 class="text-3xl font-bold text-gray-700 mb-4 text-center">Enroll in Subject</h2>
    <form action="" method="POST">
      <div class="mb-3">
        <label for="passcode" class="form-label text-gray-700">Enter Passcode:</label>
        <input type="text" name="passcode" id="passcode" class="form-control border border-gray-300 rounded" placeholder="Enter subject passcode" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Enroll</button>
    </form>
  </div>
</body>
</html>