<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> Messages | QuickFix</title>
  <link rel="icon" type="logo" href="../img/logo1.png">
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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


<body class="bg-white">
  <div x-data="{ activeTab: 'all' }" class="max-w-4xl mx-auto px-4 py-8">
    <!-- Navigation tabs -->
    <nav class="border-b border-gray-200 mb-12">
      <div class="flex space-x-8">
        <button
          @click="activeTab = 'all'"
          :class="{ 'text-blue-500 border-b-2 border-blue-500': activeTab === 'all', 'text-gray-500 hover:text-gray-700': activeTab !== 'all' }"
          class="py-4 text-sm font-medium">
          All Messages
        </button>
        <button
          @click="activeTab = 'unread'"
          :class="{ 'text-blue-500 border-b-2 border-blue-500': activeTab === 'unread', 'text-gray-500 hover:text-gray-700': activeTab !== 'unread' }"
          class="py-4 text-sm font-medium">
          Unread Only
        </button>
      </div>
    </nav>

    <!-- Empty state -->
    <div class="text-center">
      <!-- Mailbox SVG illustration -->
      <svg class="w-24 h-24 mx-auto mb-6" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect x="20" y="40" width="60" height="40" fill="#2563EB" rx="4" />
        <path d="M20 50l30 15 30-15" stroke="#1E40AF" stroke-width="2" />
        <rect x="35" y="30" width="30" height="10" fill="#2563EB" />
        <path d="M45 25C45 20 50 15 55 20" stroke="#1E40AF" stroke-width="2" />
        <circle cx="58" cy="18" r="2" fill="#1E40AF" />
      </svg>

      <!-- Empty state message -->
      <p class="text-gray-600 text-lg max-w-md mx-auto">
        You haven't got any messages yet - assign a job or get assigned to chat privately!
      </p>
    </div>
  </div>
</body>

</html>