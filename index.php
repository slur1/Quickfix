<?php
include 'config/db_connection.php';
$query = "SELECT * FROM categories";
$categoryresult = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home | Quickfix</title>
  <link rel="icon" type="logo" href="./img/logo1.png">
  <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');

    body {
      font-family: 'Montserrat', sans-serif;
    }

    html {
      scroll-behavior: smooth;
    }

    #loader-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background: white;
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 9999;
    }

    .loader-container {
      width: 30vw;
      max-width: 200px;
      height: 30vw;
      max-height: 200px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .logo {
      width: 90%;
      height: 90%;
      animation: heartbeat 1.5s infinite;
      object-fit: contain;
    }

    .text-container {
      height: 40px;
      width: 100%;
      text-align: center;
    }

    .letter {
      display: inline-block;
      font-size: 24px;
      font-weight: bold;
      color: #3498db;
      opacity: 0;
      animation: fadeIn 0.4s forwards;
      /* Faster fadeIn */
      animation-delay: calc(var(--index) * 0.15s);
      /* Faster delay between letters */
    }

    .fadeOut {
      animation: fadeOut 0.7s forwards;
    }

    @keyframes heartbeat {
      0% {
        transform: scale(1);
      }

      14% {
        transform: scale(1.2);
      }

      28% {
        transform: scale(1);
      }

      42% {
        transform: scale(1.2);
      }

      70% {
        transform: scale(1);
      }
    }

    @keyframes fadeIn {
      0% {
        opacity: 0;
        transform: translateY(10px);
      }

      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes fadeOut {
      0% {
        opacity: 1;
        transform: translateY(0);
      }

      100% {
        opacity: 0;
        transform: translateY(-10px);
      }
    }

    @media (max-width: 480px) {
      .loader-container {
        width: 80vw !important;
        height: auto;
      }

      .logo {
        width: 70%;
        height: 70%;
        margin-bottom: 30px;
      }

      .letter {
        font-size: 28px;
      }
    }
  </style>
</head>

<body class="bg-white min-h-screen">
  <?php include('loader.php'); ?>
  <!-- Navigation -->
  <div id="main-content" style="display:none;">
    <nav class="flex justify-between items-center p-4 bg-white shadow-md sticky top-0 z-50">
      <!-- Logo Section -->
      <a href="#" class="flex items-center space-x-2">
        <img src="./img/logo1.png" alt="Company Logo" class="h-12 w-auto">
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
          <a href="#download" class="block lg:inline text-blue-600 hover:text-blue-800 font-medium py-2 px-4">Download</a>
          <!--
        <a href="./user/how_it_works.php" class="block lg:inline text-blue-600 hover:text-blue-800 font-medium py-2 px-4">How it Works</a>-->
          <!-- Categories Dropdown -->
          <!--
        <div class="relative" @click.away="categoryOpen = false">
          <button @click="categoryOpen = !categoryOpen"
            class="flex items-center text-blue-600 hover:text-blue-800 font-medium py-2 px-4">
            Categories
            <svg class="w-4 h-4 ml-1" :class="{'transform rotate-180': categoryOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
          </button>-->
          <!-- Categories Menu -->
          <!--
        <div x-show="categoryOpen"
          x-transition:enter="transition ease-out duration-100"
          x-transition:enter-start="transform opacity-0 scale-95"
          x-transition:enter-end="transform opacity-100 scale-100"
          x-transition:leave="transition ease-in duration-75"
          x-transition:leave-start="transform opacity-100 scale-100"
          x-transition:leave-end="transform opacity-0 scale-95"
          class="absolute left-0 mt-2 w-screen max-w-md bg-white rounded-md shadow-lg z-50">
  -->
          <!-- User Type Selection -->
          <!--
        <div class="p-4 border-b">
          <div class="flex space-x-4">
            <button @click="userType = 'tasker'"
              :class="{'bg-blue-50': userType === 'tasker'}"
              class="flex-1 p-3 text-left rounded-lg hover:bg-blue-50">
              <div class="font-semibold text-blue-900">As a Job Seeker</div>
              <div class="text-sm text-gray-600">I'm looking for job as...</div>
            </button>
            <button @click="userType = 'poster'"
              :class="{'bg-blue-50': userType === 'poster'}"
              class="flex-1 p-3 text-left rounded-lg hover:bg-blue-50">
              <div class="font-semibold text-blue-900">As a Job Provider</div>
              <div class="text-sm text-gray-600">I'm looking to hire someone for...</div>
            </button>
          </div>
        </div>
  -->
          <!-- Categories Grid -->
          <!--
        <div class="max-h-96 overflow-y-auto p-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <template x-if="userType === 'tasker'">
  -->
          <!-- Job Seeker Categories -->
          <!--
        <div>
          <h3 class="font-semibold text-lg text-blue-900 mb-2">Home Cleaning</h3>
          <div class="grid grid-cols-1 gap-2">
            <template x-for="category in [
                    { name: 'House Cleaner', url: '../public/user/maintenance.php' },
                    { name: 'Carpet Cleaner', url: '../public/user/maintenance.php' },
                    { name: 'Aircon Cleaner', url: '../public/user/maintenance.php' },
                    { name: 'Laundry Helper', url: '../public/user/maintenance.php' },
                    { name: 'Upholstery Cleaner', url: '../public/user/maintenance.php' }
                  ]" :key="category.name">
              <a :href="category.url"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 rounded"
                x-text="category.name"></a>
            </template>
          </div>
          <h3 class="font-semibold text-lg text-blue-900 mt-4 mb-2">House Repair</h3>
          <div class="grid grid-cols-1 gap-2">
            <template x-for="category in [
                    { name: 'Electrical Repair', url: '../public/user/maintenance.php' },
                    { name: 'Lighting Repair', url: '../public/user/maintenance.php' },
                    { name: 'Wiring Repair', url: '../public/user/maintenance.php' },
                    { name: 'Appliance Repair', url: '../public/user/maintenance.php' },
                    { name: 'Furniture Repair', url: '../public/user/maintenance.php' }
                  ]" :key="category.name">
              <a :href="category.url"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 rounded"
                x-text="category.name"></a>
            </template>
          </div>
        </div>
        </template>
        <template x-if="userType === 'poster'">
            -->
          <!-- Job Provider Categories -->
          <!--
        <div>
          <h3 class="font-semibold text-lg text-blue-900 mb-2">Home Cleaning Services</h3>
          <div class="grid grid-cols-1 gap-2">
            <template x-for="category in [
                    { name: 'House Cleaning', url: '../public/user/maintenance.php' },
                    { name: 'Carpet Cleaning', url: '../public/user/maintenance.php' },
                    { name: 'Upholstery Cleaning', url: '../public/user/maintenance.php' },
                    { name: 'Laundry Service', url: '../public/user/maintenance.php' },
                    { name: 'Aircon Cleaning', url: '../public/user/maintenance.php' }
                  ]" :key="category.name">
              <a :href="category.url"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 rounded"
                x-text="category.name"></a>
            </template>
          </div>
          <h3 class="font-semibold text-lg text-blue-900 mt-4 mb-2">Home Repair Services</h3>
          <div class="grid grid-cols-1 gap-2">
            <template x-for="category in [
                    { name: 'Electrical Repair', url: '../public/user/maintenance.php' },
                    { name: 'Lighting Repair', url: '../public/user/maintenance.php' },
                    { name: 'Wiring Repair', url: '../public/user/maintenance.php' },
                    { name: 'Appliance Repair', url: '../public/user/maintenance.php' },
                    { name: 'Furniture Repair', url: '../public/user/maintenance.php' }
                  ]" :key="category.name">
              <a :href="category.url"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 rounded"
                x-text="category.name"></a>
            </template>
          </div>
        </div>
        </template>
      </div>
    </div>
    </div>
    </div>
    </div>
            -->

          <!-- Buttons Section -->
          <div class="flex justify-center space-x-4 py-4 lg:py-0">
            <a href="./user/userLogin.php"><button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Sign In</button></a>
            <a href="./job-seeker/become-a-job-seeker.php"><button class="px-4 py-2 bg-blue-800 text-white rounded-full hover:bg-blue-900">Join Us</button></a>
          </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative overflow-hidden">


      <!-- Main content -->
      <div class="container mx-auto px-4 py-12 relative z-10">
        <!-- Fancy Decorations: Left -->
        <div class="absolute top-0 left-0 w-48 h-48 bg-blue-200 rounded-full blur-3xl opacity-50 -z-10"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-blue-400 rounded-full blur-2xl opacity-30 -z-10"></div>

        <!-- Fancy Decorations: Right -->
        <div class="absolute top-10 right-0 w-40 h-40 bg-blue-300 rounded-full blur-3xl opacity-50 -z-10"></div>
        <div class="absolute bottom-10 right-0 w-28 h-28 bg-blue-500 rounded-full blur-2xl opacity-30 -z-10"></div>
      </div>


      <!-- Heading -->
      <div class="text-center mb-12">
        <h1 class="text-5xl font-bold text-blue-800 mb-2">Find trusted help</h1>
        <h1 class="text-5xl font-bold text-blue-800">for home tasks</h1>
      </div>

      <!-- Search bar -->
      <div class="max-w-3xl mx-auto mb-16 relative">
        <input type="text" placeholder="What do you need help with?" class="w-full px-6 py-4 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button class="absolute right-0 top-0 h-full bg-blue-700 text-white px-6 rounded-r-full hover:bg-blue-800 transition">
          <i class="fas fa-search"></i>
        </button>
      </div>

      <!-- Service categories -->
      <div class="flex flex-col items-center max-w-5xl mx-auto mb-8">
        <div class="flex justify-center gap-8">
          <!-- Cleaning -->
          <div class="flex flex-col items-center cursor-pointer relative pb-2 w-20 service-category" data-category="cleaning">
            <div class="w-14 h-14 rounded-full flex items-center justify-center text-2xl font-bold transition-all duration-300 ease-in-out bg-gray-100 text-gray-600 hover:bg-blue-100 hover:text-blue-800">
              <i class="fas fa-broom"></i>
            </div>
            <div class="text-center mt-2 text-gray-600 hover:text-blue-800">Cleaning</div>
            <div class="absolute bottom-0 w-0 h-0.5 bg-blue-800 transition-all duration-300 ease-in-out highlight"></div>
          </div>

          <!-- Repair -->
          <div class="flex flex-col items-center cursor-pointer relative pb-2 w-20 service-category" data-category="repair">
            <div class="w-14 h-14 rounded-full flex items-center justify-center text-2xl transition-all duration-300 ease-in-out bg-gray-100 text-gray-600 hover:bg-blue-100 hover:text-blue-800">
              <i class="fas fa-tools"></i>
            </div>
            <div class="text-center mt-2 text-gray-600 hover:text-blue-800">Repair</div>
            <div class="absolute bottom-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 ease-in-out highlight"></div>
          </div>
        </div>

        <!-- Cleaning Subcategories -->
        <div id="cleaning-subcategories" class="hidden mt-4 flex-wrap justify-center gap-3">
          <a href="laundry.php" class="px-4 py-2 bg-white rounded-full text-black hover:bg-blue-800 hover:text-white border border-gray-300 inline-block text-center">
            Laundry
          </a>

          <button class="px-4 py-2 bg-white rounded-full text-black hover:bg-blue-800 hover:text-white border border-gray-300">Upholstery Cleaning</button>
          <button class="px-4 py-2 bg-white rounded-full text-black hover:bg-blue-800 hover:text-white border border-gray-300">Regular Cleaning</button>
          <button class="px-4 py-2 bg-white rounded-full text-black hover:bg-blue-800 hover:text-white border border-gray-300">Deep Cleaning</button>
          <button class="px-4 py-2 bg-white rounded-full text-black hover:bg-blue-800 hover:text-white border border-gray-300">Carpet Cleaning</button>
          <button class="px-4 py-2 bg-white rounded-full text-black hover:bg-blue-800 hover:text-white border border-gray-300">Aircon Cleaning</button>
        </div>

        <!-- Repair Subcategories -->
        <div id="repair-subcategories" class="hidden mt-4 flex-wrap justify-center gap-3">
          <button class="px-4 py-2 bg-white rounded-full text-black hover:bg-blue-800 hover:text-white border border-gray-300">Electrical Repair</button>
          <button class="px-4 py-2 bg-white rounded-full text-black hover:bg-blue-800 hover:text-white border border-gray-300">Lighting Repair</button>
          <button class="px-4 py-2 bg-white rounded-full text-black hover:bg-blue-800 hover:text-white border border-gray-300">Wiring Repair</button>
          <button class="px-4 py-2 bg-white rounded-full text-black hover:bg-blue-800 hover:text-white border border-gray-300">Appliance Repair</button>
          <button class="px-4 py-2 bg-white rounded-full text-black hover:bg-blue-800 hover:text-white border border-gray-300">Furniture Repair</button>
        </div>
      </div>


      <!-- Image display area -->
      <div class="max-w-6xl mx-auto mt-12 mb-8">
        <div id="category-image" class="w-full h-96 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
          <img src="./img/cleaning-service.svg"
            alt="IMG"
            class="w-full h-full object-cover transition-opacity duration-300 ease-in-out">
        </div>
      </div>

    </div>
  </div>



  <!-- Post Your First Job Section -->
  <section class="py-20 px-4 relative overflow-hidden max-w-screen-xl mx-auto">
    <!-- Fancy Decorations: Left -->
    <div class="absolute -top-10 left-0 lg:-left-10 w-36 h-36 bg-blue-200 rounded-full blur-3xl opacity-50 -z-10 lg:block hidden"></div>
    <div class="absolute top-1/3 left-4 lg:-left-16 w-28 h-28 bg-blue-400 rounded-full blur-2xl opacity-30 -z-10 lg:block hidden"></div>

    <!-- Fancy Decorations: Right -->
    <div class="absolute -top-16 right-0 lg:-right-8 w-32 h-32 bg-blue-300 rounded-full blur-3xl opacity-50 -z-10 lg:block hidden"></div>
    <div class="absolute bottom-5 right-4 lg:-right-14 w-40 h-40 bg-blue-500 rounded-full blur-2xl opacity-30 -z-10 lg:block hidden"></div>

    <div class="max-w-4xl mx-auto text-center relative">
      <h2 class="text-4xl font-bold text-blue-900 mb-8">Post your first Job in seconds</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-gray-100 p-6 rounded-lg">
          <h3 class="font-semibold mb-2">Describe your Job</h3>
          <p>Tell us what you need to be done, when and where it works for you.</p>
        </div>
        <div class="bg-gray-100 p-6 rounded-lg">
          <h3 class="font-semibold mb-2">Choose your budget</h3>
          <p>Tell us how much you're willing to pay. You'll get matched with experts who fit your budget.</p>
        </div>
        <div class="bg-gray-100 p-6 rounded-lg">
          <h3 class="font-semibold mb-2">Review offers & chat</h3>
          <p>Compare offers, reviews, and profiles. Message experts to discuss details.</p>
        </div>
      </div>
      <button class="mt-10 px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700" onclick="window.location.href='./user/userLogin.php'">
        Post a Job Now!
      </button>
    </div>
  </section>



  <!-- Popular Jobs Section -->
  <!--
