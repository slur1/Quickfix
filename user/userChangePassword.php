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
  <title>Change Password</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <script src="https://unpkg.com/alpinejs" defer></script>
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
    <main class="flex-1 flex items-center justify-center">
      <div class="max-w-md w-full text-center space-y-4">
        <h1 class="text-4xl font-bold text-blue-900">Change Password</h1>

        <!-- Lock Icon -->
        <div class="flex justify-center">
          <svg class="w-24 h-24" viewBox="0 0 24 24" fill="none">
            <rect x="5" y="11" width="14" height="10" class="fill-blue-500" />
            <path d="M17 11V7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7V11" stroke="black" stroke-width="2" />
            <circle cx="12" cy="16" r="2" fill="black" />
          </svg>
        </div>

        <!-- Message -->
        <div class="space-y-2">
          <p class="text-gray-800">
            To change your password, click the button below to receive an email with a password reset link. This email will be sent to:
          </p>
          <p class="font-medium text-gray-900">
            <?php echo isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'No email available'; ?>
          </p>
          <p class="text-gray-800">
            If you need to update your email address, please click
            <a href="#" class="text-blue-600 hover:text-blue-700">here</a>.
          </p>
        </div>

        <!-- Button -->
        <div>
          <button
            @click="resetPassword()"
            :disabled="isLoading"
            class="px-6 py-3 bg-blue-500 text-white rounded-full hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
            <span x-show="!isLoading">Reset password</span>
          </button>
        </div>
      </div>
    </main>
</body>




</html>