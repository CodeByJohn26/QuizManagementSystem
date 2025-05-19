function toggleFields() {
  const questionDropdown = document.getElementById("question_type");
  const dynamicFields = document.getElementById("dynamic_fields");

  // Exit if elements are not found.
  if (!questionDropdown || !dynamicFields) {
    return;
  }

  const questionType = questionDropdown.value;
  dynamicFields.innerHTML = ""; // Clear previous dynamic fields

  // Multiple Choice Fields
  if (questionType === "multiple_choice") {
    dynamicFields.innerHTML = `
      <div class="mb-3">
        <label class="form-label text-gray-700">Choices (A, B, C, D, E):</label>
        ${["A", "B", "C", "D", "E"].map((choice, index) => `
          <div class="mb-2">
            <input type="text" name="choices[]" class="form-control" placeholder="Choice ${choice}" ${index < 4 ? "required" : ""}>
          </div>
        `).join("")}
      </div>
      <div class="mb-3">
        <label class="form-label text-gray-700">Correct Choice:</label>
        <select name="correct_choice" class="form-select border border-gray-300 rounded" required>
          <option value="" selected>Choose the Correct Answer</option>
          ${["a", "b", "c", "d", "e"].map(choice => `<option value="${choice}">${choice.toUpperCase()}</option>`).join("")}
        </select>
      </div>
    `;
  }

  // True/False Fields
  else if (questionType === "true_false") {
    dynamicFields.innerHTML = `
      <div class="mb-3">
        <label class="form-label text-gray-700">Correct Answer:</label>
        <select name="correct_answer" class="form-select border border-gray-300 rounded" required>
          <option value="true">True</option>
          <option value="false">False</option>
        </select>
      </div>
    `;
  }

  // Identification Fields
  else if (questionType === "identification") {
    dynamicFields.innerHTML = `
      <div class="mb-3">
        <label class="form-label text-gray-700">Correct Answer:</label>
        <input type="text" name="correct_answer" class="form-control border border-gray-300 rounded" placeholder="Enter the correct answer" required>
      </div>
    `;
  }

  // Essay Fields
  else if (questionType === "essay") {
    dynamicFields.innerHTML = `
      <div class="mb-3">
        <label class="form-label text-gray-700">Maximum Score:</label>
        <input type="number" name="max_score" class="form-control border border-gray-300 rounded" placeholder="Enter maximum score" min="1" required>
      </div>
      <div class="mb-3">
        <label class="form-label text-gray-700">Answer:</label>
        <p class="text-red-500">The teacher will manually check and grade this question.</p>
      </div>
    `;
  }
}