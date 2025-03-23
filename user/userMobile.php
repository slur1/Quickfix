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
  <title>Mobile Change</title>
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
      <h1 class="text-3xl font-bold text-blue-900 mb-4">
        <?php
        // Check if the user is logged in and if the contact number is available
        if (isset($_SESSION['user_contact_number'])) {
          echo $_SESSION['user_contact_number'];
        } else {
          echo "Contact number not available";
        }
        ?>
      </h1>


      <p class="text-gray-700 mb-8">
        We'll keep you up to date about the latest happenings on your jobs by SMS.
      </p>

      <div x-data="{ mobileNumber: '' }">
        <form @submit.prevent="console.log('Sending verification code to:', mobileNumber)">
          <div class="space-y-2">
            <label for="mobile" class="text-lg font-semibold text-blue-900">
              Mobile number
            </label>
            <p class="text-sm text-gray-600">
              We will send you a verification code
            </p>

            <div class="flex gap-4">
              <div class="relative w-64"> <!-- Reduced width applied here -->
                <span class="absolute left-3 top-1/2 -translate-y-1/2">
                  <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                  </svg>
                </span>
                <input
                  type="tel"
                  id="mobile"
                  placeholder="04123456789"
                  class="w-full pl-10 pr-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  x-model="mobileNumber"
                  pattern="[0-9]*"
                  required>
              </div>
              <button
                type="submit"
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-8 rounded focus:outline-none focus:shadow-outline">
                Send
              </button>
            </div>
          </div>
        </form>
      </div>

      <p class="mt-6 text-sm text-gray-500 leading-relaxed">
        By verifying your mobile number, we can ensure you're a real person! Rest assured, we won’t share it with anyone or sell it to third parties. It’s simply for us to send you important updates and notifications.
      </p>
    </main>

</body>

</html>