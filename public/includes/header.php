<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Quickfix</title>
  <link rel="icon" type="logo" href="./img/logo1.png">
  <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
    rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
  <link rel="stylesheet" href="https://unpkg.com/@themesberg/flowbite@1.2.0/dist/flowbite.min.css" />
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>


  <style>

    html {
  scroll-behavior: smooth;
}
    .gradient {
  background: linear-gradient(90deg, #cbd5e1 0%, #4c51bf 100%);
    }
  .swiper-wrapper {
          height: fit-content !important;
          width: 100% !important;
      }
      .swiper-pagination{
          display: flex;
          align-items: center;
          justify-content: start;
          gap: 16px;
          position: relative;
      }
      .swiper-pagination-bullet {
          width: 42px;
          height: 42px;
          text-align: center;
          display: flex;
          align-items: center;
          justify-content: center;
          line-height: 26px;
          font-size: 16px;
          color: #6B7280;
          opacity: 1;
          background: #F9FAFB;
          border: 1px solid #E5E7EB;
      }
      .swiper-pagination-bullet-active {
      color: #fff;
      background: #4f609c;
      }
      .swiper-horizontal>.swiper-pagination-bullets, .swiper-pagination-bullets.swiper-pagination-horizontal, .swiper-pagination-custom, .swiper-pagination-fraction {
        bottom: var(--swiper-pagination-bottom,0px);
      }

.mySwiperSecond .swiper-horizontal>.mySwiperSecond .swiper-pagination-bullets .mySwiperSecond .swiper-pagination-bullet,
        .mySwiperSecond .swiper-pagination-horizontal.swiper-pagination-bullets .swiper-pagination-bullet {
            width: 16px !important;
            height: 4px !important;
            border-radius: 5px !important;
            margin: 0 6px !important;
            
        }

        .mySwiperSecond .swiper-pagination {
            bottom: 2px !important;
        }

        .mySwiperSecond .swiper-wrapper {
            height: max-content !important;
            width: max-content !important;
            padding-bottom: 64px;
        }

        .mySwiperSecond .swiper-pagination-bullet-active {
            background: #4F46E5 !important;
        }
        
        .mySwiperSecond .swiper-slide.swiper-slide-active>.slide_active\:border-indigo-600 {
            --tw-border-opacity: 1;
            border-color: rgb(79 70 229 / var(--tw-border-opacity));
        }

        .mySwiperSecond .swiper-slide.swiper-slide-active>.group .slide_active\:text-gray-800 {
            ---tw-text-opacity: 1;
            color: rgb(31 41 55 / var(--tw-text-opacity));
        }
        #animated-text {
    transition: opacity 0.5s ease-in-out;
        }


  </style>
</head>

<body class="leading-normal tracking-normal text-white gradient" style="font-family: 'Montserrat', sans-serif;">
  <!--Nav-->
  <nav id="header" class="fixed w-full z-30 top-0 text-white bg-transparent">
  <div class="w-full container mx-auto flex items-center justify-between py-2">
    <!-- Logo Section -->
    <div class="flex items-center space-x-2">
      <a id="brandname"
        class="toggleColour text-gray-800 no-underline hover:text-gray-800 hover:no-underline font-bold text-1xl lg:text-2xl flex items-center space-x-1"
        href="#">
        <img src="../img/logo1.png" alt="Quickfix Logo" class="h-10 lg:h-14">
        <span>Quickfix</span>
      </a>
    </div>

    <!-- Navbar Links -->
    <div class="flex items-center justify-between flex-grow">
      <!-- Left Links -->
      <ul class="flex items-center space-x-2 ml-6"> <!-- Changed space-x-6 to space-x-2 for closer items -->
        <li>
          <a class="inline-block text-black no-underline hover:text-gray-800 hover:underline py-2 px-4"
            href="#how-quickfix-works">How it works</a>
        </li>
        <li>
          <a class="inline-block text-black no-underline hover:text-gray-800 hover:underline py-2 px-4"
            href="#">Categories</a>
        </li>
      </ul>

      <!-- Right Links -->
      <div class="flex items-center space-x-6">
        <a href="./user/user-registration.php" class="text-gray-900 font-medium hover:underline">Sign up</a>
        <a href="./user/user-login.php" class="text-gray-900 font-medium hover:underline">Sign in</a>
        <a href="./job-seeker/become-a-job-seeker.php"
          class="bg-transparent text-blue-950 font-medium border-2 rounded px-4 py-2 hover:bg-blue-950 hover:text-white transition-colors duration-300">
          Become a Job Seeker
        </a>
      </div>
    </div>
  </div>
</nav>








<script>
  // Your existing scroll and dropdown toggle script
  var scrollpos = window.scrollY;
  var header = document.getElementById("header");
  var navcontent = document.getElementById("nav-content");
  var navaction = document.getElementById("navAction");
  var brandname = document.getElementById("brandname");
  var toToggle = document.querySelectorAll(".toggleColour");

  document.addEventListener("scroll", function () {
    /*Apply classes for slide in bar*/
    scrollpos = window.scrollY;

    if (scrollpos > 10) {
      header.classList.add("gradient");
      navaction.classList.remove("bg-white");
      navaction.classList.add("gradient");
      navaction.classList.remove("text-gray-800");
      navaction.classList.add("text-white");
      //Use to switch toggleColour colours
      for (var i = 0; i < toToggle.length; i++) {
        toToggle[i].classList.add("text-gray-800");
        toToggle[i].classList.remove("text-white");
      }
      header.classList.add("shadow");
      navcontent.classList.remove("bg-gray-100");
      navcontent.classList.add("bg-white");
    } else {
      header.classList.remove("bg-white");
      navaction.classList.remove("gradient");
      navaction.classList.add("bg-white");
      navaction.classList.remove("text-white");
      navaction.classList.add("text-gray-800");
      //Use to switch toggleColour colours
      for (var i = 0; i < toToggle.length; i++) {
        toToggle[i].classList.add("text-white");
        toToggle[i].classList.remove("text-gray-800");
      }

      header.classList.remove("shadow");
      navcontent.classList.remove("bg-white");
      navcontent.classList.add("bg-gray-100");
    }
  });


  var navMenuDiv = document.getElementById("nav-content");
  var navMenu = document.getElementById("nav-toggle");

  document.onclick = check;
  function check(e) {
    var target = (e && e.target) || (event && event.srcElement);

    //Nav Menu
    if (!checkParent(target, navMenuDiv)) {
      // click NOT on the menu
      if (checkParent(target, navMenu)) {
        // click on the link
        if (navMenuDiv.classList.contains("hidden")) {
          navMenuDiv.classList.remove("hidden");
        } else {
          navMenuDiv.classList.add("hidden");
        }
      } else {
        // click both outside link and outside menu, hide menu
        navMenuDiv.classList.add("hidden");
      }
    }
  }
  function checkParent(t, elm) {
    while (t.parentNode) {
      if (t == elm) {
        return true;
      }
      t = t.parentNode;
    }
    return false;
  }
</script>

</body>

</html>