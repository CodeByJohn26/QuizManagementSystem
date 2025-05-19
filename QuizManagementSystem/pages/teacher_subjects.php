<?php
session_start();
require_once '../includes/db_connection.php';

// Ensure a teacher ID is provided.
if (!isset($_GET['teacher_id'])) {
    echo "<p class='text-gray-500'>No teacher selected.</p>";
    exit;
}

$teacher_id = $_GET['teacher_id'];

// Fetch subjects taught by the teacher.
$stmt = $conn->prepare("SELECT id, name, description FROM subjects WHERE teacher_id = ?");
$stmt->execute([$teacher_id]);
$subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Teacher's Subjects</title>
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <button class="btn btn-secondary" onclick="window.location.href='enroll_subject.php';">Back to Teachers</button>
    <h2 class="text-3xl font-bold text-gray-700 mb-4 text-center">Subjects Offered</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <?php
      if (empty($subjects)) {
        echo "<p class='text-gray-500'>No subjects found for this teacher.</p>";
      } else {
        foreach ($subjects as $subject) {
          ?>
          <div class="bg-white shadow p-4 rounded card">
            <h3 class="font-bold text-blue-700"><?php echo htmlspecialchars($subject['name']); ?></h3>
            <p class="text-gray-600"><?php echo htmlspecialchars($subject['description']); ?></p>
            <a href="subject_enrollment.php?subject_id=<?php echo $subject['id']; ?>" class="btn btn-primary mt-3 w-100">Enroll</a>
          </div>
          <?php
        }
      }
      ?>
    </div>
  </div>
</body>
</html>