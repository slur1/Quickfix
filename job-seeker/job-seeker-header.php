<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Become a Job Seeker | Quickfix</title>
  <link rel="icon" type="logo" href="./img/logo1.png">
  <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css" />
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');

    body {
      font-family: 'Montserrat', sans-serif;
    }
  </style>
</head>

<body>

  <!-- Navigation -->
  <nav class="flex justify-between items-center p-4 bg-white shadow-md sticky top-0 z-50">
    <!-- Logo Section -->
    <a href="../index.php" class="flex items-center space-x-2">
      <img src="../img/logo1.png" alt="Company Logo" class="h-12 w-auto">
      <span class="text-2xl font-bold text-blue-900">QuickFix</span>
    </a>
    <!-- Hamburger Button (visible on small screens) -->
    <button id="nav-toggle" @click="mobileOpen = !mobileOpen" class="block lg:hidden p-2 text-gray-900 focus:outline-none">
      <svg class="h-6 w-6 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
        <title>Menu</title>
        <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
      </svg>
    </button>
    <!-- Full Menu -->
    <div id="nav-content" x-data="{ mobileOpen: false, categoryOpen: false, userType: null }"
      :class="{'hidden': !mobileOpen, 'flex': mobileOpen}"
      class="lg:flex lg:items-center flex-col lg:flex-row lg:space-x-6 lg:space-y-0 absolute lg:relative top-full left-0 w-full lg:w-auto bg-white shadow-md lg:shadow-none lg:bg-transparent">
      <!-- Links Section -->
      <div class="flex flex-col lg:flex-row items-start lg:items-center lg:space-x-4">

      <!-- Buttons Section -->
      <div class="flex justify-center space-x-4 py-4 lg:py-0">
        <a href="../user/user-registration.php"><button class="px-4 py-2 text-blue-600 hover:bg-blue-50 rounded-md">Sign Up</button></a>
      </div>
    </div>
  </nav>


  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</body>

</html>