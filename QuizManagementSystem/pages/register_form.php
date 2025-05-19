<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Student Registration</title>
  <script src="https://cdn.tailwindcss.com"></script>
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
<body class="bg-gradient-to-br from-yellow-400 via-yellow-300 to-blue-500 min-h-screen flex items-center justify-center p-4">
  <div class="w-full max-w-lg bg-gradient-to-br from-yellow-400 via-yellow-300 to-blue-500 p-10 rounded-lg shadow-lg">
    <h1 class="text-5xl font-fredoka text-[#1a1a1a] mb-2">Register Now!</h1>
    <p class="text-sm text-[#4a4a4a] mb-6">Create your student account</p>

    <form class="w-full" action="register.php" method="POST">
      <label for="username" class="block text-xs font-semibold text-[#5a5a5a] mb-1 tracking-widest">USERNAME</label>
      <input type="text" name="username" id="username" class="w-full mb-4 rounded border px-4 py-3 text-xs text-[#4a4a4a] placeholder-[#7a7a7a] focus:outline-none focus:ring-1 focus:ring-green-500" placeholder="Enter your username" required>

      <label for="email" class="block text-xs font-semibold text-[#5a5a5a] mb-1 tracking-widest">EMAIL</label>
      <input type="email" name="email" id="email" class="w-full mb-4 rounded border px-4 py-3 text-xs text-[#4a4a4a] placeholder-[#7a7a7a] focus:outline-none focus:ring-1 focus:ring-green-500" placeholder="Enter your email" required>

      <label for="password" class="block text-xs font-semibold text-[#5a5a5a] mb-1 tracking-widest">PASSWORD</label>
      <input type="password" name="password" id="password" class="w-full mb-4 rounded border px-4 py-3 text-xs text-[#4a4a4a] placeholder-[#7a7a7a] focus:outline-none focus:ring-1 focus:ring-green-500" placeholder="Enter your password" required>

      <div class="flex items-center mt-2 mb-4">
        <input type="checkbox" id="showPassword" class="mr-2 cursor-pointer">
        <label for="showPassword" class="text-xs text-[#4a4a4a] cursor-pointer">Show Password</label>
      </div>

      <!-- Hidden Role Input -->
      <input type="hidden" name="role" value="student">

      <button type="submit" class="w-full bg-[#4caf50] text-white text-xs font-semibold py-3 mt-2 rounded hover:bg-[#3b8e3b] transition-colors">REGISTER</button>
    </form>

    <div class="w-full flex justify-between mt-4 text-[15px] text-[#4a4a4a]">
      <a href="login.php">Already have an account?</a>
      <a href="../index.php">Home</a>
    </div>
  </div>

  <!-- JavaScript should be inside the body and properly closed -->
  <script>
    const showPasswordCheckbox = document.getElementById('showPassword');
    const passwordInput = document.getElementById('password');

    showPasswordCheckbox.addEventListener('change', function () {
      passwordInput.type = this.checked ? 'text' : 'password';
    });
  </script>
</body>
</html>
