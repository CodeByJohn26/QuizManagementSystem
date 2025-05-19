<?php require 'take_quiz_backend.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Take Quiz</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        let timeLeft = <?= json_encode((int)$remaining_time) ?>;

        function startTimer() {
            const timerDisplay = document.getElementById("timer");
            const form = document.getElementById("quizForm");

            const interval = setInterval(() => {
                if (timeLeft <= 0) {
                    clearInterval(interval);
                    alert("Time is up! Submitting your quiz.");
                    form.submit();
                } else {
                    const minutes = Math.floor(timeLeft / 60);
                    const seconds = timeLeft % 60;
                    timerDisplay.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
                    document.getElementById("remaining_time_input").value = timeLeft;
                    timeLeft--;
                }
            }, 1000);
        }

        window.onload = startTimer;
    </script>
</head>
<body class="min-h-screen bg-gradient-to-br from-yellow-400 via-yellow-300 to-blue-500 p-6">
<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-lg p-6">
    <h4 class="text-center text-red-600 text-lg font-semibold mb-2">
        Remaining Time: <span id="timer" class="font-mono">Loading...</span>
    </h4>
    <h2 class="text-center text-3xl font-bold text-gray-700 mb-2">
        <?= htmlspecialchars($quiz['title'], ENT_QUOTES); ?>
    </h2>
    <p class="text-center text-gray-500 mb-6">
        Time Limit: <?= htmlspecialchars($quiz['time_limit'], ENT_QUOTES); ?> minute(s)
    </p>

    <form id="quizForm" method="POST">
        <input type="hidden" name="remaining_time" id="remaining_time_input" value="<?= $remaining_time ?>">

        <?php foreach ($questions as $index => $question): ?>
            <?php
                $quiz_class = match ($question['question_type']) {
                    'multiple_choice' => 'bg-green-500 text-white',
                    default => 'bg-gray-400 text-white',
                };
            ?>
            <div class="bg-gray-100 rounded-xl p-4 shadow mb-6">
                <h5 class="text-lg font-semibold mb-2">
                    <?= ($index + 1) . '. ' . htmlspecialchars($question['question_text'], ENT_QUOTES); ?>
                    <span class="ml-2 px-2 py-1 text-sm rounded <?= $quiz_class ?>">
                        <?= ucfirst(str_replace('_', ' ', $question['question_type'])); ?>
                    </span>
                </h5>

                <?php if ($question['question_type'] === 'multiple_choice'): ?>
                    <?php
                        $choices = json_decode($question['choices'], true) ?: [];
                    ?>

                    <?php if (!empty($choices)): ?>
                        <div class="space-y-2">
                            <?php foreach ($choices as $choice): ?>
                                <label class="flex items-center space-x-2">
                                    <input type="radio"
                                           class="accent-blue-500"
                                           name="question_<?= $question['id']; ?>"
                                           value="<?= htmlspecialchars($choice, ENT_QUOTES); ?>" required>
                                    <span><?= htmlspecialchars($choice, ENT_QUOTES); ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-red-500 mt-2">No choices available for this question.</p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <button type="submit" name="submit_quiz"
                class="w-full bg-gradient-to-r from-blue-600 to-yellow-400 text-white font-semibold py-2 px-4 rounded-xl shadow hover:opacity-90 transition">
            Submit Quiz
        </button>
    </form>
</div>
</body>
</html>