<div class="relative">
  <!-- Profile Section -->
  <div class="text-center mb-8">
    <div class="w-24 h-24 mx-auto mb-4 rounded-full border-2 border-blue-500 p-1">
      <div class="w-full h-full bg-blue-100 rounded-full flex items-center justify-center">
        <div class="w-12 h-12 bg-blue-200 rounded-full"></div>
      </div>
    </div>

    <h2 class="text-lg font-medium text-blue-900">
      <?php
      // Check if the user is logged in and display their full name
      if (isset($_SESSION['user_first_name']) && isset($_SESSION['user_last_name'])) {
        echo $_SESSION['user_first_name'] . ' ' . $_SESSION['user_last_name'];
      } else {
        echo "Guest"; // Default value if not logged in
      }
      ?>
    </h2>
  </div>

  <!-- Navigation -->
  <nav
    x-data="{ showSettings: false }"
    :class="{ 'h-auto': showSettings, 'h-full': !showSettings }"
    class="space-y-6 bg-gray-50 transition-all duration-300 overflow-hidden">
    <a href="#" class="flex items-center text-blue-900 hover:text-blue-700">
      <img src="../img/userHome.svg" alt="Home" class="h-5 w-5 mr-3">
      Home
    </a>
    <!--<a href="../user/userJobSeekerDashboard.php" class="flex items-center text-blue-900 font-medium">
      <img src="../img/userDashboard.svg" alt="Dashboard" class="h-5 w-5 mr-3">
      My Job Seeker Dashboard
    </a>-->
    <!--<a href="../user/aboutListings.php" class="flex items-center text-blue-900 hover:text-blue-700">
      <img src="../img/userService.svg" alt="Services" class="h-5 w-5 mr-3">
      My services
    </a>-->
    <!--<a href="../user/userPaymentHistory.php" class="flex items-center text-blue-900 hover:text-blue-700">
      <img src="../img/userPaymentHistory.svg" alt="Payment History" class="h-5 w-5 mr-3">
      Payment history
    </a> -->
    <!--<a href="#" class="flex items-center text-blue-900 hover:text-blue-700">
      <img src="../img/userPaymentMethod.svg" alt="Payment Methods" class="h-5 w-5 mr-3">
      Payment methods
    </a> -->
    <a href="../user/userVerifyAccount.php" class="flex items-center text-blue-900 hover:text-blue-700">
      <img src="../img/userAccountVerify.svg" alt="Verify Account" class="h-5 w-5 mr-3">
      Verify Account
    </a>
    <!--<a href="#" class="flex items-center text-blue-900 hover:text-blue-700">
      <img src="../img/userNotifications.svg" alt="Notifications" class="h-5 w-5 mr-3">
      Notifications
    </a>-->
    <div class="relative">
      <div
        @click="showSettings = !showSettings"
        class="flex items-center justify-between text-blue-900 hover:text-blue-700 cursor-pointer">
        <span>Settings</span>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
        </svg>
      </div>

      <!-- Settings Submenu -->
      <div
        x-show="showSettings"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform -translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-2"
        class="mt-4 space-y-4 bg-gray-50 px-4 py-4">
        <!--<a href="../user/userMobile.php" class="flex items-center text-blue-900 hover:text-blue-700">
          <img src="../img/userMobile.svg" alt="Mobile" class="h-5 w-5 mr-3">
          Mobile
        </a>-->
        <a href="../user/userEmail.php" class="flex items-center text-blue-900 hover:text-blue-700">
          <img src="../img/userEmail.svg" alt="Email" class="h-5 w-5 mr-3">
          Email
        </a>
        <!--<a href="../user/userAccount.php" class="flex items-center text-blue-900 hover:text-blue-700">
          <img src="../img/userAccount.svg" alt="Account" class="h-5 w-5 mr-3">
          Account
        </a>-->
        <!--<a href="../user/userVerifyAccount.php" class="flex items-center text-blue-900 hover:text-blue-700">
          <img src="../img/userAccountVerify.svg" alt="Verify Account" class="h-5 w-5 mr-3">
          Submit NC2 File
        </a>-->
        <a href="../user/userChangePassword.php" class="flex items-center text-blue-900 hover:text-blue-700">
          <img src="../img/userChangePassword.svg" alt="Change Password" class="h-5 w-5 mr-3">
          Change Password
        </a>
        <!--
        <a href="#" class="flex items-center text-blue-900 hover:text-blue-700">
          <img src="../img/userNotifications.svg" alt="Notification Settings" class="h-5 w-5 mr-3">
          Notification Settings
        </a>
        <a href="#" class="flex items-center text-blue-900 hover:text-blue-700">
          <img src="../img/userJobAlerts.svg" alt="Job Alerts" class="h-5 w-5 mr-3">
          Job Alerts
        </a>
-->
      </div>
    </div>
  </nav>
</div>