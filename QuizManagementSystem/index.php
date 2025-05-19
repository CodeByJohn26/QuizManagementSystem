<?php
require 'includes/db_connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Welcome to Quiz Management System</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://kit.fontawesome.com/0aaabd993c.js" crossorigin="anonymous"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Fredoka+One&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap');

    body {
      font-family: 'Inter', sans-serif;
    }
    .font-fredoka {
      font-family: 'Fredoka One', cursive;
    }
  </style>
</head>
<body class="min-h-screen relative flex flex-col justify-center items-center text-center px-4 bg-gradient-to-br from-yellow-400 via-yellow-300 to-blue-500">

  <div class="absolute top-4 left-6">
    <img src="assets/images/logo.png" class="h-20" alt="Logo">
  </div>

  <div class="absolute top-4 right-6 space-x-4">
    <a href="pages/register_form.php" class="px-4 py-2 bg-green-600 text-white rounded-md font-semibold hover:bg-green-700 transition shadow-lg shadow-gray-500/50 opacity-90 hover:opacity-100"><i class="fa fa-plus" aria-hidden="true"></i> Create Account</a>
    <a href="pages/login.php" class="px-4 py-2 bg-blue-600 text-white rounded-md font-semibold hover:bg-blue-700 transition shadow-lg shadow-gray-500/50 opacity-90 hover:opacity-100"><i class="fa fa-sign-in" aria-hidden="true"></i> Login</a>
  </div>

  <!-- Main Content -->
  <main class="flex flex-col justify-center items-center text-center mt-12">
    <h1 class="text-5xl font-bold text-yellow-600 drop-shadow mb-4">Welcome to Quiz Management System</h1>
    <p class="text-2xl font-bold text-blue-800 drop-shadow mb-8">Your gateway to smarter learning and teaching!</p>
  </main>

  <!-- Footer -->
  <footer class="absolute font-bold bottom-6 text-black text-sm">
    <p>&copy; 2025 Quiz Management System <br> Baltazar, Emasa, Espinosa, Lorenzo, Pacete</p>
  </footer>

</body>
</html>
