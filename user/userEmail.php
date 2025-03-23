<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
  // Redirect to the login page if not logged in
  header("Location: userLogin.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Email Change</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
      <h1 class="text-3xl font-bold text-blue-900 mb-4">Email Address</h1>

      <p class="text-gray-700 mb-8">
        We'll keep you up to date about the latest happenings on your jobs by email.
      </p>

      <div x-data="{ mobileNumber: '' }">
        <form @submit.prevent="console.log('Sending verification code to:', mobileNumber)">
          <div class="space-y-2">
            <label for="mobile" class="text-lg font-semibold text-blue-900">
              Email Address
            </label>

            <div class="flex gap-4">
              <div class="relative w-64"> <!-- Reduced width applied here -->
                <span class="absolute left-3 top-1/2 -translate-y-1/2">
                  <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a3 3 0 003.22 0L21 8m-18 0v8a2 2 0 002 2h14a2 2 0 002-2V8m-18 0L12 3l9 5" />
                  </svg>
                </span>
                <input
                  type="email"
                  id="email"
                  placeholder="example@email.com"
                  class="w-full pl-10 pr-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  x-model="emailAddress"
                  required>
              </div>
              <button
                type="submit"
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-8 rounded focus:outline-none focus:shadow-outline">
                Change
              </button>
            </div>
          </div>
        </form>
      </div>

      <p class="mt-6 text-sm text-gray-500 leading-relaxed">
        To manage your email notification preferences, please click here.
      </p>
    </main>

</body>

</html>