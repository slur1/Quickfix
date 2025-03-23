<?php

session_start();


if (!isset($_SESSION['user_id'])) {
  
  header("Location: userLogin.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Job Seeker Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" type="logo" href="../img/logo1.png">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');

    body {
      font-family: 'Montserrat', sans-serif;
    }
  </style>
</head>

<?php
include './userHeader.php';
?>

<body class="bg-gray-50">
  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white p-6 shadow-sm">
      <?php include './userSideBar.php'; ?>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8">
      <h1 class="text-3xl font-bold text-blue-900 mb-8">My Job Seeker Dashboard</h1>

      <!-- Completion Rating Section -->
      <section class="mb-12">
        <h2 class="text-xl font-semibold text-blue-900 mb-4">Your Completion Rating (last 20 jobs)</h2>
        <p class="text-gray-600 mb-6">We've set your Completion Rating as Good to help kickstart your QuickFix journey. Complete your assigned jobs to keep this score high!</p>

        <!-- Progress Bar -->
        <div class="h-2 bg-gray-200 rounded-full mb-2">
          <div class="h-full w-0 bg-green-500 rounded-full"></div>
        </div>
        <div class="flex justify-between text-sm text-gray-600">
          <span>Poor</span>
          <span>Okay</span>
          <span>Good</span>
          <span>Excellent</span>
        </div>
        <div class="text-right text-sm font-medium text-gray-900">0/0</div>
      </section>

      <!-- Earnings Section -->
      <section class="mb-12">
        <h2 class="text-xl font-semibold text-blue-900 mb-4">Your Earnings (last 30 days)</h2>
        

        <!-- Progress Bar -->
        <div class="h-2 bg-gray-200 rounded-full mb-2">
          <div class="h-full w-0 bg-blue-500 rounded-full"></div>
        </div>
        <div class="flex justify-between text-sm text-gray-600">
          <span>₱0</span>
          <span>₱880</span>
          <span>₱3,000</span>
          <span>₱6,000+</span>
        </div>
      </section>

    </main>
  </div>
</body>

</html>