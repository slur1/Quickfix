<?php
// Include the header
include '../includes/header.php';
?>

<!--Hero-->
<div class="pt-24">
  <div class="container px-3 mx-auto flex flex-col items-center text-center">
    <!-- Centered Content -->
    <div class="flex flex-col w-full md:w-3/5 justify-center items-center">
      <h1 class="my-6 text-5xl md:text-6xl font-bold leading-tight">
        <span>House </span><span id="typewriter"></span>
      </h1>
      <p class="leading-normal text-xl md:text-3xl mb-10">
        House cleaning, repairs, and more—book reliable help for your short-term needs now.
      </p>
      <div class="flex flex-col md:flex-row gap-6">
        <a href="#services"
          class="bg-blue-800 text-white text-lg px-8 py-4 rounded-xl shadow-lg hover:bg-blue-900 transition duration-300">
          Post a Job Now! →
        </a>
        <a href="#contact"
          class="bg-gray-100 text-blue-800 text-lg px-8 py-4 rounded-xl shadow-lg hover:bg-gray-200 transition duration-300">
          Earn money as a Job Seeker →
        </a>
      </div>
    </div>
  </div>

  <!-- Gradient Section -->
  <div class="relative -mt-20 lg:-mt-32">
    <svg viewBox="0 0 1440 320" version="1.1" xmlns="http://www.w3.org/2000/svg" class="w-full">
      <path fill="#ffffff" fill-opacity="1" d="M0,160L60,176C120,192,240,224,360,218.7C480,213,600,171,720,181.3C840,192,960,256,1080,282.7C1200,309,1320,299,1380,293.3L1440,288L1440,320L1380,320C1320,320,1200,320,1080,320C960,320,840,320,720,320C600,320,480,320,360,320C240,320,120,320,60,320L0,320Z"></path>
    </svg>
  </div>
</div>






<section class="bg-white py-8">
  <div class="container max-w-5xl mx-auto m-8">
    <h2 class="w-full my-2 text-5xl font-bold leading-tight text-center text-gray-800">
      Swift Solutions for Your Short-Term Jobs
    </h2>
    <div class="w-full mb-4">
      <div class="h-1 mx-auto gradient w-64 opacity-25 my-0 py-0 rounded-t"></div>
    </div>
    <div class="flex flex-wrap">
      <div class="w-5/6 sm:w-1/2 p-6">
        <h3 class="text-3xl text-gray-800 font-bold leading-none mb-3">
          House Cleaning
        </h3>
        <p class="text-gray-600 mb-8">
          Trusted experienced cleaners ready to leave your home spotless—book your cleaning today!
          <br />
          <br />
        </p>
      </div>
      <div class="w-full sm:w-1/2 p-6">
        <img src="../img/cleaning-service.png" alt="Image description" class="w-full h-auto">
      </div>
    </div>
    <div class="flex flex-wrap flex-col-reverse sm:flex-row">
      <div class="w-full sm:w-1/2 p-6 mt-6">
        <img src="../img/house-cleaning.png" alt="Image description" class="w-2/3 h-auto">
      </div>
      <div class="w-full sm:w-1/2 p-6 mt-6">
        <div class="align-middle">
          <h3 class="text-3xl text-gray-800 font-bold leading-none mb-3">
            House Repair
          </h3>
          <p class="text-gray-600 mb-8">
            From fixing leaks to electrical work, our experts are ready to tackle any home repair job—quickly and affordably.
            <br />
            <br />
          </p>
        </div>
      </div>
    </div>
  </div>
</section>




