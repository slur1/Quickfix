<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>How it Works | QuickFix</title>
    <link rel="icon" type="logo" href="../img/logo1.png">
    <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');

        body {
            font-family: 'Montserrat', sans-serif;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col">
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
                <a href="../index.php#download" class="block lg:inline text-blue-600 hover:text-blue-800 font-medium py-2 px-4">Download</a>
                <a href="./user/how_it_works.php" class="block lg:inline text-blue-600 hover:text-blue-800 font-medium py-2 px-4">How it Works</a>
                <!-- Categories Dropdown -->
                <div class="relative" @click.away="categoryOpen = false">
                    <button @click="categoryOpen = !categoryOpen"
                        class="flex items-center text-blue-600 hover:text-blue-800 font-medium py-2 px-4">
                        Categories
                        <svg class="w-4 h-4 ml-1" :class="{'transform rotate-180': categoryOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <!-- Categories Menu -->
                    <div x-show="categoryOpen"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute left-0 mt-2 w-screen max-w-md bg-white rounded-md shadow-lg z-50">
                        <!-- User Type Selection -->
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
                        <!-- Categories Grid -->
                        <div class="max-h-96 overflow-y-auto p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <template x-if="userType === 'tasker'">
                                    <!-- Job Seeker Categories -->
                                    <div>
                                        <h3 class="font-semibold text-lg text-blue-900 mb-2">Home Cleaning</h3>
                                        <div class="grid grid-cols-1 gap-2">
                                            <template x-for="category in [
                    { name: 'House Cleaner', url: './maintenance.php' },
                    { name: 'Carpet Cleaner', url: './maintenance.php' },
                    { name: 'Aircon Cleaner', url: './maintenance.php' },
                    { name: 'Laundry Helper', url: './maintenance.php' },
                    { name: 'Upholstery Cleaner', url: './maintenance.php' }
                  ]" :key="category.name">
                                                <a :href="category.url"
                                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 rounded"
                                                    x-text="category.name"></a>
                                            </template>
                                        </div>
                                        <h3 class="font-semibold text-lg text-blue-900 mt-4 mb-2">House Repair</h3>
                                        <div class="grid grid-cols-1 gap-2">
                                            <template x-for="category in [
                    { name: 'Electrical Repair', url: './maintenance.php' },
                    { name: 'Lighting Repair', url: './maintenance.php' },
                    { name: 'Wiring Repair', url: './maintenance.php' },
                    { name: 'Appliance Repair', url: './maintenance.php' },
                    { name: 'Furniture Repair', url: './maintenance.php' }
                  ]" :key="category.name">
                                                <a :href="category.url"
                                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 rounded"
                                                    x-text="category.name"></a>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="userType === 'poster'">
                                    <!-- Job Provider Categories -->
                                    <div>
                                        <h3 class="font-semibold text-lg text-blue-900 mb-2">Home Cleaning Services</h3>
                                        <div class="grid grid-cols-1 gap-2">
                                            <template x-for="category in [
                    { name: 'House Cleaning', url: './maintenance.php' },
                    { name: 'Carpet Cleaning', url: './maintenance.php' },
                    { name: 'Upholstery Cleaning', url: './maintenance.php' },
                    { name: 'Laundry Service', url: './maintenance.php' },
                    { name: 'Aircon Cleaning', url: './maintenance.php' }
                  ]" :key="category.name">
                                                <a :href="category.url"
                                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 rounded"
                                                    x-text="category.name"></a>
                                            </template>
                                        </div>
                                        <h3 class="font-semibold text-lg text-blue-900 mt-4 mb-2">Home Repair Services</h3>
                                        <div class="grid grid-cols-1 gap-2">
                                            <template x-for="category in [
                    { name: 'Electrical Repair', url: './maintenance.php' },
                    { name: 'Lighting Repair', url: './maintenance.php' },
                    { name: 'Wiring Repair', url: './maintenance.php' },
                    { name: 'Appliance Repair', url: './maintenance.php' },
                    { name: 'Furniture Repair', url: './maintenance.php' }
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

            <!-- Buttons Section -->
            <div class="flex justify-center space-x-4 py-4 lg:py-0">
                <a href="../user/user-registration.php"><button class="px-4 py-2 text-blue-600 hover:bg-blue-50 rounded-md">Sign Up</button></a>
                <a href="../user/userLogin.php"><button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Sign In</button></a>
                <a href="../job-seeker/become-a-job-seeker.php"><button class="px-4 py-2 bg-blue-800 text-white rounded-full hover:bg-blue-900">Become a Job Seeker</button></a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow">
        <!-- Hero Section -->
        <section class="bg-blue-900 text-white py-16 lg:py-24 w-full h-screen flex items-center">
            <div class="container mx-auto px-0">
                <div class="flex flex-col lg:flex-row items-center w-full">
                    <!-- Left Content -->
                    <div class="lg:w-1/2 mb-8 lg:mb-0 text-center lg:text-left">
                        <h1 class="text-4xl lg:text-6xl font-bold mb-6 leading-tight">Post a Job. Get Offers. Get it done!</h1>
                        <p class="text-xl mb-8">The best place for people to find short-term jobs</p>
                        <a href="../user/userLogin.php" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-full transition duration-300">
                            Post a Job Now!
                        </a>
                    </div>
                    <!-- Right Content -->
                    <div class="lg:w-1/2">
                        <img src="../img/done.svg" alt="Done illustration" class="w-full h-auto">
                    </div>
                </div>
            </div>
        </section>

        <!--1st div (describe div to)-->
        <div class="container mx-auto px-4 py-12 lg:py-24">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-24 items-center max-w-7xl mx-auto">
                <!-- Left side - Mobile mockup -->
                <div class="relative">
                    <div class="bg-blue-900 rounded-md p-4 pb-8 shadow-2xl max-w-md mx-auto">
                        <!-- Phone status bar -->
                        <div class="flex justify-between items-center text-white mb-4 px-4">
                            <span class="text-sm font-medium">11:50 AM</span>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 11.5a.5.5 0 01.5-.5h14a.5.5 0 010 1h-14a.5.5 0 01-.5-.5z" />
                                </svg>
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 11.5a.5.5 0 01.5-.5h14a.5.5 0 010 1h-14a.5.5 0 01-.5-.5z" />
                                </svg>
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                                </svg>
                            </div>
                        </div>
                        <!-- App content -->
                        <div class="bg-white rounded-3xl p-6 shadow-lg">
                            <!-- Back button -->
                            <button class="mb-6">
                                <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <!-- Progress bar -->
                            <div class="h-1 w-1/3 bg-blue-500 rounded mb-8"></div>
                            <!-- Form content -->
                            <div class="space-y-4">
                                <h2 class="text-2xl font-bold text-blue-900">Start with a title</h2>
                                <p class="text-gray-600">In a few words, what do you need done?</p>
                                <div class="mt-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                                    <p class="text-gray-900">Help me do my laundry</p>
                                    <div class="animate-pulse w-px h-5 bg-gray-900 inline-block ml-1"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right side - Content -->
                <div class="text-center lg:text-left">
                    <h1 class="text-4xl lg:text-6xl font-bold text-blue-900 mb-6 leading-tight">
                        Describe what you need
                    </h1>
                    <p class="text-xl text-gray-600 mb-8 max-w-2xl">
                        Describe what you need done in a few sentences. Keep it simple and clear to attract the best Job Seeker.
                    </p>
                    <a href="../user/userLogin.php" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-full transition-colors">
                        Post your Job Now!
                    </a>
                </div>
            </div>
        </div>
        <!--End of 1st div-->


        <!--2nd div (budget div to)-->
        <div class="container mx-auto px-4 py-12 lg:py-24">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-24 items-center max-w-7xl mx-auto">
                <!-- Left side - Content -->
                <div class="text-center lg:text-left">
                    <h1 class="text-4xl lg:text-6xl font-bold text-blue-900 mb-6 leading-tight">
                        Set your budget
                    </h1>
                    <p class="text-xl text-gray-600 mb-8 max-w-2xl">
                        Don't worry, you can adjust your budget later and discuss it with potential Job Seekers if needed.
                    </p>
                    <a href="../user/userLogin.php" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-full transition-colors">
                        Post your Job Now!
                    </a>
                </div>

                <!-- Right side - Mobile mockup -->
                <div class="relative">
                    <div class="bg-blue-900 rounded-md p-4 pb-8 shadow-2xl max-w-md mx-auto">
                        <!-- Phone status bar -->
                        <div class="flex justify-between items-center text-white mb-4 px-4">
                            <span class="text-sm font-medium">11:50 AM</span>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 11.5a.5.5 0 01.5-.5h14a.5.5 0 010 1h-14a.5.5 0 01-.5-.5z" />
                                </svg>
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 11.5a.5.5 0 01.5-.5h14a.5.5 0 010 1h-14a.5.5 0 01-.5-.5z" />
                                </svg>
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                                </svg>
                            </div>
                        </div>
                        <!-- App content -->
                        <div class="bg-white rounded-md p-6 shadow-lg">
                            <!-- Progress bar -->
                            <div class="h-1 w-2/3 bg-blue-500 rounded mb-8"></div>
                            <!-- Budget content -->
                            <div class="space-y-8">
                                <h2 class="text-2xl font-bold text-blue-900">Your Budget</h2>
                                <!-- Budget display -->
                                <div class="text-center">
                                    <div class="text-blue-600 text-7xl font-bold mb-4">143</div>
                                </div>
                                <!-- Number pad -->
                                <div class="grid grid-cols-3 gap-4">
                                    <button class="text-2xl font-semibold text-blue-900 p-4 rounded-lg hover:bg-gray-100 transition-colors">1</button>
                                    <button class="text-2xl font-semibold text-blue-900 p-4 rounded-lg hover:bg-gray-100 transition-colors">2</button>
                                    <button class="text-2xl font-semibold text-blue-900 p-4 rounded-lg hover:bg-gray-100 transition-colors">3</button>
                                    <button class="text-2xl font-semibold text-blue-900 p-4 rounded-lg hover:bg-gray-100 transition-colors">4</button>
                                    <button class="text-2xl font-semibold text-blue-900 p-4 rounded-lg hover:bg-gray-100 transition-colors">5</button>
                                    <button class="text-2xl font-semibold text-blue-900 p-4 rounded-lg hover:bg-gray-100 transition-colors">6</button>
                                    <button class="text-2xl font-semibold text-blue-900 p-4 rounded-lg hover:bg-gray-100 transition-colors">7</button>
                                    <button class="text-2xl font-semibold text-blue-900 p-4 rounded-lg hover:bg-gray-100 transition-colors">8</button>
                                    <button class="text-2xl font-semibold text-blue-900 p-4 rounded-lg hover:bg-gray-100 transition-colors">9</button>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end ng 2nd div (budget div to)-->


        <!--3rd div (offers div to)-->
        <div class="container mx-auto px-4 py-12 lg:py-24">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-24 items-center max-w-7xl mx-auto">
                <!-- Left side - Mobile UI -->
                <div class="bg-blue-900 rounded-md p-4 pb-8 shadow-2xl max-w-md mx-auto">
                    <div class="bg-white rounded-3xl p-4 max-w-sm mx-auto">
                        <!-- Status bar -->
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-sm">11:50 AM</span>
                            <button class="back-button">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                        </div>

                        <!-- Notification -->
                        <div class="bg-[#e8ffd6] text-gray-800 p-2 rounded-full mb-4 flex items-center justify-center gap-2">
                            <span class="text-sm">2 New offers received!</span>
                        </div>

                        <!-- Task title -->
                        <h1 class="text-3xl font-bold text-blue-900 mb-6">House Cleaner Needed!</h1>

                        <!-- Tasker list -->
                        <div class="space-y-4">
                            <!-- Tasker 1 -->
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <img src="../img/boss.svg?height=40&width=40" alt="Joshua M." class="w-10 h-10 rounded-full object-cover" />
                                    <span class="font-medium">Je G.</span>
                                </div>
                                <button class="bg-blue-500 text-white px-4 py-2 rounded-full hover:bg-blue-600 transition-colors">
                                    Assign
                                </button>
                            </div>

                            <!-- Tasker 2 -->
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <img src="../img/boss.svg?height=40&width=40" alt="Henry P." class="w-10 h-10 rounded-full object-cover" />
                                    <span class="font-medium">Je C.</span>
                                </div>
                                <button class="bg-blue-500 text-white px-4 py-2 rounded-full hover:bg-blue-600 transition-colors">
                                    Assign
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right side - Content -->
                <div class="space-y-6">
                    <h2 class="text-blue-900 text-4xl md:text-5xl font-bold leading-tight">
                        Receive Offers and pick the most suitable Job Seeker
                    </h2>
                    <p class="text-gray-800 text-lg leading-relaxed">
                        Review profiles, ratings, completion rates, and reviews to find the Job Seeker who fits your needs. Once the job is complete, show your appreciation by releasing payment and leaving a review to highlight the great work they’ve done.
                    </p>
                    <button class="bg-blue-50 text-blue-500 px-6 py-3 rounded-full hover:bg-blue-100 transition-colors text-lg font-medium">
                        Post your Job Now!
                    </button>
                </div>
            </div>
            <!--3rd div end (offers div to)-->




            <div class="max-w-6xl mx-auto mt-16 px-4">
                <h2 class="text-blue-900 text-5xl font-bold mb-4">Your to-do list, our priority</h2>
                <p class="text-gray-800 text-lg mb-8">From house cleaning to home repairs, find the reliable help you need on QuickFix.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Laundry -->
                    <div class="space-y-3 text-center">
                        <div class="rounded-2xl overflow-hidden h-48 w-full">
                            <img src="../img/laundry.svg" alt="Laundry services" class="w-full h-full object-contain" />
                        </div>
                        <h3 class="text-blue-900 text-xl font-semibold">Laundry</h3>
                    </div>

                    <!-- Lighting Repair -->
                    <div class="space-y-3 text-center">
                        <div class="rounded-2xl overflow-hidden h-48 w-full">
                            <img src="../img/lighting.svg" alt="Lighting Repair services" class="w-full h-full object-contain" />
                        </div>
                        <h3 class="text-blue-900 text-xl font-semibold">Lighting Repair</h3>
                    </div>

                    <!-- Deep Cleaning -->
                    <div class="space-y-3 text-center">
                        <div class="rounded-2xl overflow-hidden h-48 w-full">
                            <img src="../img/cleaning-service.svg" alt="Deep Cleaning services" class="w-full h-full object-contain" />
                        </div>
                        <h3 class="text-blue-900 text-xl font-semibold">Deep Cleaning</h3>
                    </div>

                    <!-- Regular Cleaning -->
                    <div class="space-y-3 text-center">
                        <div class="rounded-2xl overflow-hidden h-48 w-full">
                            <img src="../img/cleaning1.svg" alt="Regular Cleaning services" class="w-full h-full object-contain" />
                        </div>
                        <h3 class="text-blue-900 text-xl font-semibold">Regular Cleaning</h3>
                    </div>

                    <!-- Electrical Repair -->
                    <div class="space-y-3 text-center">
                        <div class="rounded-2xl overflow-hidden h-48 w-full">
                            <img src="../img/electrical-repair.svg" alt="Electrical Repair services" class="w-full h-full object-contain" />
                        </div>
                        <h3 class="text-blue-900 text-xl font-semibold">Electrical Repair</h3>
                    </div>

                    <!-- Wiring Repair -->
                    <div class="space-y-3 text-center">
                        <div class="rounded-2xl overflow-hidden h-48 w-full">
                            <img src="../img/wiring-repair.svg" alt="Wiring Repair services" class="w-full h-full object-contain" />
                        </div>
                        <h3 class="text-blue-900 text-xl font-semibold">Wiring Repair</h3>
                    </div>

                    <!-- Appliance Repair -->
                    <div class="space-y-3 text-center">
                        <div class="rounded-2xl overflow-hidden h-48 w-full">
                            <img src="../img/appliance.svg" alt="Appliance Repair services" class="w-full h-full object-contain" />
                        </div>
                        <h3 class="text-blue-900 text-xl font-semibold">Appliance Repair</h3>
                    </div>

                    <!-- Furniture Repair -->
                    <div class="space-y-3 text-center">
                        <div class="rounded-2xl overflow-hidden h-48 w-full">
                            <img src="../img/furniture.svg" alt="Furniture Repair services" class="w-full h-full object-contain" />
                        </div>
                        <h3 class="text-blue-900 text-xl font-semibold">Furniture Repair</h3>
                    </div>
                </div>
            </div>




            <!--5th div (ratings div to)-->
            <div class="max-w-6xl mx-auto mt-24 px-4 grid md:grid-cols-2 gap-12 items-center">
                <div class="relative">
                    <!-- Rating Card -->
                    <div class="absolute -top-8 left-8 bg-white rounded-2xl p-4 shadow-lg z-10">
                        <div class="flex items-center gap-2">
                            <span class="text-3xl font-bold text-blue-900">5.0</span>
                            <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </div>
                        <div class="text-gray-600">Overall rating</div>
                        <div class="text-sm text-gray-500">540 ratings</div>
                    </div>

                    <!-- Main Image -->
                    <div class="rounded-2xl overflow-hidden">
                        <img src="../img/lighting.svg" alt="Tasker working" class="w-full h-full object-cover" />
                    </div>

                    <!-- Review Card -->
                    <div class="absolute -bottom-8 right-8 bg-white rounded-2xl p-4 shadow-lg max-w-sm">
                        <div class="flex items-center gap-2 mb-2">
                            <img src="../img/lighting.svg" alt="Tommy" class="w-8 h-8 rounded-full" />
                            <span class="font-medium text-blue-900">Je's review</span>
                        </div>
                        <p class="text-gray-600 text-sm">Highly recommend. Je is a highly skilled house cleaner and took great care within my home.</p>
                    </div>
                </div>

                <div>
                    <h2 class="text-blue-900 text-4xl font-bold mb-4">Ratings & reviews</h2>
                    <p class="text-gray-800 text-lg mb-6">Explore the Job Seeker's portfolio and skills showcased on their profile, along with their verified ratings, reviews, and completion rate on jobs they've successfully completed through QuickFix. This ensures you’re confident in choosing the right person for your job.</p>
                    <button class="bg-blue-50 text-blue-500 px-6 py-3 rounded-full hover:bg-blue-100 transition-colors">Get Started</button>
                </div>
            </div>
            <!--5th div end (ratings div to)-->

            <!--6th div (communication div to)-->
            <div class="max-w-6xl mx-auto mt-24 px-4 grid md:grid-cols-2 gap-12 items-center">
                <!-- Right Content (Now on the Left) -->
                <div>
                    <h2 class="text-blue-900 text-4xl font-bold mb-4">Communication</h2>
                    <p class="text-gray-800 text-lg mb-6">With QuickFix, you can stay connected from the moment your job is posted until it's completed. Once you accept an offer, privately message the Job Seeker to finalize the details and ensure your job gets done smoothly.</p>
                    <button class="bg-blue-50 text-blue-500 px-6 py-3 rounded-full hover:bg-blue-100 transition-colors">Get started</button>
                </div>

                <!-- Left Content (Now on the Right) -->
                <div class="bg-blue-900 rounded-3xl p-8">
                    <div class="bg-white rounded-2xl p-6 max-w-sm mx-auto">
                        <!-- Message 1 -->
                        <div class="flex gap-3 mb-4">
                            <img src="../img/boss.svg?height=40&width=40" alt="User" class="w-10 h-10 rounded-full" />
                            <div class="bg-gray-100 rounded-2xl p-4">
                                <p class="text-blue-900 font-medium mb-1">Hi Je!</p>
                                <p class="text-gray-700">Can you clean up my room?</p>
                            </div>
                        </div>

                        <!-- Message 2 -->
                        <div class="flex gap-3 justify-end">
                            <div class="bg-blue-900 text-white rounded-2xl p-4">
                                <p>Yes sir, I'll be there at 9:00 AM in the morning.</p>
                            </div>
                            <img src="../img/boss.svg?height=40&width=40" alt="Henry" class="w-10 h-10 rounded-full" />
                        </div>
                    </div>
                </div>
            </div>
            <!--6th div end (communication div to)-->
    </main>

    <!--Footer-->
    <footer class="bg-gray-100">
        <div class="container mx-auto px-8">
            <div class="w-full flex flex-col md:flex-row py-6">
                <div class="flex-1 mb-6 text-blue-900">
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
        // Add functionality for number pad
        const buttons = document.querySelectorAll('button');
        let budget = '290';

        buttons.forEach(button => {
            button.addEventListener('click', () => {
                if (budget === '290') {
                    budget = button.textContent;
                } else {
                    budget += button.textContent;
                }
                document.querySelector('.text-7xl').textContent = budget;
            });
        });
    </script>



    <script>
        // Add click handlers for assign buttons
        document.querySelectorAll('button').forEach(button => {
            if (button.textContent.trim() === 'Assign') {
                button.addEventListener('click', function() {
                    this.textContent = 'Assigned';
                    this.classList.remove('bg-blue-500', 'hover:bg-blue-600');
                    this.classList.add('bg-blue-900');
                });
            }
        });

        // Back button functionality
        document.querySelector('.back-button').addEventListener('click', () => {
            alert('Back button clicked');
        });
    </script>




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

</body>

</html>