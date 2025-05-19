<!-- login.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Role Selection</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Fredoka+One&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap');
    body { font-family: 'Inter', sans-serif; }
    .font-fredoka { font-family: 'Fredoka One', cursive; }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4 bg-gradient-to-br from-yellow-400 via-yellow-300 to-blue-500">
  <div class="text-center">
    <h1 class="text-black text-2xl sm:text-4xl font-semibold mb-12">
      Proceed by selecting your<br />designated role
    </h1>
    <div class="flex flex-col sm:flex-row gap-10 items-center justify-center">

      <!-- Student Card -->
      <a href="student_login.php">
        <div class="border border-[#2B8A74] rounded-2xl w-72 sm:w-80 p-8 flex flex-col items-center shadow-md transition-transform duration-300 hover:scale-110 bg-gradient-to-br from-yellow-400 via-yellow-300 to-blue-500">
          <img src="https://storage.googleapis.com/a1aa/image/589da7b2-b8ba-450f-fb72-d7b8653c4d6c.jpg" class="w-40 h-40 object-contain mb-6 rounded-full shadow" />
          <h2 class="font-fredoka text-blue-600 text-3xl font-bold mb-2">Student</h2>
          <p class="text-base text-gray-900">Submit Task</p>
        </div>
      </a>

      <!-- Teacher Card -->
      <a href="teacher_login.php">
        <div class="border border-[#2B8A74] rounded-2xl w-72 sm:w-80 p-8 flex flex-col items-center shadow-md transition-transform duration-300 hover:scale-110 bg-gradient-to-br from-yellow-400 via-yellow-300 to-blue-500">
          <img src="https://storage.googleapis.com/a1aa/image/60c86ba8-c8d4-43bc-416c-1c276a614a52.jpg" class="w-40 h-40 object-contain mb-6 rounded-full shadow" />
          <h2 class="font-fredoka text-blue-600 text-3xl font-bold mb-2">Teacher</h2>
          <p class="text-base text-gray-900">Assign Task</p>
        </div>
      </a>

      <!-- Admin Card -->
      <a href="admin_login.php">
        <div class="border border-[#2B8A74] rounded-2xl w-72 sm:w-80 p-8 flex flex-col items-center shadow-md transition-transform duration-300 hover:scale-110 bg-gradient-to-br from-yellow-400 via-yellow-300 to-blue-500">
          <img src="https://storage.googleapis.com/a1aa/image/df48b2a3-cf26-41e9-95b0-6f8f07ef2627.jpg" class="w-40 h-40 object-contain mb-6 rounded-full shadow" />
          <h2 class="font-fredoka text-blue-600 text-3xl font-bold mb-2">Admin</h2>
          <p class="text-base text-gray-900">Manage the data</p>
        </div>
      </a>

    </div>
  </div>
</body>
</html>