<div class="bg-white min-h-screen flex items-center justify-center p-8">
  <!-- Container -->
  <div class="w-full max-w-5xl">
    <!-- Header -->
    <h2 class="text-3xl font-bold text-center text-black mb-8">Popular Projects</h2>

    <!-- Card Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">

      <!-- Project Card 1 -->
      <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
        <img src="../img/apartment-cleaning.jpg" alt="Furniture Assembly" class="w-full h-48 object-cover">
        <div class="p-4">
          <h3 class="text-lg font-semibold text-gray-800">Apartment Cleaning</h3>
          <p class="text-gray-500">Projects starting at ₱45 per hour</p>
        </div>
      </div>

      <!-- Project Card 2 -->
      <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
        <img src="../img/deep-clean.jpg" alt="Mount Art or Shelves" class="w-full h-48 object-cover">
        <div class="p-4">
          <h3 class="text-lg font-semibold text-gray-800">Deep Clean</h3>
          <p class="text-gray-500">Projects starting at ₱35 per hour</p>
        </div>
      </div>

      <!-- Project Card 3 -->
      <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
        <img src="../img/garage-cleaning.jpg" alt="Mount a TV" class="w-full h-48 object-cover">
        <div class="p-4">
          <h3 class="text-lg font-semibold text-gray-800">Garage Cleaning</h3>
          <p class="text-gray-500">Projects starting at ₱45 per hour</p>
        </div>
      </div>

      <!-- Project Card 4 -->
      <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
        <img src="../img/move-out-clean.jpg" alt="Help Moving" class="w-full h-48 object-cover">
        <div class="p-4">
          <h3 class="text-lg font-semibold text-gray-800">Move Out Clean</h3>
          <p class="text-gray-500">Projects starting at ₱50 per hour</p>
        </div>
      </div>

      <!-- Project Card 5 -->
      <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
        <img src="../img/door-cabinet-repair.jpg" alt="Home & Apartment Cleaning" class="w-full h-48 object-cover">
        <div class="p-4">
          <h3 class="text-lg font-semibold text-gray-800">Door, Cabinet, & Furniture Repair</h3>
          <p class="text-gray-500">Projects starting at ₱55 per hour</p>
        </div>
      </div>

      <!-- Project Card 6 -->
      <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
        <img src="../img/wall-repair.jpg" alt="Minor Plumbing Repairs" class="w-full h-48 object-cover">
        <div class="p-4">
          <h3 class="text-lg font-semibold text-gray-800">Wall Repair</h3>
          <p class="text-gray-500">Projects starting at ₱60 per hour</p>
        </div>
      </div>

      <!-- Project Card 7 -->
      <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
        <img src="../img/electrical-help.jpg" alt="Electrical Help" class="w-full h-48 object-cover">
        <div class="p-4">
          <h3 class="text-lg font-semibold text-gray-800">Electrical Help</h3>
          <p class="text-gray-500">Projects starting at ₱55 per hour</p>
        </div>
      </div>

      <!-- Project Card 8 -->
      <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
        <img src="../img/plumbing.jpg" alt="Heavy Lifting" class="w-full h-48 object-cover">
        <div class="p-4">
          <h3 class="text-lg font-semibold text-gray-800">Plumbing Help</h3>
          <p class="text-gray-500">Projects starting at ₱50 per hour</p>
        </div>
      </div>

      <!-- Project Card 9 -->
      <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
        <img src="../img/light-carpentry.jpg" alt="Heavy Lifting" class="w-full h-48 object-cover">
        <div class="p-4">
          <h3 class="text-lg font-semibold text-gray-800">Light Carpentry</h3>
          <p class="text-gray-500">Projects starting at ₱45 per hour</p>
        </div>
      </div>

    </div>
  </div>
</div>