<section class="bg-gray-50 py-20 px-4 relative overflow-hidden max-w-screen-xl mx-auto">

  <div class="absolute top-5 left-0 lg:-left-10 w-36 h-36 bg-blue-200 rounded-full blur-3xl opacity-50 -z-10 lg:block hidden"></div>
  <div class="absolute bottom-10 left-4 lg:-left-16 w-28 h-28 bg-blue-400 rounded-full blur-2xl opacity-30 -z-10 lg:block hidden"></div>


  <div class="absolute top-16 right-0 lg:-right-8 w-32 h-32 bg-blue-300 rounded-full blur-3xl opacity-50 -z-10 lg:block hidden"></div>
  <div class="absolute bottom-5 right-4 lg:-right-14 w-40 h-40 bg-blue-500 rounded-full blur-2xl opacity-30 -z-10 lg:block hidden"></div>

  <div class="max-w-6xl mx-auto relative">
    <h2 class="text-4xl font-bold mb-12 text-blue-900 text-center">Popular Jobs</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

      <?php while ($row = $categoryresult->fetch_assoc()) { ?>
        <a href="categories.php?category_id=<?= $row['id'] ?>">
          <div class="bg-white rounded-lg shadow-md overflow-hidden transform transition-transform duration-300 hover:scale-105">
            <img src="./img/laundry.svg" alt="Cleaning" class="w-full h-48 object-cover">
            <div class="p-4">
              <h3 class="font-semibold text-lg mb-2"><?= $row['name'] ?></h3>
            </div>
          </div>
        </a>
      <?php } ?>
    </div>
  </div>
