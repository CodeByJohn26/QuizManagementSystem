<?php
require '../includes/db_connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        echo "<script>alert('Email and password are required!'); window.history.back();</script>";
        exit;
    }

    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        if ($user['role'] !== 'teacher') {
            echo "<script>alert('Access denied. Please log in through your designated portal.'); window.location.href='login.php';</script>";
            exit;
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        header("Location: teacher_dashboard.php");
        exit;
    } else {
        echo "<script>alert('Invalid email or password.'); window.history.back();</script>";
        exit;
    }
}
?>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>Login Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap"
      rel="stylesheet"
    />
    <style>
      body {
        font-family: 'Poppins', sans-serif;
      }
    </style>
  </head>
  <body class="bg-gradient-to-br from-yellow-400 via-yellow-300 to-blue-500 min-h-screen flex items-center justify-center p-4">
    <div class="flex flex-col md:flex-row items-center justify-center max-w-7xl w-full rounded-lg">
      <!-- Login Form -->
      <div class="flex flex-col items-start max-w-lg w-full bg-gradient-to-br from-yellow-400 via-yellow-300 to-blue-500 p-10 rounded-lg shadow-lg">
        <h1 class="text-5xl font-semibold text-[#1a1a1a] mb-1">
          Good Day, Teacher!
        </h1>
        <p class="text-sm text-[#4a4a4a]">Welcome!</p>
        <form class="w-full bg-transparent rounded-lg p-8" action="teacher_login.php" method="POST">

          <label for="email" class="block text-xs font-semibold text-[#5a5a5a] mb-1 tracking-widest"> EMAIL </label>
          <input type="email" id="email" name="email" class="w-full mb-4 rounded border px-4 py-3 text-xs text-[#4a4a4a] placeholder-[#7a7a7a] focus:outline-none focus:ring-1 focus:ring-green-500"/>
          
          <label for="password" class="block text-xs font-semibold text-[#5a5a5a] mb-1 tracking-widest"> PASSWORD </label>
          <input type="password" id="password" name="password" class="w-full mb-1 rounded border px-4 py-3 text-xs text-[#4a4a4a] placeholder-[#7a7a7a] focus:outline-none focus:ring-1 focus:ring-green-500"/>
          
          <div class="flex items-center mt-2 mb-4">
            <input type="checkbox" id="showPassword" class="mr-2 cursor-pointer">
            <label for="showPassword" class="text-xs text-[#4a4a4a] cursor-pointer">Show Password</label>
          </div>

          <!-- <p class="text-[12px] text-[#4a4a4a] mb-4">Forgot password?</p> -->
          <button type="submit" class="w-full bg-[#4caf50] mt-3 text-white text-xs font-semibold py-3 rounded hover:bg-[#3b8e3b] transition-colors"> LOGIN </button>
        </form>
        
   <!-- <a class="text-[12px] text-[#4a4a4a]" href="register_form.php">Don't have an account?</a> -->
      </div>

      <!-- Image Section -->
      <div class="hidden md:block relative w-[50rem] h-[55rem] ml-10 opacity-90">
        <img
          src="../assets/images/teacher.png"
          alt="Student Illustration"
          class="w-full h-full object-contain"
        />
      </div>
    </div>
  </body>
  <script>
    const showPasswordCheckbox = document.getElementById('showPassword');
    const passwordInput = document.getElementById('password');

    showPasswordCheckbox.addEventListener('change', function () {
      passwordInput.type = this.checked ? 'text' : 'password';
    });
  </script>
</html>
