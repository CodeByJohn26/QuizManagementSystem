<?php
session_start();

// Clear all session data.
$_SESSION = array();
session_destroy();

// Display a logout message before redirecting.
echo "<!DOCTYPE html>
<html lang='en'>
<head>
  <meta charset='UTF-8'>
  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
  <title>Logged Out</title>
  <!-- Tailwind CSS -->
  <script src='https://cdn.tailwindcss.com'></script>
  <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css' rel='stylesheet'/>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Fredoka+One&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap');

    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(to bottom right, #facc15, #fde68a, #3b82f6);
      margin: 0;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .logout-container {
      background: white;
      border: 2px solid #2B8A74;
      border-radius: 1rem;
      padding: 2rem;
      max-width: 400px;
      text-align: center;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }
    .logout-container h1 {
      font-family: 'Fredoka One', cursive;
      font-size: 25px;
      color: #1d4ed8;
      margin-bottom: 1rem;
    }
    .logout-container p {
      font-size: 16px;
      color: #1f2937;
      margin-bottom: 1.5rem;
    }
    .spinner {
      border: 4px solid #fde68a;
      border-top: 4px solid #3b82f6;
      border-radius: 50%;
      width: 40px;
      height: 40px;
      animation: spin 1s linear infinite;
      margin: 0 auto 1rem;
    }
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
  </style>
  <script>
    let countdown = 3;
    function updateCountdown() {
      if (countdown > 0) {
        document.getElementById('seconds').textContent = countdown;
        countdown--;
      } else {
        window.location.href = 'login.php';
      }
    }
    setInterval(updateCountdown, 1000);
  </script>
</head>
<body>
  <div class='logout-container'>
    <div class='spinner'></div>
    <h1>You have been logged out.</h1>
    <p>Redirecting to the login page in <span id='seconds'>3</span> seconds...</p>
  </div>
</body>
</html>";
exit;
?>
