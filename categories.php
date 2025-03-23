<?php
include 'config/db_connection.php';

$category_id = $_GET['category_id'];
$query = "SELECT * FROM jobs WHERE category_id = $category_id";
$jobsresult = $conn->query($query);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home | Quickfix</title>
  <link rel="icon" type="logo" href="./img/logo1.png">
  <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css" />
  <script src="https://kit.fontawesome.com/1f8a28cba3.js" crossorigin="anonymous"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');

    body {
      font-family: 'Montserrat', sans-serif;
    }

    html {
      scroll-behavior: smooth;
    }
  </style>
</head>

<body class="bg-white min-h-screen">
  <!-- Navigation -->
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


      <!-- Buttons Section -->
      <div class="flex justify-center space-x-4 py-4 lg:py-0">
        <a href="./user/user-registration.php"><button class="px-4 py-2 text-blue-600 hover:bg-blue-50 rounded-md">Sign Up</button></a>
        <a href="./user/userLogin.php"><button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Sign In</button></a>
        <a href="./job-seeker/become-a-job-seeker.php"><button class="px-4 py-2 bg-blue-800 text-white rounded-full hover:bg-blue-900">Join Us</button></a>
      </div>
    </div>
  </nav>

  <!-- Popular Jobs -->
  <section class="bg-gray-50 py-20 px-4">
    <div class="max-w-6xl mx-auto">
      <h2 class="text-4xl font-bold mb-12 text-blue-900 text-center">Popular Jobs</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <!-- Job 1 -->
        <?php while ($row = $jobsresult->fetch_assoc()) { ?>
          <a href="categories.php?category_id=<?= $row['id'] ?>">
            <div class="bg-white rounded-lg shadow-md overflow-hidden transform transition-transform duration-300 hover:scale-105">
              <img src="./img/laundry.svg" alt="Cleaning" class="w-full h-48 object-cover">
              <div class="p-4">
                <h3 class="font-semibold text-lg mb-2"><?= htmlspecialchars($row['job_title']) ?></h3>
                <p class="text-gray-600 text-sm">Job starting at <?= htmlspecialchars($row['budget']) ?></p>

                <?php
                $query2 = "SELECT AVG(rating) as avg_rating FROM completed_jobs WHERE job_id = '" . $row['id'] . "'";
                $completed_jobsresult = $conn->query($query2);
                $row2 = $completed_jobsresult->fetch_assoc();
                $rating = $row2['avg_rating'] ? number_format($row2['avg_rating'], 1) : "No ratings yet";
                ?>
                <div class="text-right mt-3">
                  <b><span class="text-yellow-500"><i class="fas fa-star"></i></span> <?= $rating ?></b>
                </div>
              </div>
            </div>
          </a>
        <?php } ?>
      </div>
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