<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laundry | Quickfix</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');

    body {
      font-family: 'Montserrat', sans-serif;
    }
  </style>
</head>

<body class="bg-white min-h-screen">
  <!-- Navigation -->
  <div id="main-content">
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
            <a href="./user/userLogin.php"><button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Sign In</button></a>
            <a href="./job-seeker/become-a-job-seeker.php"><button class="px-4 py-2 bg-blue-800 text-white rounded-full hover:bg-blue-900">Join Us</button></a>
          </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="bg-gradient-to-r from-blue-500 to-blue-700 text-white">
      <div class="container mx-auto px-4 py-16 md:py-24 flex flex-col md:flex-row items-center">
        <div class="md:w-1/2 mb-8 md:mb-0">
          <h1 class="text-4xl md:text-5xl font-bold mb-4">Laundry Services</h1>
          <p class="text-xl mb-6">We take care of your clothes so you don't have to.</p>
          <div class="flex space-x-4">
            <a href="./user/userLogin.php" class="bg-white text-blue-700 px-6 py-3 rounded-lg font-medium hover:bg-gray-100 transition inline-block text-center">
              Get Started
            </a>

          </div>
        </div>
        <div class="md:w-1/2">
          <img src="https://images.unsplash.com/photo-1545173168-9f1947eebb7f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80"
            alt="Laundry Service"
            class="rounded-lg shadow-xl w-full h-auto">
        </div>
      </div>
    </header>

    <!-- Laundry Services Section -->
    <section id="services" class="py-16 bg-white">
      <div class="container mx-auto px-4">
        <div class="text-center mb-12">
          <h2 class="text-3xl font-bold text-gray-800 mb-2">Our Laundry Services</h2>
          <p class="text-gray-600 max-w-2xl mx-auto">We offer a wide range of professional laundry services to meet all your needs, with quality and care.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <!-- Service 1 -->
          <div class="bg-gray-50 rounded-lg p-6 shadow-md hover:shadow-lg transition">
            <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center mb-4">
              <i class="fas fa-tshirt text-blue-600 text-2xl"></i>
            </div>
            <h3 class="text-xl font-semibold mb-2">Wash & Fold</h3>
            <p class="text-gray-600 mb-4">Our standard wash and fold service includes sorting, washing, drying, and folding your clothes with care.</p>
            <div class="flex items-center justify-between">

            </div>
          </div>

          <!-- Service 2 -->
          <div class="bg-gray-50 rounded-lg p-6 shadow-md hover:shadow-lg transition">
            <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center mb-4">
              <i class="fas fa-user-tie text-blue-600 text-2xl"></i>
            </div>
            <h3 class="text-xl font-semibold mb-2">Dry Cleaning</h3>
            <p class="text-gray-600 mb-4">Professional dry cleaning for your delicate garments, suits, dresses, and other special care items.</p>
            <div class="flex items-center justify-between">

            </div>
          </div>

          <!-- Service 3 -->
          <div class="bg-gray-50 rounded-lg p-6 shadow-md hover:shadow-lg transition">
            <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center mb-4">
              <i class="fas fa-hand-paper text-blue-600 text-2xl"></i>
            </div>
            <h3 class="text-xl font-semibold mb-2">Wash Your Clothes at Home</h3>
            <p class="text-gray-600 mb-4">Enjoy the convenience of doing your laundry at home with expert guidance. Get tips, recommendations, and the best products for a hassle-free wash.</p>
            <div class="flex items-center justify-between">

            </div>
          </div>
        </div>

        <div class="mt-12 text-center">
          <button class="bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition">View All Services</button>
        </div>
      </div>
    </section>

    <!-- How It Works -->
    <section class="py-16 bg-gray-50">
      <div class="container mx-auto px-4">
        <div class="text-center mb-12">
          <h2 class="text-3xl font-bold text-gray-800 mb-2">How It Works</h2>
          <p class="text-gray-600 max-w-2xl mx-auto">Our simple 3-step process makes laundry day a breeze.</p>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-start">
          <!-- Step 1 -->
          <div class="flex flex-col items-center mb-8 md:mb-0 md:w-1/4 px-4">
            <div class="w-16 h-16 bg-blue-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mb-4">1</div>
            <h3 class="text-xl font-semibold mb-2 text-center">Schedule</h3>
            <p class="text-gray-600 text-center">Book a time that works for your schedule.</p>
          </div>



          <!-- Step 3 -->
          <div class="flex flex-col items-center mb-8 md:mb-0 md:w-1/4 px-4">
            <div class="w-16 h-16 bg-blue-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mb-4">2</div>
            <h3 class="text-xl font-semibold mb-2 text-center">Clean</h3>
            <p class="text-gray-600 text-center">Your clothes are cleaned with care by skilled people.</p>
          </div>

          <!-- Step 4 -->
          <div class="flex flex-col items-center md:w-1/4 px-4">
            <div class="w-16 h-16 bg-blue-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mb-4">3</div>
            <h3 class="text-xl font-semibold mb-2 text-center">Enjoy</h3>
            <p class="text-gray-600 text-center">Enjoy your new like fresh clothes than ever.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Reviews Section -->
    <section id="reviews" class="py-16 bg-white">
      <div class="container mx-auto px-4">
        <div class="text-center mb-12">
          <h2 class="text-3xl font-bold text-gray-800 mb-2">Customer Reviews</h2>
          <p class="text-gray-600 max-w-2xl mx-auto">Don't just take our word for it. See what our customers have to say.</p>
        </div>

        <!-- Reviews Carousel -->
        <div class="relative max-w-4xl mx-auto">
          <!-- Carousel Container -->
          <div id="reviews-carousel" class="overflow-hidden">
            <div id="reviews-slider" class="flex transition-transform duration-300 ease-in-out">
              <!-- Review 1 -->
              <div class="w-full md:w-1/2 lg:w-1/3 flex-shrink-0 px-4">
                <div class="bg-gray-50 rounded-lg p-6 shadow-md h-full">
                  <div class="flex items-center mb-4">
                    <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Customer" class="w-12 h-12 rounded-full mr-4">
                    <div>
                      <h4 class="font-semibold">Sarah Johnson</h4>
                      <div class="flex text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                      </div>
                    </div>
                  </div>
                  <p class="text-gray-600 italic">"I've been using CleanPress for over a year now and I couldn't be happier. My clothes always come back perfectly clean and neatly folded. The pickup and delivery service is so convenient!"</p>
                  <p class="mt-4 text-sm text-gray-500">Wash & Fold Service • 2 weeks ago</p>
                </div>
              </div>

              <!-- Review 2 -->
              <div class="w-full md:w-1/2 lg:w-1/3 flex-shrink-0 px-4">
                <div class="bg-gray-50 rounded-lg p-6 shadow-md h-full">
                  <div class="flex items-center mb-4">
                    <img src="https://randomuser.me/api/portraits/men/46.jpg" alt="Customer" class="w-12 h-12 rounded-full mr-4">
                    <div>
                      <h4 class="font-semibold">Michael Chen</h4>
                      <div class="flex text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                      </div>
                    </div>
                  </div>
                  <p class="text-gray-600 italic">"The dry cleaning service is excellent. They managed to remove a stubborn stain from my favorite suit that other cleaners couldn't handle. Very professional and high quality service."</p>
                  <p class="mt-4 text-sm text-gray-500">Dry Cleaning Service • 1 month ago</p>
                </div>
              </div>

              <!-- Review 3 -->
              <div class="w-full md:w-1/2 lg:w-1/3 flex-shrink-0 px-4">
                <div class="bg-gray-50 rounded-lg p-6 shadow-md h-full">
                  <div class="flex items-center mb-4">
                    <img src="https://randomuser.me/api/portraits/women/65.jpg" alt="Customer" class="w-12 h-12 rounded-full mr-4">
                    <div>
                      <h4 class="font-semibold">Emily Rodriguez</h4>
                      <div class="flex text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                      </div>
                    </div>
                  </div>
                  <p class="text-gray-600 italic">"As a busy mom of three, the pickup and delivery service has been a game changer. The app is easy to use, the staff is friendly, and the service is consistently great. Highly recommend!"</p>
                  <p class="mt-4 text-sm text-gray-500">Pickup & Delivery • 3 days ago</p>
                </div>
              </div>

              <!-- Review 4 -->
              <div class="w-full md:w-1/2 lg:w-1/3 flex-shrink-0 px-4">
                <div class="bg-gray-50 rounded-lg p-6 shadow-md h-full">
                  <div class="flex items-center mb-4">
                    <img src="https://randomuser.me/api/portraits/men/22.jpg" alt="Customer" class="w-12 h-12 rounded-full mr-4">
                    <div>
                      <h4 class="font-semibold">David Wilson</h4>
                      <div class="flex text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                      </div>
                    </div>
                  </div>
                  <p class="text-gray-600 italic">"Great service overall. I appreciate the attention to detail and the care they take with my clothes. The only reason for 4 stars instead of 5 is that delivery was a bit late once, but they were very apologetic."</p>
                  <p class="mt-4 text-sm text-gray-500">Wash & Fold Service • 2 months ago</p>
                </div>
              </div>

              <!-- Review 5 -->
              <div class="w-full md:w-1/2 lg:w-1/3 flex-shrink-0 px-4">
                <div class="bg-gray-50 rounded-lg p-6 shadow-md h-full">
                  <div class="flex items-center mb-4">
                    <img src="https://randomuser.me/api/portraits/women/89.jpg" alt="Customer" class="w-12 h-12 rounded-full mr-4">
                    <div>
                      <h4 class="font-semibold">Jessica Taylor</h4>
                      <div class="flex text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                      </div>
                    </div>
                  </div>
                  <p class="text-gray-600 italic">"I had a last-minute emergency with a wine spill on my favorite dress before an event. CleanPress not only picked it up immediately but had it cleaned and delivered back to me in time. Exceptional service!"</p>
                  <p class="mt-4 text-sm text-gray-500">Emergency Cleaning • 1 week ago</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Carousel Controls -->
          <button id="prev-btn" class="absolute top-1/2 left-0 -ml-4 -translate-y-1/2 bg-white w-10 h-10 rounded-full shadow-md flex items-center justify-center focus:outline-none">
            <i class="fas fa-chevron-left text-blue-600"></i>
          </button>
          <button id="next-btn" class="absolute top-1/2 right-0 -mr-4 -translate-y-1/2 bg-white w-10 h-10 rounded-full shadow-md flex items-center justify-center focus:outline-none">
            <i class="fas fa-chevron-right text-blue-600"></i>
          </button>

          <!-- Carousel Indicators -->
          <div class="flex justify-center mt-8">
            <div id="carousel-indicators" class="flex space-x-2">
              <!-- Indicators will be added by JavaScript -->
            </div>
          </div>
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

    <!-- JavaScript for Reviews Carousel -->
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        // Get carousel elements
        const slider = document.getElementById('reviews-slider');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        const indicatorsContainer = document.getElementById('carousel-indicators');

        // Get all slides
        const slides = slider.children;
        const slideCount = slides.length;

        // Calculate how many slides to show based on screen width
        let slidesToShow = 1;
        if (window.innerWidth >= 768) slidesToShow = 2;
        if (window.innerWidth >= 1024) slidesToShow = 3;

        // Calculate total number of pages
        const pageCount = Math.ceil(slideCount / slidesToShow);

        // Current page index
        let currentPage = 0;

        // Create indicators
        for (let i = 0; i < pageCount; i++) {
          const indicator = document.createElement('button');
          indicator.classList.add('w-3', 'h-3', 'rounded-full', 'bg-gray-300');
          if (i === 0) indicator.classList.add('bg-blue-600');

          indicator.addEventListener('click', () => {
            goToPage(i);
          });

          indicatorsContainer.appendChild(indicator);
        }

        // Function to update indicators
        function updateIndicators() {
          const indicators = indicatorsContainer.children;
          for (let i = 0; i < indicators.length; i++) {
            if (i === currentPage) {
              indicators[i].classList.remove('bg-gray-300');
              indicators[i].classList.add('bg-blue-600');
            } else {
              indicators[i].classList.remove('bg-blue-600');
              indicators[i].classList.add('bg-gray-300');
            }
          }
        }

        // Function to go to a specific page
        function goToPage(pageIndex) {
          if (pageIndex < 0) pageIndex = 0;
          if (pageIndex >= pageCount) pageIndex = pageCount - 1;

          currentPage = pageIndex;
          const offset = -pageIndex * (100 / slidesToShow) * slidesToShow;
          slider.style.transform = `translateX(${offset}%)`;

          updateIndicators();
        }

        // Event listeners for buttons
        prevBtn.addEventListener('click', () => {
          goToPage(currentPage - 1);
        });

        nextBtn.addEventListener('click', () => {
          goToPage(currentPage + 1);
        });

        // Handle window resize
        window.addEventListener('resize', () => {
          // Recalculate slidesToShow
          let newSlidesToShow = 1;
          if (window.innerWidth >= 768) newSlidesToShow = 2;
          if (window.innerWidth >= 1024) newSlidesToShow = 3;

          // Only update if slidesToShow changed
          if (newSlidesToShow !== slidesToShow) {
            slidesToShow = newSlidesToShow;

            // Recalculate pageCount
            const newPageCount = Math.ceil(slideCount / slidesToShow);

            // Clear and recreate indicators
            indicatorsContainer.innerHTML = '';
            for (let i = 0; i < newPageCount; i++) {
              const indicator = document.createElement('button');
              indicator.classList.add('w-3', 'h-3', 'rounded-full', 'bg-gray-300');
              if (i === currentPage) indicator.classList.add('bg-blue-600');

              indicator.addEventListener('click', () => {
                goToPage(i);
              });

              indicatorsContainer.appendChild(indicator);
            }

            // Adjust current page if needed
            if (currentPage >= newPageCount) {
              currentPage = newPageCount - 1;
            }

            // Update carousel position
            goToPage(currentPage);
          }
        });

        // Initialize carousel
        goToPage(0);

        // Auto-advance carousel every 5 seconds
        setInterval(() => {
          goToPage((currentPage + 1) % pageCount);
        }, 5000);
      });
    </script>
</body>

</html>