</section>
-->



  </div>


  <!-- Testimonials Section -->
  <section class="py-20 px-4 bg-gray-50">
    <div class="max-w-4xl mx-auto">
      <h2 class="text-4xl font-bold mb-12 text-blue-900 text-center">What our users are saying</h2>
      <div class="relative overflow-hidden">
        <div id="testimonials-slider" class="flex transition-transform duration-500 ease-in-out">
          <div class="w-full flex-shrink-0 bg-white p-6 rounded-lg shadow-lg">
            <div class="flex items-center mb-4">
              <img src="https://via.placeholder.com/60" alt="User 1" class="w-12 h-12 rounded-full mr-4">
              <div>
                <p class="font-semibold">Edge P.</p>
                <p class="text-sm text-gray-500">Homeowner</p>
              </div>
            </div>
            <p class="text-gray-600 mb-4">"QuickFix saved me so much time! I needed someone reliable to fix a leaky pipe, and within hours, I had an experienced
              handyman at my door. The work was professional, affordable, and hassle-free. Highly recommend!"</p>
            <div class="flex items-center">
              <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
              </svg>
              <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
              </svg>
              <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
              </svg>
              <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
              </svg>
              <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
              </svg>
            </div>
          </div>
          <div class="w-full flex-shrink-0 bg-white p-6 rounded-lg shadow-lg">
            <div class="flex items-center mb-4">
              <img src="https://via.placeholder.com/60" alt="User 2" class="w-12 h-12 rounded-full mr-4">
              <div>
                <p class="font-semibold">Cy </p>
                <p class="text-sm text-gray-500">Busy Professional</p>
              </div>
            </div>
            <p class="text-gray-600 mb-4">Finding someone to clean my apartment was so easy with QuickFix. The person who came was thorough, friendly, and left
              my place spotless. I'll definitely use this service again for my cleaning needs!"</p>
            <div class="flex items-center">
              <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
              </svg>
              <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
              </svg>
              <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
              </svg>
              <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
              </svg>
              <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
              </svg>
            </div>
          </div>
          <div class="w-full flex-shrink-0 bg-white p-6 rounded-lg shadow-lg">
            <div class="flex items-center mb-4">
              <img src="https://via.placeholder.com/60" alt="User 3" class="w-12 h-12 rounded-full mr-4">
              <div>
                <p class="font-semibold">Aubrey D.G</p>
                <p class="text-sm text-gray-500">Freelance Worker</p>
              </div>
            </div>
            <p class="text-gray-600 mb-4">"As a busy mom, I don't have time for house cleaning. I found a reliable cleaner through this platform who now comes weekly. It's been a game-changer for my family!"</p>
            <div class="flex items-center">
              <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
              </svg>
              <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
              </svg>
              <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
              </svg>
              <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
              </svg>
              <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
              </svg>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Be Your Own Boss Section -->
  <!--
  <section class="bg-blue-50 text-white py-20 px-4">
    <div class="max-w-5xl mx-auto text-center">
      <h2 class="text-4xl font-bold text-blue-900 mb-6">Be Your Own Boss</h2>
      <p class="text-lg mb-10 text-gray-600">Join thousands of experts earning money by helping others. Your skills, your schedule, your income.</p>

      <div class="flex flex-col md:flex-row justify-center items-center gap-8">
        <div class="bg-white text-blue-900 p-8 rounded-lg shadow-lg transform hover:scale-105 transition-transform">
          <p class="text-3xl font-bold mb-2">₱22,179</p>
          <p class="mb-4 text-lg font-medium">Earned by <span class="font-bold">Je G.</span> this month</p>
          <p class="text-sm mb-6">Tasks completed: House Repair</p>
          <img src="./img/boss.svg" alt="Testimonial 1" class="rounded-lg mx-auto w-3/4">
        </div>

        <div class="bg-white text-blue-900 p-8 rounded-lg shadow-lg transform hover:scale-105 transition-transform">
          <p class="text-3xl font-bold mb-2">₱32,450</p>
          <p class="mb-4 text-lg font-medium">Earned by <span class="font-bold">Je C.</span> this month</p>
          <p class="text-sm mb-6">Tasks completed: Home cleaning</p>
          <img src="./img/boss.svg" alt="Testimonial 2" class="rounded-lg mx-auto w-3/4">
        </div>
      </div>

      <div class="mt-10">
        <button class="px-8 py-3 bg-blue-800 text-white rounded-md shadow-lg hover:bg-blue-900 transition-colors">Start Earning Now</button>
      </div>
    </div>
  </section>
  -->



  <!-- FAQ Section (replacing Articles Section) -->
  <section class="py-20 px-4">
    <div class="max-w-4xl mx-auto">
      <h2 class="text-4xl font-bold mb-8 text-center">Frequently Asked Questions</h2>
      <div class="space-y-4">
        <div class="border border-gray-200 rounded-lg">
          <button class="flex justify-between items-center w-full px-4 py-4 text-left text-lg font-semibold" onclick="toggleFAQ(this)">
            <span>How does the platform work?</span>
            <svg class="w-6 h-6 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
          </button>
          <div class="hidden px-4 pb-4">
            <p class="text-gray-700">Our platform connects people who need jobs done with skilled professionals. Simply post a job, receive offers from experts, choose the best fit, and get your job completed efficiently.</p>
          </div>
        </div>
        <div class="border border-gray-200 rounded-lg">
          <button class="flex justify-between items-center w-full px-4 py-4 text-left text-lg font-semibold" onclick="toggleFAQ(this)">
            <span>How much does it cost to post a Job?</span>
            <svg class="w-6 h-6 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
          </button>
          <div class="hidden px-4 pb-4">
            <p class="text-gray-700">Posting a job is completely free. You only pay when you accept an offer from a job provider and the job is completed to your satisfaction.</p>
          </div>
        </div>
        <div class="border border-gray-200 rounded-lg">
          <button class="flex justify-between items-center w-full px-4 py-4 text-left text-lg font-semibold" onclick="toggleFAQ(this)">
            <span>How do I become a job provider?</span>
            <svg class="w-6 h-6 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
          </button>
          <div class="hidden px-4 pb-4">
            <p class="text-gray-700">To become a job provider, simply sign up for an account, complete your profile highlighting your skills and experience, and start bidding on jobs that match your expertise.</p>
          </div>
        </div>
        <div class="border border-gray-200 rounded-lg">
          <button class="flex justify-between items-center w-full px-4 py-4 text-left text-lg font-semibold" onclick="toggleFAQ(this)">
            <span>Is my payment secure?</span>
            <svg class="w-6 h-6 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
          </button>
          <div class="hidden px-4 pb-4">
            <p class="text-gray-700">Yes, all payments are processed through our secure payment platform. Funds are held safely until the task is completed and you're satisfied with the work.</p>
          </div>
        </div>
        <div class="border border-gray-200 rounded-lg">
          <button class="flex justify-between items-center w-full px-4 py-4 text-left text-lg font-semibold" onclick="toggleFAQ(this)">
            <span>What if I'm not satisfied with the work?</span>
            <svg class="w-6 h-6 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
          </button>
          <div class="hidden px-4 pb-4">
            <p class="text-gray-700">If you're not satisfied with the work, you can communicate with the job provider to address any issues. If the problem persists, our customer support team is always available to help resolve any disputes.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Download Section -->
  <section id="download" class="py-16 px-4 bg-gray-100">
    <div class="max-w-4xl mx-auto text-center">
      <h2 class="text-3xl font-bold mb-6">Download Our App</h2>
      <p class="text-lg mb-8">Get our app on your Android device for the best experience.</p>

      <div class="flex justify-center">
        <a href="https://quickfixph.online/download/quickfix.apk" download class="inline-flex items-center px-8 py-4 bg-blue-800 text-white font-semibold rounded-lg shadow-lg hover:bg-blue-700 transition duration-300 transform hover:scale-105">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
          </svg>
          Download APK
        </a>
      </div>

      <p class="mt-6 text-sm text-gray-600">Version 1.0.0</p>
    </div>
  </section>

  <!--Footer-->
  <footer class="bg-gray-100">
    <div class="container mx-auto px-8">
      <div class="w-full flex flex-col md:flex-row py-6">
        <div class="flex-1 mb-6 text-blue-900">
          <a class="no-underline hover:no-underline flex items-center font-bold text-1xl lg:text-2xl" href="#">
            <img src="./img/logo1.png" alt="Quickfix Logo" class="h-10 lg:h-14">
            <span>Quickfix</span>
          </a>
        </div>
        <div class="flex-1">
          <p class="uppercase text-gray-500 md:mb-6">Links</p>
          <ul class="list-reset mb-6">
            <li class="mt-2 inline-block mr-2 md:block md:mr-0">
              <a href="#" class="no-underline hover:underline text-gray-800 hover:text-blue-500">FAQ</a>
            </li>
            <li class="mt-2 inline-block mr-2 md:block md:mr-0">
              <a href="#" class="no-underline hover:underline text-gray-800 hover:text-blue-500">Help</a>
            </li>
            <li class="mt-2 inline-block mr-2 md:block md:mr-0">
              <a href="#" class="no-underline hover:underline text-gray-800 hover:text-blue-500">Support</a>
            </li>
          </ul>
        </div>
        <div class="flex-1">
          <p class="uppercase text-gray-500 md:mb-6">Legal</p>
          <ul class="list-reset mb-6">
            <li class="mt-2 inline-block mr-2 md:block md:mr-0">
              <a href="#" class="no-underline hover:underline text-gray-800 hover:text-blue-500">Terms</a>
            </li>
            <li class="mt-2 inline-block mr-2 md:block md:mr-0">
              <a href="#" class="no-underline hover:underline text-gray-800 hover:text-blue-500">Privacy</a>
            </li>
          </ul>
        </div>
        <div class="flex-1">
          <p class="uppercase text-gray-500 md:mb-6">Social</p>
          <ul class="list-reset mb-6">
            <li class="mt-2 inline-block mr-2 md:block md:mr-0">
              <a href="#" class="no-underline hover:underline text-gray-800 hover:text-blue-500">Facebook</a>
            </li>
            <li class="mt-2 inline-block mr-2 md:block md:mr-0">
              <a href="#" class="no-underline hover:underline text-gray-800 hover:text-blue-500">Linkedin</a>
            </li>
            <li class="mt-2 inline-block mr-2 md:block md:mr-0">
              <a href="#" class="no-underline hover:underline text-gray-800 hover:text-blue-500">Twitter</a>
            </li>
          </ul>
        </div>
        <div class="flex-1">
          <p class="uppercase text-gray-500 md:mb-6">Company</p>
          <ul class="list-reset mb-6">
            <li class="mt-2 inline-block mr-2 md:block md:mr-0">
              <a href="#" class="no-underline hover:underline text-gray-800 hover:text-blue-500">Official Blog</a>
            </li>
            <li class="mt-2 inline-block mr-2 md:block md:mr-0">
              <a href="#" class="no-underline hover:underline text-gray-800 hover:text-blue-500">About Us</a>
            </li>
            <li class="mt-2 inline-block mr-2 md:block md:mr-0">
              <a href="#" class="no-underline hover:underline text-gray-800 hover:text-blue-500">Contact</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </footer>


  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const categories = document.querySelectorAll('.service-category');
      const categoryImage = document.getElementById('category-image').querySelector('img');



      // Image URLs for each category
      const categoryImages = {
        cleaning: './img/cleaning-service.png',
        repair: './img/electric-help.svg'
      };

      categories.forEach(category => {
        category.addEventListener('click', function() {
          const categoryName = this.getAttribute('data-category');

          // Remove highlight from all categories
          categories.forEach(cat => {
            const iconContainer = cat.querySelector('div:first-child'); // Icon background
            iconContainer.classList.remove('bg-blue-100', 'text-blue-600');
            iconContainer.classList.add('bg-gray-100', 'text-gray-600');

            const textElement = cat.querySelector('div:nth-child(2)'); // Text
            textElement.classList.remove('text-blue-800', 'font-medium');
            textElement.classList.add('text-gray-600');

            const highlight = cat.querySelector('.highlight'); // Underline highlight
            highlight.classList.remove('w-full', 'opacity-100');
            highlight.classList.add('w-0', 'opacity-0');
          });

          // Add highlight to the clicked category
          const iconContainer = this.querySelector('div:first-child');
          iconContainer.classList.remove('bg-gray-100', 'text-gray-600');
          iconContainer.classList.add('bg-blue-100', 'text-blue-600');

          const textElement = this.querySelector('div:nth-child(2)');
          textElement.classList.remove('text-gray-600');
          textElement.classList.add('text-blue-800', 'font-medium');

          const highlight = this.querySelector('.highlight');
          highlight.classList.remove('w-0', 'opacity-0');
          highlight.classList.add('w-full', 'opacity-100');

          // Toggle subcategories visibility
          document.getElementById('cleaning-subcategories').classList.toggle('hidden', categoryName !== 'cleaning');
          document.getElementById('repair-subcategories').classList.toggle('hidden', categoryName !== 'repair');

          // Update image smoothly
          if (categoryImages[categoryName]) {
            categoryImage.style.opacity = '0';
            setTimeout(() => {
              categoryImage.src = categoryImages[categoryName];
              categoryImage.style.opacity = '1';
            }, 300);
          }
        });
      });

      categoryImage.style.transition = 'opacity 0.3s ease';
      categoryImage.style.opacity = '1';
    });
  </script>

  <script>
    function resetAnimation() {
      const letters = document.querySelectorAll('.letter');
      letters.forEach(letter => letter.classList.add('fadeOut'));
      setTimeout(() => {
        letters.forEach(letter => {
          letter.classList.remove('fadeOut');
          letter.style.opacity = 0;
          void letter.offsetWidth;
          letter.style.animation = 'none';
          setTimeout(() => {
            letter.style.animation = '';
          }, 10);
        });
      }, 800);
    }

    // Adjust totalFadeInTime due to faster animation
    const totalFadeInTime = 0.15 * 8 + 0.4; // = 1.6s approx
    const displayTime = 1.2; // optional tweak
    const cycleTime = (totalFadeInTime + displayTime + 0.8) * 1000; // ~3.6s
    setInterval(() => resetAnimation(), cycleTime);

    // Show main content after loader
    window.addEventListener('load', () => {
      setTimeout(() => {
        document.getElementById('loader-overlay').style.display = 'none';
        document.getElementById('main-content').style.display = 'block';
      }, 2000); // Show loader for 3 seconds
    });
  </script>

  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <script>
    const navToggle = document.getElementById("nav-toggle");
    const navContent = document.getElementById("nav-content");

    navToggle.addEventListener("click", () => {
      navContent.classList.toggle("hidden");
    });

    document.addEventListener("click", (e) => {
      if (!navContent.contains(e.target) && !navToggle.contains(e.target)) {
        navContent.classList.add("hidden");
      }
    });
  </script>



  <script>
    const words = ["Cleaning", "Repair", "Inspection"];
    let i = 0;
    let j = 0;
    let currentWord = "";
    let isDeleting = false;

    function type() {
      currentWord = words[i];

      if (isDeleting) {
        document.getElementById("typewriter").textContent = currentWord.substring(0, j - 1);
        j--;
        if (j == 0) {
          isDeleting = false;
          i++;
          if (i == words.length) {
            i = 0;
          }
        }
      } else {
        document.getElementById("typewriter").textContent = currentWord.substring(0, j + 1);
        j++;
        if (j == currentWord.length) {
          isDeleting = true;
        }
      }

      // Adjust the typing and deleting speed here if needed
      setTimeout(type, isDeleting ? 250 : 100);
    }

    type();
  </script>

  <script>
    function toggleFAQ(element) {
      const content = element.nextElementSibling;
      const icon = element.querySelector('svg');

      content.classList.toggle('hidden');
      icon.classList.toggle('rotate-180');
    }
  </script>

  <script>
    const slider = document.querySelector('#testimonials-slider');
    const slides = slider.children;
    let currentIndex = 0;

    function updateSlider() {
      slider.style.transform = `translateX(-${currentIndex * 100}%)`;
    }

    function nextSlide() {
      currentIndex = (currentIndex + 1) % slides.length;
      updateSlider();
    }

    // Auto-rotate every 5 seconds
    setInterval(nextSlide, 3000);
  </script>
</body>

</html>