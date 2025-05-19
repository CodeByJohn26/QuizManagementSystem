<?php
require 'manage_subjects.php';

if (!isset($subjects)) {
    $subjects = [];
}
if (!isset($teachers)) {
    $teachers = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Manage Subjects</title>
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

  <!-- Navigation Bar -->
  <nav class="flex items-center justify-between p-4 gradient-header shadow-lg">
    <div class="text-2xl font-bold"><i class="fa fa-user" aria-hidden="true"></i> Admin Panel</div>
    <ul class="flex gap-6 text-white">
        <li><a href="admin_dashboard.php" class="hover:text-yellow-300"><i class="fa fa-home" aria-hidden="true"></i> Dashboard</a></li>
        <li><a href="monitor_users.php" class="hover:text-yellow-300"><i class="fa fa-user" aria-hidden="true"></i> Users</a></li>
        <li><a href="assign_subject_admin.php" class="text-yellow-300 font-semibold"><i class="fa fa-book" aria-hidden="true"></i> Subjects</a></li>
        <li><a href="view_feedback.php" class="hover:text-yellow-300"><i class="fa fa-commenting-o" aria-hidden="true"></i> Feedback</a></li>
        <li><a href="logout.php" class="hover:text-yellow-300"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a></li>
    </ul>
  </nav>

  <!-- Main Content -->
  <main class="max-w-7xl mx-auto p-6">

    <h1 class="text-3xl font-bold text-blue-600 text-center mb-6">Manage Subjects</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

      <!-- Add Subject Form -->
      <div class="col-span-1 bg-white p-6 rounded-xl shadow-md border-t-4 border-green-400">
        <h2 class="text-2xl font-bold text-green-600 mb-4">Add New Subject</h2>
        <form action="assign_subject_admin.php" method="POST" class="space-y-4">
          <div>
            <label class="block text-sm text-gray-700">Subject Name:</label>
            <input type="text" name="name" class="w-full border border-gray-300 rounded p-2" required>
          </div>
          <div>
            <label class="block text-sm text-gray-700">Subject Code:</label>
            <input type="text" name="code" class="w-full border border-gray-300 rounded p-2" required>
          </div>
          <div>
            <label class="block text-sm text-gray-700">Assign Professor:</label>
            <select name="teacher_id" class="w-full border border-gray-300 rounded p-2" required>
              <option value="">-- Select Professor --</option>
              <?php foreach ($teachers as $teacher): ?>
                <option value="<?= $teacher['id']; ?>"><?= htmlspecialchars($teacher['username']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label class="block text-sm text-gray-700">Subject Color:</label>
            <select name="color" class="w-full border border-gray-300 rounded p-2" required>
              <option value="#ef4444" style="background-color: #ef4444;">Red</option>
              <option value="#3b82f6" style="background-color: #3b82f6;">Blue</option>
              <option value="#22c55e" style="background-color: #22c55e;">Green</option>
              <option value="#eab308" style="background-color: #eab308;">Yellow</option>
              <option value="#8b5cf6" style="background-color: #8b5cf6;">Purple</option>
              <option value="#ec4899" style="background-color: #ec4899;">Pink</option>
              <option value="#6366f1" style="background-color: #6366f1;">Indigo</option>
              <option value="#14b8a6" style="background-color: #14b8a6;">Teal</option>
              <option value="#f97316" style="background-color: #f97316;">Orange</option>
              <option value="#6b7280" style="background-color: #6b7280;">Gray</option>
            </select>
          </div>
          <button type="submit" name="add_subject" class="w-full bg-green-500 hover:bg-green-400 text-white font-bold py-2 rounded">
            Add Subject
          </button>
        </form>
      </div>

      <!-- Existing Subjects Table -->
      <div class="col-span-2 bg-white p-6 rounded-xl shadow-md border-t-4 border-yellow-400 overflow-auto">
        <h2 class="text-2xl font-bold text-blue-600 mb-4"><i class="fa fa-book" aria-hidden="true"></i> Existing Subjects</h2>
        <table class="min-w-full text-sm text-left">
          <thead class="bg-blue-600 text-white">
            <tr>
              <th class="p-2">ID</th>
              <th class="p-2">Name</th>
              <th class="p-2">Code</th>
              <th class="p-2">Color</th>
              <th class="p-2">Professor</th>
              <th class="p-2">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($subjects)): ?>
              <?php foreach ($subjects as $subject): ?>
                <tr class="border-b">
                  <form action="assign_subject_admin.php" method="POST">
                    <td class="p-2"><?= $subject['id']; ?></td>
                    <td class="p-2">
                      <input type="text" name="name" value="<?= htmlspecialchars($subject['name']); ?>" class="w-full border border-gray-300 rounded p-1">
                    </td>
                    <td class="p-2">
                      <input type="text" name="code" value="<?= htmlspecialchars($subject['code']); ?>" class="w-full border border-gray-300 rounded p-1">
                    </td>
                    <td class="p-2">
                      <input type="color" name="color" value="<?= htmlspecialchars($subject['color']); ?>" class="w-full border border-gray-300 rounded">
                    </td>
                    <td class="p-2">
                      <select name="teacher_id" class="w-full border border-gray-300 rounded p-1">
                        <option value="" disabled>-- Select Professor --</option>
                        <?php foreach ($teachers as $teacher): ?>
                          <option value="<?= $teacher['id']; ?>" <?= $teacher['id'] == $subject['teacher_id'] ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($teacher['username']); ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </td>
                    <td>
                        <input type="hidden" name="subject_id" value="<?php echo $subject['id']; ?>">
                        <div class="flex gap-2">
                            <button type="submit" name="update_subject" class="bg-blue-500 hover:bg-blue-400 text-white font-semibold py-1 px-3 rounded-md shadow transition">Update</button>
                            <button type="submit" name="delete_subject" onclick="return confirm('Are you sure you want to delete this subject?');"
                            class="bg-red-500 hover:bg-red-400 text-white font-semibold py-1 px-3 rounded-md shadow transition">Delete</button>
                        </div>
                    </td>
                  </form>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="6" class="text-center py-4">No subjects found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

    </div>
  </main>

</body>
</html>