<section id="how-quickfix-works" class="py-24 relative">
  <div class="w-full max-w-7xl px-4 md:px-5 lg:px-5 mx-auto">
    <div class="w-full flex-col justify-start items-center lg:gap-12 gap-8 inline-flex">
      <div class="w-full flex-col justify-start items-center gap-3 flex">
        <h2 class="w-full text-center text-4xl font-bold leading-tight">How Quickfix Works</h2>
      </div>

      <div class="w-full flex flex-col lg:flex-row lg:items-start items-center lg:gap-16 gap-8">

        <img class="object-cover w-1/3 lg:w-1/4 mb-6 lg:mb-0" src="../img/how-it-works.svg" alt="How It Works image" />

        <div class="swiper mySwiper w-full lg:pl-8 lg:pt-4">
          <div class="swiper-wrapper">
            <!-- Swiper Slide 1 -->
            <div class="swiper-slide p-4 lg:p-6">
              <div class="flex flex-col items-center lg:items-start gap-4">
                <span class="text-base font-medium text-center lg:text-left">1st Step</span>
                <div class="flex flex-col items-center lg:items-start gap-2">
                  <h4 class="text-xl font-semibold text-center lg:text-left">Gather Required Documents</h4>
                  <p class="w-full lg:max-w-2xl text-base font-normal text-center lg:text-left leading-relaxed">
                    Collect necessary documents such as identification (passport or driver's license), proof of address
                    (utility bill or lease agreement), and Social Security Number (or equivalent for your country).
                  </p>
                </div>
              </div>
            </div>

            <!-- Swiper Slide 2 -->
            <div class="swiper-slide p-4 lg:p-6">
              <div class="flex flex-col items-center lg:items-start gap-4">
                <span class="text-base font-medium text-center lg:text-left">2nd Step</span>
                <div class="flex flex-col items-center lg:items-start gap-2">
                  <h4 class="text-xl font-semibold text-center lg:text-left">Complete Profile Information</h4>
                  <p class="w-full lg:max-w-2xl text-base font-normal text-center lg:text-left leading-relaxed">
                    Fill out your profile information, including your contact details, to make it easy for others to
                    connect
                    with you.
                  </p>
                </div>
              </div>
            </div>

            <!-- Swiper Slide 3 -->
            <div class="swiper-slide p-4 lg:p-6">
              <div class="flex flex-col items-center lg:items-start gap-4">
                <span class="text-base font-medium text-center lg:text-left">3rd Step</span>
                <div class="flex flex-col items-center lg:items-start gap-2">
                  <h4 class="text-xl font-semibold text-center lg:text-left">Browse Available Jobs</h4>
                  <p class="w-full lg:max-w-2xl text-base font-normal text-center lg:text-left leading-relaxed">
                    Use our platform to browse available short-term jobs, from cleaning to house repairs, and find the
                    perfect
                    match.
                  </p>
                </div>
              </div>
            </div>

            <!-- Swiper Slide 4 -->
            <div class="swiper-slide p-4 lg:p-6">
              <div class="flex flex-col items-center lg:items-start gap-4">
                <span class="text-base font-medium text-center lg:text-left">4th Step</span>
                <div class="flex flex-col items-center lg:items-start gap-2">
                  <h4 class="text-xl font-semibold text-center lg:text-left">Contact and Agree on Terms</h4>
                  <p class="w-full lg:max-w-2xl text-base font-normal text-center lg:text-left leading-relaxed">
                    Reach out to the service provider, agree on terms, and set up a time for the job to be done.
                  </p>
                </div>
              </div>
            </div>

            <!-- Swiper Slide 5 -->
            <div class="swiper-slide p-4 lg:p-6">
              <div class="flex flex-col items-center lg:items-start gap-4">
                <span class="text-base font-medium text-center lg:text-left">5th Step</span>
                <div class="flex flex-col items-center lg:items-start gap-2">
                  <h4 class="text-xl font-semibold text-center lg:text-left">Complete and Review</h4>
                  <p class="w-full lg:max-w-2xl text-base font-normal text-center lg:text-left leading-relaxed">
                    Once the job is completed, leave a review to help others make informed decisions in the future.
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- Place pagination outside the swiper-slide elements -->
          <div class="swiper-pagination justify-center w-full flex mt-6"></div>
        </div>
      </div>
    </div>
  </div>
</section>






<!--START OF FAQS-->
<section class="py-24 bg-white">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div
      class="flex flex-col justify-center items-center gap-x-16 gap-y-5 xl:gap-28 lg:flex-row lg:justify-between max-lg:max-w-2xl mx-auto max-w-full">
      <div class="w-full lg:w-1/2">
        <img src="../img/faqs.svg" alt="FAQ Quickfix Section"
          class="w-full rounded-xl object-cover" />
      </div>
      <div class="w-full lg:w-1/2">
        <div class="lg:max-w-xl">
          <div class="mb-6 lg:mb-16">
            <h6 class="text-lg text-center font-medium text-indigo-600 mb-2 lg:text-left">faqs</h6>
            <h2 class="text-4xl text-center font-bold text-gray-900 leading-[3.25rem] mb-5 lg:text-left">Looking for
              answers?</h2>
          </div>
          <div class="accordion-group" data-accordion="default-accordion">
            <div class="accordion py-8 border-b border-solid border-gray-200"
              id="basic-heading-one-with-arrow-always-open">
              <button
                class="accordion-toggle group inline-flex items-center justify-between text-xl font-normal leading-8 text-gray-600 w-full transition duration-500 hover:text-indigo-600 accordion-active:text-indigo-600 accordion-active:font-medium">
                <h5>How to create an account?</h5>
                <svg
                  class="text-gray-900 transition duration-500 group-hover:text-indigo-600 accordion-active:text-indigo-600"
                  width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M16.5 8.25L12.4142 12.3358C11.7475 13.0025 11.4142 13.3358 11 13.3358C10.5858 13.3358 10.2525 13.0025 9.58579 12.3358L5.5 8.25"
                    stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
              </button>
              <div id="basic-collapse-one-with-arrow-always-open"
                class="accordion-content w-full px-0 overflow-hidden pr-4"
                aria-labelledby="basic-heading-one-with-arrow-always-open">
                <p class="text-base font-normal text-gray-600">
                  To create an account, find the 'Sign up' or 'Create account' button, fill out the registration form
                  with your personal information, and click 'Create account' or 'Sign up.' Verify your email address if
                  needed, and then log in to start using the platform.
                </p>
              </div>
            </div>
            <div class="accordion py-8 border-b border-solid border-gray-200"
              id="basic-heading-two-with-arrow-always-open">
              <button
                class="accordion-toggle group inline-flex items-center justify-between font-normal text-xl leading-8 text-gray-600 w-full transition duration-500 hover:text-indigo-600 accordion-active:text-indigo-600 accordion-active:font-medium">
                <h5>Have any trust issue?</h5>
                <svg
                  class="text-gray-900 transition duration-500 group-hover:text-indigo-600 accordion-active:text-indigo-600"
                  width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M16.5 8.25L12.4142 12.3358C11.7475 13.0025 11.4142 13.3358 11 13.3358C10.5858 13.3358 10.2525 13.0025 9.58579 12.3358L5.5 8.25"
                    stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
              </button>
              <div id="basic-collapse-two-with-arrow-always-open"
                class="accordion-content w-full px-0 overflow-hidden pr-4">
                <p class="text-base text-gray-500 font-normal">
                  Our focus on providing robust and user-friendly content management capabilities ensures that you can
                  manage your content with confidence, and achieve your content marketing goals with ease.
                </p>
              </div>
            </div>
            <div class="accordion py-8 border-b border-solid border-gray-200"
              id="basic-heading-three-with-arrow-always-open">
              <button
                class="accordion-toggle group inline-flex items-center justify-between text-xl font-normal leading-8 text-gray-600 w-full transition duration-500 hover:text-indigo-600 accordion-active:font-medium accordion-active:text-indigo-600">
                <h5>How can I reset my password?</h5>
                <svg
                  class="text-gray-900 transition duration-500 group-hover:text-indigo-600 accordion-active:text-indigo-600"
                  width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M16.5 8.25L12.4142 12.3358C11.7475 13.0025 11.4142 13.3358 11 13.3358C10.5858 13.3358 10.2525 13.0025 9.58579 12.3358L5.5 8.25"
                    stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
              </button>
              <div id="basic-collapse-three-with-arrow-always-open"
                class="accordion-content w-full px-0 overflow-hidden pr-4">
                <p class="text-base text-gray-500 font-normal">
                  Our focus on providing robust and user-friendly content management capabilities ensures that you can
                  manage your content with confidence, and achieve your content marketing goals with ease.
                </p>
              </div>
            </div>
            <div class="accordion py-8" id="basic-heading-four-with-arrow-always-open">
              <button
                class="accordion-toggle group inline-flex items-center justify-between text-xl font-normal leading-8 text-gray-600 w-full transition duration-500 hover:text-indigo-600 accordion-active:font-medium accordion-active:text-indigo-600">
                <h5>What is the payment process?</h5>
                <svg
                  class="text-gray-900 transition duration-500 group-hover:text-indigo-600 accordion-active:text-indigo-600"
                  width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M16.5 8.25L12.4142 12.3358C11.7475 13.0025 11.4142 13.3358 11 13.3358C10.5858 13.3358 10.2525 13.0025 9.58579 12.3358L5.5 8.25"
                    stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
              </button>
              <div id="basic-collapse-four-with-arrow-always-open"
                class="accordion-content w-full px-0 overflow-hidden pr-4">
                <p class="text-base text-gray-500 font-normal">
                  Our focus on providing robust and user-friendly content management capabilities ensures that you can
                  manage your content with confidence, and achieve your content marketing goals with ease.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!--END OF FAQS-->


<!--START OF TESTIMONIALS-->
<section class="py-24 bg-white">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="mb-16 ">
      <span class="text-sm text-gray-500 font-medium text-center block mb-2">TESTIMONIAL</span>
      <h2 class="text-4xl text-center font-bold text-gray-900 ">What our happy user says!</h2>
    </div>
    <!--Slider wrapper-->

    <div class="swiper mySwiperSecond">
      <div class="swiper-wrapper w-max">
        <div class="swiper-slide">
          <div
            class="group bg-white border border-solid border-gray-300 rounded-xl p-6 transition-all duration-500  w-full mx-auto hover:border-indigo-600 hover:shadow-sm slide_active:border-indigo-600">
            <div class="">
              <div class="flex items-center mb-7 gap-2 text-amber-500 transition-all duration-500  ">
                <svg class="w-5 h-5" viewBox="0 0 18 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M8.10326 1.31699C8.47008 0.57374 9.52992 0.57374 9.89674 1.31699L11.7063 4.98347C11.8519 5.27862 12.1335 5.48319 12.4592 5.53051L16.5054 6.11846C17.3256 6.23765 17.6531 7.24562 17.0596 7.82416L14.1318 10.6781C13.8961 10.9079 13.7885 11.2389 13.8442 11.5632L14.5353 15.5931C14.6754 16.41 13.818 17.033 13.0844 16.6473L9.46534 14.7446C9.17402 14.5915 8.82598 14.5915 8.53466 14.7446L4.91562 16.6473C4.18199 17.033 3.32456 16.41 3.46467 15.5931L4.15585 11.5632C4.21148 11.2389 4.10393 10.9079 3.86825 10.6781L0.940384 7.82416C0.346867 7.24562 0.674378 6.23765 1.4946 6.11846L5.54081 5.53051C5.86652 5.48319 6.14808 5.27862 6.29374 4.98347L8.10326 1.31699Z"
                    fill="currentColor" />
                </svg>
                <span class="text-base font-semibold text-indigo-600">4.9</span>
              </div>
              <p
                class="text-base text-gray-600 leading-6  transition-all duration-500 pb-8 group-hover:text-gray-800 slide_active:text-gray-800">
                "QuickFix saved me so much time! I needed someone reliable to fix a leaky pipe, and within hours, I had an experienced
                handyman at my door. The work was professional, affordable, and hassle-free. Highly recommend!"
              </p>
            </div>
            <div class="flex items-center gap-5 border-t border-solid border-gray-200 pt-5">
              <img class="rounded-full h-10 w-10 object-cover" src="https://pagedone.io/asset/uploads/1696229969.png"
                alt="avatar" />
              <div class="block">
                <h5 class="text-gray-900 font-medium transition-all duration-500  mb-1">Emily R.</h5>
                <span class="text-sm leading-4 text-gray-500">Homeowner</span>
              </div>
            </div>
          </div>
        </div>
        <div class="swiper-slide">
          <div
            class="group bg-white border border-solid border-gray-300 flex justify-between flex-col rounded-xl p-6 transition-all duration-500  w-full mx-auto hover:border-indigo-600 slide_active:border-indigo-600 hover:shadow-sm">
            <div class="">
              <div class="flex items-center mb-7 gap-2 text-amber-500 transition-all duration-500  ">
                <svg class="w-5 h-5" viewBox="0 0 18 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M8.10326 1.31699C8.47008 0.57374 9.52992 0.57374 9.89674 1.31699L11.7063 4.98347C11.8519 5.27862 12.1335 5.48319 12.4592 5.53051L16.5054 6.11846C17.3256 6.23765 17.6531 7.24562 17.0596 7.82416L14.1318 10.6781C13.8961 10.9079 13.7885 11.2389 13.8442 11.5632L14.5353 15.5931C14.6754 16.41 13.818 17.033 13.0844 16.6473L9.46534 14.7446C9.17402 14.5915 8.82598 14.5915 8.53466 14.7446L4.91562 16.6473C4.18199 17.033 3.32456 16.41 3.46467 15.5931L4.15585 11.5632C4.21148 11.2389 4.10393 10.9079 3.86825 10.6781L0.940384 7.82416C0.346867 7.24562 0.674378 6.23765 1.4946 6.11846L5.54081 5.53051C5.86652 5.48319 6.14808 5.27862 6.29374 4.98347L8.10326 1.31699Z"
                    fill="currentColor" />
                </svg>
                <span class="text-base font-semibold text-indigo-600">4.9</span>
              </div>
              <p
                class="text-base text-gray-600 leading-6  transition-all duration-500 pb-8 group-hover:text-gray-800 slide_active:text-gray-800">
                "Finding someone to clean my apartment was so easy with QuickFix. The person who came was thorough, friendly, and left
                my place spotless. I'll definitely use this service again for my cleaning needs!"
              </p>
            </div>
            <div class="flex items-center gap-5 pt-5 border-t border-solid border-gray-200">
              <img class="rounded-full h-10 w-10 object-cover" src="https://pagedone.io/asset/uploads/1696229994.png"
                alt="avatar" />
              <div class="block">
                <h5 class="text-gray-900 font-medium transition-all duration-500  mb-1">James L.
                </h5>
                <span class="text-sm leading-4 text-gray-500">Busy Professional</span>
              </div>
            </div>
          </div>
        </div>
        <div class="swiper-slide">
          <div
            class=" flex justify-between flex-col lg:w-full group bg-white border border-solid border-gray-300 rounded-xl p-6 transition-all duration-500  w-full mx-auto slide_active:border-indigo-600 hover:border-indigo-600 hover:shadow-sm">
            <div class="">
              <div class="flex items-center mb-7 gap-2 text-amber-500 transition-all duration-500  ">
                <svg class="w-5 h-5" viewBox="0 0 18 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M8.10326 1.31699C8.47008 0.57374 9.52992 0.57374 9.89674 1.31699L11.7063 4.98347C11.8519 5.27862 12.1335 5.48319 12.4592 5.53051L16.5054 6.11846C17.3256 6.23765 17.6531 7.24562 17.0596 7.82416L14.1318 10.6781C13.8961 10.9079 13.7885 11.2389 13.8442 11.5632L14.5353 15.5931C14.6754 16.41 13.818 17.033 13.0844 16.6473L9.46534 14.7446C9.17402 14.5915 8.82598 14.5915 8.53466 14.7446L4.91562 16.6473C4.18199 17.033 3.32456 16.41 3.46467 15.5931L4.15585 11.5632C4.21148 11.2389 4.10393 10.9079 3.86825 10.6781L0.940384 7.82416C0.346867 7.24562 0.674378 6.23765 1.4946 6.11846L5.54081 5.53051C5.86652 5.48319 6.14808 5.27862 6.29374 4.98347L8.10326 1.31699Z"
                    fill="currentColor" />
                </svg>
                <span class="text-base font-semibold text-indigo-600">4.9</span>
              </div>
              <p
                class="text-base text-gray-600 leading-6  transition-all duration-500  pb-8 group-hover:text-gray-800 slide_active:text-gray-800">
                "I was looking for a part-time job and QuickFix connected me with multiple clients who needed help with small repairs
                around the house. Now, I have a flexible schedule and get paid to do what I enjoy!"
              </p>
            </div>
            <div class="flex items-center gap-5 border-t border-solid border-gray-200 pt-5">
              <img class="rounded-full h-10 w-10 object-cover" src="	https://pagedone.io/asset/uploads/1696230027.png"
                alt="avatar" />
              <div class="block">
                <h5 class="text-gray-900 font-medium transition-all duration-500  mb-1">Carlos M.</h5>
                <span class="text-sm leading-4 text-gray-500">Freelance Worker</span>
              </div>
            </div>
          </div>
        </div>
        <div class="swiper-slide">
          <div
            class="group bg-white border border-solid border-gray-300 rounded-xl p-6 transition-all duration-500  w-full mx-auto slide_active:border-indigo-600 hover:border-indigo-600 hover:shadow-sm">
            <div class="">
              <div class="flex items-center mb-7 gap-2 text-amber-500 transition-all duration-500  ">
                <svg class="w-5 h-5" viewBox="0 0 18 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M8.10326 1.31699C8.47008 0.57374 9.52992 0.57374 9.89674 1.31699L11.7063 4.98347C11.8519 5.27862 12.1335 5.48319 12.4592 5.53051L16.5054 6.11846C17.3256 6.23765 17.6531 7.24562 17.0596 7.82416L14.1318 10.6781C13.8961 10.9079 13.7885 11.2389 13.8442 11.5632L14.5353 15.5931C14.6754 16.41 13.818 17.033 13.0844 16.6473L9.46534 14.7446C9.17402 14.5915 8.82598 14.5915 8.53466 14.7446L4.91562 16.6473C4.18199 17.033 3.32456 16.41 3.46467 15.5931L4.15585 11.5632C4.21148 11.2389 4.10393 10.9079 3.86825 10.6781L0.940384 7.82416C0.346867 7.24562 0.674378 6.23765 1.4946 6.11846L5.54081 5.53051C5.86652 5.48319 6.14808 5.27862 6.29374 4.98347L8.10326 1.31699Z"
                    fill="currentColor" />
                </svg>
                <span class="text-base font-semibold text-indigo-600">4.9</span>
              </div>
              <p
                class="text-base text-gray-600 leading-6  transition-all duration-500 pb-8 group-hover:text-gray-800 slide_active:text-gray-800">
                "As a working mom, it's hard to find trustworthy people for household tasks. QuickFix helped me find a reliable cleaner
                who has become a regular in our home. I feel like I can finally keep up with everything!"
              </p>
            </div>
            <div class="flex items-center gap-5 border-t border-solid border-gray-200 pt-5">
              <img class="rounded-full h-10 w-10 object-cover" src="https://pagedone.io/asset/uploads/1696229969.png"
                alt="avatar" />
              <div class="block">
                <h5 class="text-gray-900 font-medium transition-all duration-500  mb-1">Samantha P.</h5>
                <span class="text-sm leading-4 text-gray-500">Working Mom</span>
              </div>
            </div>
          </div>
        </div>
        <div class="swiper-slide">
          <div
            class="group bg-white border border-solid border-gray-300 flex justify-between flex-col rounded-xl p-6 transition-all duration-500  w-full mx-auto slide_active:border-indigo-600 hover:border-indigo-600 hover:shadow-sm ">
            <div class="">
              <div class="flex items-center mb-7 gap-2 text-amber-500 transition-all duration-500  ">
                <svg class="w-5 h-5" viewBox="0 0 18 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M8.10326 1.31699C8.47008 0.57374 9.52992 0.57374 9.89674 1.31699L11.7063 4.98347C11.8519 5.27862 12.1335 5.48319 12.4592 5.53051L16.5054 6.11846C17.3256 6.23765 17.6531 7.24562 17.0596 7.82416L14.1318 10.6781C13.8961 10.9079 13.7885 11.2389 13.8442 11.5632L14.5353 15.5931C14.6754 16.41 13.818 17.033 13.0844 16.6473L9.46534 14.7446C9.17402 14.5915 8.82598 14.5915 8.53466 14.7446L4.91562 16.6473C4.18199 17.033 3.32456 16.41 3.46467 15.5931L4.15585 11.5632C4.21148 11.2389 4.10393 10.9079 3.86825 10.6781L0.940384 7.82416C0.346867 7.24562 0.674378 6.23765 1.4946 6.11846L5.54081 5.53051C5.86652 5.48319 6.14808 5.27862 6.29374 4.98347L8.10326 1.31699Z"
                    fill="currentColor" />
                </svg>
                <span class="text-base font-semibold text-indigo-600">4.9</span>
              </div>
              <p
                class="text-base text-gray-600 leading-6  transition-all duration-500 pb-8 group-hover:text-gray-800 slide_active:text-gray-800">
                "QuickFix makes it easy to find skilled workers in my area. I needed help painting my living room, and within minutes, I
                was able to book someone who did a fantastic job. This service is a game-changer for home projects!"
              </p>
            </div>
            <div class="flex items-center gap-5 pt-5 border-t border-solid border-gray-200">
              <img class="rounded-full h-10 w-10 object-cover" src="https://pagedone.io/asset/uploads/1696229994.png"
                alt="avatar" />
              <div class="block">
                <h5 class="text-gray-900 font-medium transition-all duration-500  mb-1">Oliver S.
                </h5>
                <span class="text-sm leading-4 text-gray-500">DIY Enthusiast</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="swiper-pagination swiper-pagination-second"></div>
    </div>
  </div>
</section>
<!--END OF TESTIMONIALS-->



<!-- Change the colour #f8fafc to match the previous section colour -->
<svg class="wave-top" viewBox="0 0 1439 147" version="1.1" xmlns="http://www.w3.org/2000/svg"
  xmlns:xlink="http://www.w3.org/1999/xlink">
  <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
    <g transform="translate(-1.000000, -14.000000)" fill-rule="nonzero">
      <g class="wave" fill="#f8fafc">
        <path
          d="M1440,84 C1383.555,64.3 1342.555,51.3 1317,45 C1259.5,30.824 1206.707,25.526 1169,22 C1129.711,18.326 1044.426,18.475 980,22 C954.25,23.409 922.25,26.742 884,32 C845.122,37.787 818.455,42.121 804,45 C776.833,50.41 728.136,61.77 713,65 C660.023,76.309 621.544,87.729 584,94 C517.525,105.104 484.525,106.438 429,108 C379.49,106.484 342.823,104.484 319,102 C278.571,97.783 231.737,88.736 205,84 C154.629,75.076 86.296,57.743 0,32 L0,0 L1440,0 L1440,84 Z">
        </path>
      </g>
      <g transform="translate(1.000000, 15.000000)" fill="#FFFFFF">
        <g transform="translate(719.500000, 68.500000) rotate(-180.000000) translate(-719.500000, -68.500000) ">
          <path
            d="M0,0 C90.7283404,0.927527913 147.912752,27.187927 291.910178,59.9119003 C387.908462,81.7278826 543.605069,89.334785 759,82.7326078 C469.336065,156.254352 216.336065,153.6679 0,74.9732496"
            opacity="0.100000001"></path>
          <path
            d="M100,104.708498 C277.413333,72.2345949 426.147877,52.5246657 546.203633,45.5787101 C666.259389,38.6327546 810.524845,41.7979068 979,55.0741668 C931.069965,56.122511 810.303266,74.8455141 616.699903,111.243176 C423.096539,147.640838 250.863238,145.462612 100,104.708498 Z"
            opacity="0.100000001"></path>
          <path
            d="M1046,51.6521276 C1130.83045,29.328812 1279.08318,17.607883 1439,40.1656806 L1439,120 C1271.17211,77.9435312 1140.17211,55.1609071 1046,51.6521276 Z"
            opacity="0.200000003"></path>
        </g>
      </g>
    </g>
  </g>
</svg>
<section class="container mx-auto text-center py-6 mb-12">
  <h2 class="w-full my-2 text-5xl font-bold leading-tight text-center text-white">
    Get the Job Done in No Time with Quickfix!
  </h2>
  <div class="w-full mb-4">
    <div class="h-1 mx-auto bg-white w-1/6 opacity-25 my-0 py-0 rounded-t"></div>
  </div>
  <h3 class="my-4 text-3xl leading-tight">
    Connect with skilled professionals for fast, reliable help on-demand, from cleaning to repairs. Quickfix makes finding
    short-term job support as easy as a tap
  </h3>
  <button
    class="mx-auto lg:mx-0 hover:underline bg-white text-gray-800 font-bold rounded-full my-6 py-4 px-8 shadow-lg focus:outline-none focus:shadow-outline transform transition hover:scale-105 duration-300 ease-in-out">
    Send an Email
  </button>
</section>
<!--Footer-->
<footer class="bg-white">
  <div class="container mx-auto px-8">
    <div class="w-full flex flex-col md:flex-row py-6">
      <div class="flex-1 mb-6 text-black">
        <a class="no-underline hover:no-underline flex items-center font-bold text-1xl lg:text-2xl" href="#">
          <img src="../img/logo1.png" alt="Quickfix Logo" class="h-10 lg:h-14">
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
  // Your existing scroll and dropdown toggle script
  var scrollpos = window.scrollY;
  var header = document.getElementById("header");
  var navcontent = document.getElementById("nav-content");
  var navaction = document.getElementById("navAction");
  var brandname = document.getElementById("brandname");
  var toToggle = document.querySelectorAll(".toggleColour");

  document.addEventListener("scroll", function() {
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

<!-- New Swiper script -->
<script>
  var swiper = new Swiper(".mySwiper", {
    slidesPerView: 1,
    spaceBetween: 32,
    loop: true,
    centeredSlides: true,
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
      renderBullet: function(index, className) {
        return '<span class="' + className + '">' + (index + 1) + "</span>";
      },
    },
    autoplay: {
      delay: 2500,
      disableOnInteraction: false,
    },
  });
</script>

<script>
  var swiper = new Swiper(".mySwiperSecond", {
    slidesPerView: 1,
    spaceBetween: 32,
    loop: true,
    centeredSlides: true,
    pagination: {
      el: ".swiper-pagination-second",
      clickable: true,

    },
    autoplay: {
      delay: 2500,
      disableOnInteraction: false,
    },
    breakpoints: {
      640: {
        slidesPerView: 1,
        spaceBetween: 32,
      },
      768: {
        slidesPerView: 2,
        spaceBetween: 32,
      },
      1024: {
        slidesPerView: 3,
        spaceBetween: 32,
      },
    },
  });
</script>

<script>
  // Get all accordion buttons
  const accordionButtons = document.querySelectorAll('.accordion-toggle');

  accordionButtons.forEach(button => {
    button.addEventListener('click', () => {
      const content = button.nextElementSibling;
      const isOpen = content.style.maxHeight && content.style.maxHeight !== '0px';

      // Close all accordions
      document.querySelectorAll('.accordion-content').forEach(item => {
        item.style.maxHeight = '0px';
      });

      // Open the clicked accordion
      if (!isOpen) {
        content.style.maxHeight = content.scrollHeight + 'px';
      }
    });
  });
</script>


</body>

</html>