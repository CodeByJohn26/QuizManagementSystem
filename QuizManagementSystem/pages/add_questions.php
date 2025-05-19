<?php
require 'backend_questions.php';

$selected_quiz_id = $_GET['quiz_id'] ?? $_POST['quiz_id'] ?? null;
$editing_question = isset($_GET['edit']) ? $_GET['edit'] : null;

// Fix for undefined $is_completed
$is_completed = false;
if ($selected_quiz_id && isset($quizzes)) {
    foreach ($quizzes as $quiz) {
        if ($quiz['id'] == $selected_quiz_id) {
            $is_completed = $quiz['quiz_completed'];
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Add or Edit Questions</title>
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
    <li><a href="edit_teacher_profile.php" class="hover:text-yellow-300"><i class="fa fa-user" aria-hidden="true"></i> Profile</a></li>
    <li><a href="logout.php" class="hover:text-yellow-300"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a></li>
  </ul>
</nav>

<!-- Main Content -->
<main class="flex-1 p-10">
  <div class="max-w-4xl mx-auto bg-white p-8 rounded-2xl shadow-lg border-t-4 border-yellow-400">
    <h1 class="text-3xl font-bold text-yellow-600 text-center mb-6">Add/Edit Questions to Quiz</h1>

    <!-- Quiz Selector -->
    <form method="GET" class="mb-6">
      <label class="block font-semibold mb-2">Select Quiz:</label>
      <select name="quiz_id" class="w-full p-3 border border-gray-300 rounded" onchange="this.form.submit()" required>
        <option value="">-- Select a Quiz --</option>
        <?php foreach ($quizzes as $quiz): ?>
          <option value="<?= $quiz['id']; ?>" <?= $selected_quiz_id == $quiz['id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($quiz['title']) ?> <?= $quiz['quiz_completed'] ? '(Completed)' : '' ?>
          </option>
        <?php endforeach; ?>
      </select>
    </form>

    <?php if ($selected_quiz_id): ?>
      <!-- Add/Edit Form -->
      <form action="add_questions.php?quiz_id=<?= $selected_quiz_id ?>" method="POST">
        <input type="hidden" name="quiz_id" value="<?= $selected_quiz_id ?>">
        <input type="hidden" name="question_id" id="edit_question_id">

        <div class="mb-4">
          <label class="block font-semibold mb-1">Question Text:</label>
          <textarea name="question_text" class="w-full p-3 border rounded" rows="2" required <?= $is_completed ? 'disabled' : '' ?>></textarea>
        </div>

        <div class="mb-4">
          <label class="block font-semibold mb-1">Question Type:</label>
          <select name="question_type" id="question_type" class="w-full p-3 border rounded" onchange="toggleFields()" required <?= $is_completed ? 'disabled' : '' ?>>
            <option value="">Select Type</option>
            <option value="multiple_choice">Multiple Choice & True/False </option>
          </select>
        </div>

        <div id="dynamic_fields"></div>

        <?php if (!$is_completed): ?>
          <button type="submit" name="<?= $editing_question ? 'update_question' : 'add_question' ?>" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-semibold py-2 px-4 rounded-md mb-2"><?= $editing_question ? 'Update Question' : 'Add Question' ?></button>
          <button type="submit" name="end_quiz" onclick="return confirm('Are you sure you want to finalize this quiz?')" class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-md">End Quiz</button>
        <?php else: ?>
          <div class="text-center text-blue-700 font-semibold mt-4">This quiz is completed. You can no longer add or edit questions.</div>
        <?php endif; ?>
      </form>

      <!-- Questions List -->
      <div class="mt-8">
        <h2 class="text-xl font-bold mb-4 text-yellow-600">Previously Added Questions:</h2>
        <ul class="space-y-4">
          <?php foreach ($questions as $index => $question): ?>
            <li class="bg-gray-50 p-4 rounded-xl shadow">
              <p class="font-semibold"><?= ($index + 1) . ". " . htmlspecialchars($question['question_text']) ?></p>

              <?php if ($question['question_type'] === 'multiple_choice'): ?>
                <?php
                  $choices = !empty($question['choices']) ? json_decode($question['choices'], true) : [];
                  $correctAnswer = $question['correct_answer'];
                ?>
                <p class="mt-2">Choices:</p>
                <ul class="ml-4 list-disc">
                  <?php foreach ($choices as $i => $choice): ?>
                    <li class="<?= ($correctAnswer === chr(97 + $i)) ? 'text-green-600 font-semibold' : '' ?>">
                      <?= strtoupper(chr(97 + $i)) ?>. <?= htmlspecialchars($choice) ?>
                    </li>
                  <?php endforeach; ?>
                </ul>
                <p class="mt-2">Answer: <span class="font-bold"><?= htmlspecialchars($correctAnswer) ?></span></p>

              <?php elseif ($question['question_type'] === 'true_false'): ?>
                <p class="mt-2">Answer: <span class="font-bold"><?= htmlspecialchars($question['correct_answer']) ?></span></p>

              <?php elseif ($question['question_type'] === 'identification'): ?>
                <p class="mt-2">Answer: <span class="font-bold"><?= htmlspecialchars($question['correct_answer']) ?></span></p>
              <?php endif; ?>

              <?php if (!$is_completed): ?>
                <form action="backend_questions.php" method="POST" class="mt-3 inline-block">
                  <input type="hidden" name="question_id" value="<?= $question['id']; ?>">
                  <button type="submit" name="delete_question" onclick="return confirm('Delete this question?')" class="text-sm bg-red-500 text-white px-4 py-1 rounded hover:bg-red-600">Delete</button>
                </form>
              <?php endif; ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

    <?php else: ?>
      <div class="bg-yellow-200 text-yellow-800 font-semibold p-4 rounded mt-4 text-center">
        Please select a quiz to manage questions.
      </div>
    <?php endif; ?>
  </div>
</main>

<script>
 function toggleFields() {
  const questionType = document.getElementById("question_type").value;
  const dynamicFields = document.getElementById("dynamic_fields");
  dynamicFields.innerHTML = "";

  if (questionType === "multiple_choice") {
    dynamicFields.innerHTML = `
      <div class="mb-4">
        <label class="block font-semibold">Choices:</label>
        <div id="choices_container"></div>
        <button type="button" onclick="addChoice()" class="bg-blue-500 text-white px-2 py-1 rounded">Add Choice</button>
      </div>
      <div class="mb-4">
        <label class="block font-semibold">Correct Answer:</label>
        <select name="correct_choice" id="correct_choice" class="w-full p-2 border rounded" required>
          <option value="">Select</option>
        </select>
      </div>`;
  } else if (questionType === "true_false") {
    dynamicFields.innerHTML = `
      <div class="mb-4">
        <label class="block font-semibold">Correct Answer:</label>
        <select name="correct_answer" class="w-full p-2 border rounded" required>
          <option value="">Select</option>
          <option value="true">True</option>
          <option value="false">False</option>
        </select>
      </div>`;
  } else if (questionType === "identification") {
    dynamicFields.innerHTML = `
      <div class="mb-4">
        <label class="block font-semibold">Correct Answer:</label>
        <input type="text" name="correct_answer" class="w-full p-2 border rounded" required>
      </div>`;
  }
}

function addChoice() {
  const choicesContainer = document.getElementById("choices_container");
  const correctChoiceDropdown = document.getElementById("correct_choice");
  const choiceIndex = choicesContainer.children.length;

  const newChoice = document.createElement("input");
  newChoice.type = "text";
  newChoice.name = "choices[]";
  newChoice.className = "w-full p-2 border rounded mb-2";
  newChoice.placeholder = `Choice ${choiceIndex + 1}`;
  newChoice.required = true;
  choicesContainer.appendChild(newChoice);

  const newOption = document.createElement("option");
  newOption.value = newChoice.value;
  newOption.textContent = newChoice.value || `Choice ${choiceIndex + 1}`;
  
  newChoice.addEventListener("input", function () {
    newOption.value = newChoice.value;
    newOption.textContent = newChoice.value || `Choice ${choiceIndex + 1}`;
  });

  correctChoiceDropdown.appendChild(newOption);
}

window.addEventListener("DOMContentLoaded", () => {
  toggleFields();
});
</script>
</body>
</html>