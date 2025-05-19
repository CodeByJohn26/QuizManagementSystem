<?php
require 'view_analytics_backend.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz Analytics</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        .hidden { display: none; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="flex items-center justify-between p-4 gradient-header shadow-lg">
        <div class="text-2xl font-bold"><i class="fa fa-plus" aria-hidden="true"></i> Teacher Panel</div>
        <ul class="flex gap-6 text-white">
            <li><a href="teacher_dashboard.php" class="hover:text-yellow-300"><i class="fa fa-home" aria-hidden="true"></i> Dashboard</a></li>
            <li><a href="create_quiz.php" class="hover:text-yellow-300"><i class="fa fa-plus" aria-hidden="true"></i> Create Quiz</a></li>
            <li><a href="view_analytics.php" class="text-yellow-300 font-semibold"><i class="fa fa-line-chart" aria-hidden="true"></i> Analytics</a></li>
            <li><a href="edit_teacher_profile.php" class="hover:text-yellow-300"><i class="fa fa-user" aria-hidden="true"></i> Profile</a></li>
            <li><a href="logout.php" class="hover:text-yellow-300"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main class="p-8">
        <div class="max-w-5xl mx-auto bg-white p-8 rounded-xl shadow-md border-t-4 border-yellow-400">
            <h1 class="text-2xl font-bold text-blue-600 mb-4"><i class="fa fa-line-chart" aria-hidden="true"></i> Quiz Analytics</h1>
            <p class="text-gray-700 mb-6">Detailed analytics of student performance in your quizzes.</p>

            <?php if (!empty($analytics_data)): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-700 border rounded-md">
                        <thead class="bg-gray-100 text-gray-900 font-semibold">
                            <tr>
                                <th class="px-4 py-2">Quiz Title</th>
                                <th class="px-4 py-2">Attempts</th>
                                <th class="px-4 py-2">Enrolled Students Who Took the Quiz</th>
                                <th class="px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($analytics_data as $quiz): ?>
                                <tr class="border-t">
                                    <td class="px-4 py-2"><?= htmlspecialchars($quiz['title']); ?></td>
                                    <td class="px-4 py-2"><?= (int)$quiz['attempts']; ?></td>
                                    <td class="px-4 py-2"><?= count($chart_data[$quiz['id']]['students']); ?> Students</td>
                                    <td class="px-4 py-2 space-x-2">
                                        <button class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm" onclick="toggleVisibility('students<?= $quiz['id']; ?>')">Students</button>
                                        <button class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm" onclick="toggleVisibility('chart<?= $quiz['id']; ?>')">Chart</button>
                                        <a href="export_quiz_records.php?quiz_id=<?= urlencode($quiz['id']); ?>" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">Download Excel</a>
                                    </td>
                                </tr>

                                <tr id="students<?= $quiz['id']; ?>" class="hidden">
                                    <td colspan="4" class="bg-gray-50 px-4 py-4">
                                        <?php if (!empty($chart_data[$quiz['id']]['students'])): ?>
                                            <ul class="list-disc pl-5 space-y-1 text-gray-700">
                                                <?php foreach ($chart_data[$quiz['id']]['students'] as $student): ?>
                                                    <li><?= htmlspecialchars($student['student_name']); ?> - Score: <?= htmlspecialchars($student['score']); ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php else: ?>
                                            <p class="text-gray-500">No enrolled students have taken this quiz.</p>
                                        <?php endif; ?>
                                    </td>
                                </tr>

                                <tr id="chart<?= $quiz['id']; ?>" class="hidden">
                                    <td colspan="4" class="bg-white px-4 py-4">
                                        <div class="h-64">
                                            <canvas id="chartCanvas<?= $quiz['id']; ?>"></canvas>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-center text-gray-500">No quizzes available for analytics.</p>
            <?php endif; ?>
        </div>
    </main>

<script>
const chartData = <?= json_encode($chart_data); ?>;
const charts = {};

function renderChart(quizId) {
    const canvas = document.getElementById(`chartCanvas${quizId}`);
    if (!canvas) return;

    const ctx = canvas.getContext('2d');

    if (charts[quizId]) {
        charts[quizId].destroy();
    }

    const totalPossibleScore = chartData[quizId]?.total ?? 100;

    const percentageScores = chartData[quizId].scores.map(score =>
        totalPossibleScore > 0 ? (score / totalPossibleScore) * 100 : 0
    );

    charts[quizId] = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartData[quizId].labels,
            datasets: [
                {
                    label: 'Student Scores (%)',
                    data: percentageScores,
                    backgroundColor: 'rgba(59, 130, 246, 0.6)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1
                },
                {
                    label: '100% Line',
                    data: Array(chartData[quizId].labels.length).fill(100),
                    backgroundColor: 'rgba(234, 179, 8, 0.5)',
                    borderColor: 'rgba(234, 179, 8, 1)',
                    type: 'line',
                    borderWidth: 2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    title: {
                        display: true,
                        text: 'Student Scores (%)'
                    }
                }
            }
        }
    });
}

function toggleVisibility(elementId) {
    const element = document.getElementById(elementId);
    if (!element) return;

    element.classList.toggle('hidden');

    if (elementId.startsWith('chart') && !element.classList.contains('hidden')) {
        const quizId = elementId.replace('chart', '');
        renderChart(quizId);
    }
}
</script>
</body>
</html>
