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
  <title>Payments History</title>
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

<?php include './userHeader.php'; ?>

<body class="bg-gray-50" x-data="{ 
    activeTab: 'earned',
    showDropdown: false,
    selectedFilter: 'All', // Default is 'All'
    selectedRange: '', // Default is empty
    fromDate: '',
    toDate: '',
}">
  <div class="flex min-h-screen">
    <aside class="w-64 bg-white p-6 shadow-sm">
      <?php include './userSideBar.php'; ?>
    </aside>
    <main class="p-8 flex-1">
      <h1 class="text-3xl font-bold text-blue-900 mb-8">Payments History</h1>

      <!-- Tabs -->
      <div class="border-b border-gray-200 mb-6">
        <div class="flex space-x-8">
          <button
            @click="activeTab = 'earned'"
            :class="{'text-blue-600 border-b-2 border-blue-600': activeTab === 'earned', 'text-gray-500 hover:text-gray-700': activeTab !== 'earned'}"
            class="py-4 px-2 font-medium">
            Earned
          </button>
          <button
            @click="activeTab = 'outgoing'"
            :class="{'text-blue-600 border-b-2 border-blue-600': activeTab === 'outgoing', 'text-gray-500 hover:text-gray-700': activeTab !== 'outgoing'}"
            class="py-4 px-2 font-medium">
            Outgoing
          </button>
        </div>
      </div>

      <div class="flex justify-between items-center mb-6">
        <!-- Filter Dropdown -->
        <div class="relative" @click.away="showDropdown = false">
          <label class="block text-sm font-medium text-gray-700 mb-2">Showing</label>
          <button
            @click="showDropdown = !showDropdown"
            class="bg-gray-50 border border-gray-300 rounded-md py-2 px-4 flex items-center space-x-2">
            <span x-text="selectedFilter"></span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <!-- Dropdown Menu -->
          <div
            x-show="showDropdown"
            class="absolute mt-1 w-full bg-white rounded-md shadow-lg z-10">
            <div class="py-1">
              <button
                @click="selectedFilter = 'All'; selectedRange = ''; showDropdown = false"
                class="block w-full text-left px-4 py-2 hover:bg-gray-100">
                All
              </button>
              <button
                @click="selectedFilter = 'Range'; selectedRange = 'Range'; showDropdown = false"
                class="block w-full text-left px-4 py-2 hover:bg-gray-100">
                Range
              </button>
            </div>
          </div>
        </div>

        <!-- Net Amount -->
        <div class="text-right">
          <span class="block text-sm font-medium text-gray-700 mb-1" x-text="activeTab === 'earned' ? 'Net earned' : 'Net outgoing'"></span>
          <span class="text-2xl font-bold text-blue-600">₱0.00</span>
        </div>
      </div>

      <!-- Date Range Section -->
      <template x-if="selectedRange === 'Range'">
        <div class="flex gap-4 mb-4">
          <div class="flex-1">
            <label class="block text-sm font-medium mb-2">From</label>
            <input
              type="date"
              x-model="fromDate"
              class="w-full bg-gray-50 px-4 py-2 rounded-md border border-gray-200">
          </div>
          <div class="flex-1">
            <label class="block text-sm font-medium mb-2">To</label>
            <input
              type="date"
              x-model="toDate"
              class="w-full bg-gray-50 px-4 py-2 rounded-md border border-gray-200">
          </div>
        </div>
      </template>
    </main>
  </div>
</body>