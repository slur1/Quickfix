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
  <title>Account</title>
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

    <main class="max-w-3xl mx-auto px-4 py-8">
      <!-- Account Header -->
      <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Account</h1>
        <div class="mt-1 h-2 w-full bg-green-100">
          <div class="h-full w-2/3 bg-green-500"></div>
        </div>
        <p class="mt-2 text-sm text-gray-500">YOUR VERIFICATION IS 66% COMPLETE</p>
      </div>

      <!-- Profile Image Section -->
      <div class="mb-8">
        <div class="flex items-start gap-8">
          <div class="relative">
            <div class="h-24 w-24 rounded-full bg-blue-50 border-2 border-blue-100 flex items-center justify-center">
              <svg class="h-12 w-12 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
            </div>
          </div>
          <div class="flex flex-col gap-4">
            <button class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
              Upload photo
            </button>
            <a href="#" class="text-blue-500 hover:text-blue-600 text-sm">View your public profile</a>
          </div>
        </div>
      </div>

      <!-- Profile Form -->
      <form class="space-y-6">
        <div>
          <label class="block text-sm font-medium text-gray-700">Profile image</label>
          <div class="mt-1 h-32 w-full bg-gray-50 border border-gray-200 rounded-md"></div>
          <button type="button" class="mt-2 text-blue-500 hover:text-blue-600 text-sm">Upload profile image</button>
        </div>

        <div class="grid grid-cols-2 gap-6">
          <div>
            <label for="firstName" class="block text-sm font-medium text-gray-700">First name*</label>
            <input type="text" id="firstName" class="w-full pl-10 pr-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              value="<?php echo isset($_SESSION['user_first_name']) ? $_SESSION['user_first_name'] : ''; ?>">
          </div>

          <div>
            <label for="lastName" class="block text-sm font-medium text-gray-700">Last name*</label>
            <input type="text" id="lastName" class="w-full pl-10 pr-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              value="<?php echo isset($_SESSION['user_last_name']) ? $_SESSION['user_last_name'] : ''; ?>">
          </div>
        </div>

        <div>
          <label for="tagline" class="block text-sm font-medium text-gray-700">Tagline</label>
          <input type="text" id="tagline" class="w-full pl-10 pr-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="awdawdwa">
        </div>

        <div>
          <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
          <input type="text" id="location" class="w-full pl-10 pr-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="Barangay 168 Deparo, Caloocan City">
        </div>

        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <input type="email" id="email" class="w-full pl-10 pr-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            value="<?php echo isset($_SESSION['user_email']) ? $_SESSION['user_email'] : ''; ?>">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Birthday</label>
          <div class="mt-1 grid grid-cols-3 gap-4">
            <input type="text" placeholder="DD" class="w-full pl-10 pr-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <input type="text" placeholder="MM" class="w-full pl-10 pr-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <input type="text" placeholder="YYYY" class="w-full pl-10 pr-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
          </div>
        </div>

        <div>
          <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
          <textarea id="description" rows="4" class="w-full pl-10 pr-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
        </div>

        <div class="flex flex-col gap-4">
          <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Save profile
          </button>
          <button type="button" class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
            Delete my account
          </button>
        </div>
      </form>
    </main>
</body>


</html>