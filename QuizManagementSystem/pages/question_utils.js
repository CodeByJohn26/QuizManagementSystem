let currentPage = 1;

/**
 * Toggles the dynamic fields for different question types.
 */
function toggleFields() {
    const type = document.getElementById('question_type').value;
    const dynamicFields = document.getElementById('dynamic_fields');
    dynamicFields.innerHTML = ''; // Clear previous fields

    if (type === 'multiple_choice') {
        // Render fields for multiple-choice questions
        dynamicFields.innerHTML = `
            <div class="mb-3">
                <label class="form-label text-gray-700">Enter Choices:</label>
                <input type="text" name="choices[]" placeholder="Choice A (e.g., Red)" class="form-control mb-2" required>
                <input type="text" name="choices[]" placeholder="Choice B (e.g., Blue)" class="form-control mb-2" required>
                <input type="text" name="choices[]" placeholder="Choice C (Optional)" class="form-control mb-2">
                <input type="text" name="choices[]" placeholder="Choice D (Optional)" class="form-control mb-2">
                <input type="text" name="choices[]" placeholder="Choice E (Optional)" class="form-control mb-2">
                <label for="correct_choice" class="form-label text-gray-700">Correct Choice:</label>
                <select name="correct_choice" id="correct_choice" class="form-select border border-gray-300 rounded" required>
                    <option value="a">A</option>
                    <option value="b">B</option>
                    <option value="c">C</option>
                    <option value="d">D</option>
                    <option value="e">E</option>
                </select>
            </div>
        `;
    } else if (type === 'true_false') {
        // Render fields for true/false questions
        dynamicFields.innerHTML = `
            <label for="correct_answer" class="form-label text-gray-700">Correct Answer:</label>
            <select name="correct_answer" id="correct_answer" class="form-select border border-gray-300 rounded" required>
                <option value="true">True</option>
                <option value="false">False</option>
            </select>
        `;
    } else if (type === 'identification') {
        // Render fields for identification questions
        dynamicFields.innerHTML = `
            <label for="correct_answer" class="form-label text-gray-700">Correct Answer:</label>
            <input type="text" name="correct_answer" id="correct_answer" class="form-control" placeholder="Enter correct answer" required>
        `;
    } else if (type === 'essay') {
        // For essay questions, no extra fields are required
        dynamicFields.innerHTML = `
            <p class="text-gray-700">Essay questions are manually graded. No further input is required.</p>
        `;
    }
}

/**
 * Dynamically loads questions for a specific quiz using AJAX.
 * @param {number} quizId - The ID of the quiz to fetch questions for.
 */
async function loadQuestions(quizId) {
    if (!quizId) {
        alert('No quiz selected. Please select a quiz.');
        return;
    }

    const loadingIndicator = document.getElementById('loading-indicator');
    loadingIndicator.style.display = 'block'; // Show loading indicator

    try {
        const response = await fetch(`load_questions.php?quiz_id=${quizId}&page=${currentPage}`);
        if (!response.ok) throw new Error('Failed to fetch questions.');
        const data = await response.json();

        const questionsList = document.getElementById('questions-list');
        data.questions.forEach(q => {
            const listItem = document.createElement('li');
            listItem.classList.add('list-group-item');
            listItem.innerHTML = `
                <strong>Question:</strong> ${q.question_text}<br>
                <strong>Type:</strong> ${q.question_type}<br>
                <small class="text-muted">Created on ${q.created_at}</small>
            `;
            questionsList.appendChild(listItem);
        });

        currentPage++; // Increment page for pagination
    } catch (error) {
        console.error('Error loading questions:', error);
        alert('Failed to load questions. Please try again later.');
    } finally {
        loadingIndicator.style.display = 'none'; // Hide loading indicator
    }
}