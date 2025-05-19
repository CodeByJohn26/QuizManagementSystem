<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Questions</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen">

<div class="container mt-5">
    <h1 class="text-center text-2xl font-bold text-blue-600 mb-5">View Questions for Quiz</h1>

    <!-- Loading Indicator -->
    <div id="loading-indicator" style="display: none;" class="text-center mb-4">
        <p>Loading questions...</p>
    </div>

    <!-- List of Questions -->
    <ul id="questions-list" class="list-group"></ul>

    <!-- Load More Button -->
    <button id="load-more-btn" class="btn btn-primary mt-4 w-100 text-lg font-bold" onclick="loadQuestions()">Load More</button>
</div>

<script>
    let quizId = <?php echo isset($_GET['quiz_id']) ? (int)$_GET['quiz_id'] : 'null'; ?>;
    let currentPage = 1;

    // Load questions dynamically using AJAX
    async function loadQuestions() {
        if (!quizId) {
            alert('No quiz selected. Please select a quiz.');
            return;
        }

        const loadingIndicator = document.getElementById('loading-indicator');
        const loadMoreBtn = document.getElementById('load-more-btn');
        loadingIndicator.style.display = 'block'; // Show loading indicator
        loadMoreBtn.disabled = true; // Disable button while loading

        try {
            const response = await fetch(`load_questions.php?quiz_id=${quizId}&page=${currentPage}`);
            if (!response.ok) throw new Error('Failed to fetch questions.');
            const data = await response.json();

            const questionsList = document.getElementById('questions-list');
            data.questions.forEach(q => {
                const listItem = document.createElement('li');
                listItem.classList.add('list-group-item', 'mb-2');
                listItem.innerHTML = `
                    <strong>Question:</strong> ${q.question_text}<br>
                    <strong>Type:</strong> ${q.question_type}<br>
                    ${q.choices?.length ? `<strong>Choices:</strong> ${q.choices.map(c => `${c.choice_label}: ${c.choice_text}`).join(', ')}` : ''}
                    <small class="text-muted">Created on: ${q.created_at}</small>
                `;
                questionsList.appendChild(listItem);
            });

            currentPage++; // Increment page for pagination
        } catch (error) {
            console.error('Error loading questions:', error);
            alert('Failed to load questions. Please try again later.');
        } finally {
            loadingIndicator.style.display = 'none'; // Hide loading indicator
            loadMoreBtn.disabled = false; // Enable button after loading
        }
    }

    // Initial load
    loadQuestions();
</script>

</body>
</html>