<?php
include '../config/db_connection.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', '../logs/php-error.log');


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];


$locationiq_api_key = "pk.8d6caae95f83ef55c3b74214f9f81424";


function getCoordinates($job_id, $address, $locationiq_api_key)
{
    global $conn;

    $stmt = $conn->prepare("SELECT latitude, longitude FROM jobs WHERE id = ?");
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (!empty($row['latitude']) && !empty($row['longitude'])) {
            return ['latitude' => $row['latitude'], 'longitude' => $row['longitude']];
        }
    }

    if (!empty($address)) {
        $encoded_address = urlencode($address);
        $url = "https://us1.locationiq.com/v1/search.php?key=$locationiq_api_key&q=$encoded_address&format=json&limit=1";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        if (!empty($data) && isset($data[0]['lat'], $data[0]['lon'])) {
            $latitude = $data[0]['lat'];
            $longitude = $data[0]['lon'];

            $stmt = $conn->prepare("UPDATE jobs SET latitude = ?, longitude = ? WHERE id = ?");
            $stmt->bind_param("ddi", $latitude, $longitude, $job_id);
            $stmt->execute();

            return ['latitude' => $latitude, 'longitude' => $longitude];
        }
    }

    return ['latitude' => null, 'longitude' => null];
}

$sql = "SELECT jobs.id, jobs.user_id, jobs.job_title, jobs.job_date, jobs.job_time, jobs.location, 
               jobs.budget, jobs.images, 
               jobs.category_id, jobs.sub_category_id, 
               categories.name AS category_name, sub_categories.name AS sub_category_name,
               user.first_name, user.last_name, user.verification_status, user.profile_picture,
               (SELECT COUNT(*) FROM offers WHERE offers.job_id = jobs.id AND offers.provider_id = ?) AS has_offered
        FROM jobs 
        JOIN user ON jobs.user_id = user.id
        LEFT JOIN categories ON jobs.category_id = categories.id 
        LEFT JOIN sub_categories ON jobs.sub_category_id = sub_categories.id
        WHERE jobs.status NOT IN ('in_progress', 'completed')  
        ORDER BY has_offered DESC, jobs.job_date DESC";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();


$jobs = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['budget'] = $row['budget']; // Keep budget as a string (e.g., "200-500")
        $row['has_offered'] = intval($row['has_offered']);
        
        // Fetch coordinates based on the address
        if (!empty($row['location'])) {
            $coords = getCoordinates($row['id'], $row['location'], $locationiq_api_key);
            $row['latitude'] = $coords['latitude'];
            $row['longitude'] = $coords['longitude'];
        } else {
            $row['latitude'] = null;
            $row['longitude'] = null;
        }

        // Fetch offers for this job
        $offerSql = "SELECT offers.id, offers.provider_id, offers.offer_amount, offers.message
                     FROM offers WHERE job_id = ?";
        $offerStmt = $conn->prepare($offerSql);
        $offerStmt->bind_param("i", $row['id']);
        $offerStmt->execute();
        $offerResult = $offerStmt->get_result();
        $offers = [];
        while ($offerRow = $offerResult->fetch_assoc()) {
            $offers[] = $offerRow;
        }
        $row['offers'] = $offers;

        // Fetch comments for this job (including user details)
        $commentSql = "SELECT comments.id, comments.comment, comments.created_at, 
                            user.id AS user_id, user.first_name, user.last_name, 
                            user.profile_picture, user.verification_status
                    FROM comments
                    JOIN user ON comments.user_id = user.id
                    WHERE comments.job_id = ?";

        $commentStmt = $conn->prepare($commentSql);
        if (!$commentStmt) {
            echo json_encode(["error" => "SQL Error: " . $conn->error]);
            exit;
        }
        $commentStmt->bind_param("i", $row['id']);
        $commentStmt->execute();
        $commentResult = $commentStmt->get_result();

        $comments = [];
        while ($commentRow = $commentResult->fetch_assoc()) {
            $comments[] = [
                'id' => $commentRow['id'],
                'comment' => $commentRow['comment'],
                'created_at' => $commentRow['created_at'],
                'user_id' => $commentRow['user_id'],
                'first_name' => $commentRow['first_name'],
                'last_name' => $commentRow['last_name'],
                'profile_picture' => $commentRow['profile_picture'] ?: 'default-profile.png',
                'verification_status' => $commentRow['verification_status'] ?: 'not_verified'
            ];
        }
        $row['comments'] = count($comments) > 0 ? $comments : []; // Ensure it's an array


        $jobs[] = $row;
    }
}

$sql = "SELECT * FROM user WHERE id = $user_id AND take_assesment = 1";
$take_assesmentresult = $conn->query($sql);

$conn->close();



?>

<style>
    [x-cloak] {
        display: none !important;
    }

    .bg-green-200 {
        background-color: #e2f7e3;
    }

    .bg-green-500 {
        background-color: #48bb78;
    }
</style>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Jobs | QuickFix</title>
    <link rel="icon" type="image/png" href="../img/logo1.png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap');

        body {
            font-family: 'Montserrat', sans-serif;
        }
        #loader-overlay {
            position: fixed;
            top: 0; left: 0;
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
            animation: fadeIn 0.4s forwards; /* Faster fadeIn */
            animation-delay: calc(var(--index) * 0.15s); /* Faster delay between letters */
        }
        .fadeOut { animation: fadeOut 0.7s forwards; }

        @keyframes heartbeat {
            0% { transform: scale(1); }
            14% { transform: scale(1.2); }
            28% { transform: scale(1); }
            42% { transform: scale(1.2); }
            70% { transform: scale(1); }
        }
        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(10px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeOut {
            0% { opacity: 1; transform: translateY(0); }
            100% { opacity: 0; transform: translateY(-10px); }
        }

        @media (max-width: 480px) {
            .loader-container { width: 80vw !important; height: auto; }
            .logo { width: 70%; height: 70%; margin-bottom: 30px; }
            .letter { font-size: 28px; }
        }
    </style>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" defer></script>


    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous" defer></script>
</head>

<body>
<!-- loader.php -->
<div id="loader-overlay">
    <div class="loader-container">
        <img src="../img/logo1.png" alt="Logo" class="logo">
        <div class="text-container">
            <span class="letter" style="--index: 1;">Q</span>
            <span class="letter" style="--index: 2;">U</span>
            <span class="letter" style="--index: 3;">I</span>
            <span class="letter" style="--index: 4;">C</span>
            <span class="letter" style="--index: 5;">K</span>
            <span class="letter" style="--index: 6;">F</span>
            <span class="letter" style="--index: 7;">I</span>
            <span class="letter" style="--index: 8;">X</span>
        </div>
    </div>
</div>
<div id="main-content" style="display:none;"></div>
    <?php include './userHeader.php'; ?>


    <?php if ($take_assesmentresult->num_rows > 0) { ?>
    <section class="bg-white shadow px-1 py-1 flex flex-col md:flex-row md:items-center space-y-2 md:space-y-0 md:space-x-4">

        <div class="flex-1 max-w-full md:max-w-md">
            <input
                type="text"
                placeholder="Search for a Job"
                class="w-full border rounded px-4 py-2 focus:ring focus:ring-blue-300" />
        </div>

        <div class="flex flex-wrap items-center gap-2 md:gap-4">

            <div x-data="{ open: false, selectedCategories: [] }" class="relative">

                <button @click="open = !open" class="text-gray-700 hover:text-blue-500 flex items-center space-x-2 text-sm md:text-base">
                    <span>Category</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div
                    x-show="open"
                    @click.away="open = false"
                    class="absolute mt-2 bg-white border rounded shadow-lg w-[90vw] md:w-96 p-4 z-50 left-0 md:left-auto"
                    style="display: none;">

                    <div class="flex items-center justify-between border-b pb-2">
                        <h3 class="text-sm font-medium text-gray-600">All Categories</h3>
                        <button @click="selectedCategories = []" class="text-blue-500 text-sm">Clear all</button>
                    </div>

                    <div class="mt-2">
                        <input
                            type="text"
                            placeholder="Search categories"
                            class="w-full border rounded px-3 py-2 text-sm focus:ring focus:ring-blue-300" />
                    </div>

                    <div class="flex flex-col md:flex-row md:items-start md:space-x-8 space-y-4 md:space-y-0 mt-2">

                        <div>

                            <h3 class="text-lg font-semibold text-blue-800 mb-2">Home Cleaning</h3>

                            <ul class="space-y-2 text-sm text-gray-700">
                                <template x-for="category in ['Laundry', 'Upholstery Cleaning', 'Regular Cleaning', 'Deep Cleaning', 'Carpet Cleaning', 'Aircon Cleaning']">
                                    <li>
                                        <label class="flex items-center space-x-2">
                                            <input
                                                type="checkbox"
                                                :value="category"
                                                @change="(e) => { if(e.target.checked) selectedCategories.push(category); else selectedCategories = selectedCategories.filter(c => c !== category); }"
                                                class="form-checkbox" />
                                            <span x-text="category"></span>
                                        </label>
                                    </li>
                                </template>
                            </ul>
                        </div>


                        <div>

                            <h3 class="text-lg font-semibold text-blue-800 mb-2">House Repair</h3>

                            <ul class="space-y-2 text-sm text-gray-700">
                                <template x-for="category in ['Electrical Repair', 'Lighting Repair', 'Wiring Repair', 'Appliance Repair', 'Furniture Repair']">
                                    <li>
                                        <label class="flex items-center space-x-2">
                                            <input
                                                type="checkbox"
                                                :value="category"
                                                @change="(e) => { if(e.target.checked) selectedCategories.push(category); else selectedCategories = selectedCategories.filter(c => c !== category); }"
                                                class="form-checkbox" />
                                            <span x-text="category"></span>
                                        </label>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>



                    <div class="flex justify-between mt-4">
                        <button
                            @click="open = false"
                            class="px-4 py-2 bg-gray-100 text-gray-600 font-medium rounded-md hover:bg-gray-200 text-sm">
                            Cancel
                        </button>
                        <button class="px-4 py-2 bg-blue-500 text-white font-medium rounded-md hover:bg-blue-600 text-sm">Apply</button>
                    </div>
                </div>
            </div>


            <div x-data="{ open: false }" class="relative">

                <button @click="open = !open" class="text-gray-700 hover:text-blue-500 flex items-center space-x-2 text-sm md:text-base">
                    <span>Location</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>


                <div
                    x-show="open"
                    x-transition
                    @click.away="open = false"
                    class="absolute z-10 mt-2 left-0 bg-white border rounded-lg shadow-md p-4 w-64"
                    style="display: none;">

                    <div class="space-y-4">

                        <div>
                            <p class="text-gray-600 font-medium mb-2 text-sm">Barangay</p>
                            <input
                                type="text"
                                placeholder="Deparo"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" />
                        </div>

                        <div>
                            <p class="text-gray-600 font-medium mb-2 text-sm">Distance</p>
                            <div class="flex items-center space-x-2">
                                <input
                                    type="range"
                                    min="0"
                                    max="5"
                                    value="0"
                                    step="1"
                                    class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer focus:outline-none"
                                    @input="updateDistanceLabel()" />
                                <span id="distanceLabel" class="text-gray-700 text-sm">5km</span>
                            </div>
                        </div>

                        <div class="flex justify-between mt-4">
                            <button
                                @click="open = false"
                                class="px-4 py-2 bg-gray-100 text-gray-600 font-medium rounded-md hover:bg-gray-200 text-sm">
                                Cancel
                            </button>
                            <button class="px-4 py-2 bg-blue-500 text-white font-medium rounded-md hover:bg-blue-600 text-sm">Apply</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2 md:gap-4">

                <div x-data="{ open: false, priceIndex: 0, priceRanges: ['₱50 - ₱100/hour', '₱50 - ₱150/hour', '₱50 - ₱200/hour', '₱50 - ₱250/hour', '₱50 - ₱300/hour', '₱50 - ₱350/hour', '₱50 - ₱400/hour', '₱50 - ₱450/hour', '₱50 - ₱500/hour'] }" class="relative">

                    <button @click="open = !open" class="text-gray-700 hover:text-blue-500 flex items-center space-x-2 text-sm md:text-base">
                        <span>Prices</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div
                        x-show="open"
                        x-transition
                        @click.away="open = false"
                        class="absolute z-10 mt-2 left-0 bg-white border rounded-lg shadow-md p-4 w-64"
                        style="display: none;">
                        <div>
                            <p class="text-gray-600 font-medium mb-2 text-sm">Job Price</p>
                            <div class="flex flex-col items-center space-y-4">

                                <input
                                    type="range"
                                    min="0"
                                    max="8"
                                    step="1"
                                    x-model="priceIndex"
                                    class="w-full h-2 bg-blue-300 rounded-lg appearance-none cursor-pointer focus:outline-none" />

                                <span class="text-gray-700 font-bold text-base md:text-lg" x-text="priceRanges[priceIndex]"></span>
                            </div>
                        </div>

                        <div class="flex justify-between mt-4">
                            <button
                                @click="open = false"
                                class="px-4 py-2 bg-gray-100 text-gray-600 font-medium rounded-md hover:bg-gray-200 text-sm">
                                Cancel
                            </button>
                            <button
                                @click="open = false"
                                class="px-4 py-2 bg-blue-500 text-white font-medium rounded-md hover:bg-blue-600 text-sm">
                                Apply
                            </button>
                        </div>
                    </div>
                </div>

                <div x-data="{ open: false, availableTasksOnly: true, noOffersOnly: false }" class="relative">

                    <button @click="open = !open" class="text-gray-700 hover:text-blue-500 flex items-center space-x-2 text-sm md:text-base">
                        <span>Other Filters</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div
                        x-show="open"
                        x-transition
                        @click.away="open = false"
                        class="absolute z-10 mt-2 left-0 bg-white border rounded-lg shadow-md p-4 w-72"
                        style="display: none;">

                        <div>
                            <p class="text-gray-600 font-medium mb-4 text-sm">OTHER FILTERS</p>

                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-gray-800 font-medium text-sm">Available jobs only</p>
                                    <p class="text-gray-500 text-xs">Hide jobs that are already assigned</p>
                                </div>
                                <button
                                    @click="availableTasksOnly = !availableTasksOnly"
                                    :class="availableTasksOnly ? 'bg-blue-950' : 'bg-gray-300'"
                                    class="w-10 h-5 flex items-center rounded-full p-1 transition duration-300">
                                    <div
                                        :class="availableTasksOnly ? 'translate-x-5' : 'translate-x-0'"
                                        class="w-4 h-4 bg-white rounded-full shadow-md transform transition duration-300"></div>
                                </button>
                            </div>

                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-800 font-medium text-sm">Jobs with no offers only</p>
                                    <p class="text-gray-500 text-xs">Hide jobs that have offers</p>
                                </div>
                                <button
                                    @click="noOffersOnly = !noOffersOnly"
                                    :class="noOffersOnly ? 'bg-blue-950' : 'bg-gray-300'"
                                    class="w-10 h-5 flex items-center rounded-full p-1 transition duration-300">
                                    <div
                                        :class="noOffersOnly ? 'translate-x-5' : 'translate-x-0'"
                                        class="w-4 h-4 bg-white rounded-full shadow-md transform transition duration-300"></div>
                                </button>
                            </div>
                        </div>

                        <div class="flex justify-between mt-6">
                            <button
                                @click="open = false"
                                class="px-4 py-2 bg-gray-100 text-gray-600 font-medium rounded-md hover:bg-gray-200 text-sm">
                                Cancel
                            </button>
                            <button
                                @click="open = false"
                                class="px-4 py-2 bg-blue-500 text-white font-medium rounded-md hover:bg-blue-600 text-sm">
                                Apply
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div x-data="{ open: false, sortOptions: ['Price: High to low', 'Price: Low to high', 'Due date: Earliest', 'Due date: Latest', 'Newest jobs', 'Oldest jobs', 'Closest to me'], selectedSort: 'Sort' }" class="relative">

                <button @click="open = !open" class="text-gray-700 hover:text-blue-500 flex items-center space-x-2 text-sm md:text-base">
                    <span x-text="selectedSort"></span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div
                    x-show="open"
                    x-transition
                    @click.away="open = false"
                    class="absolute z-10 mt-2 right-0 bg-white border rounded-lg shadow-md p-4 w-64"
                    style="display: none;">
                    <ul>
                        <template x-for="(option, index) in sortOptions" :key="index">
                            <li
                                @click="selectedSort = option; open = false"
                                class="px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-blue-500 cursor-pointer rounded-md text-sm">
                                <span x-text="option"></span>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
        </div>
    </section>

<div class="flex flex-col lg:flex-row w-full p-2 md:p-4 bg-blue-50 min-h-screen"
    x-data='{
        jobs: <?php echo htmlspecialchars(json_encode($jobs), ENT_QUOTES, "UTF-8"); ?>, 
        selectedJob: null, 
        open: false, 
        enlargedImage: null,

        withdrawOffer(jobId) {
            Swal.fire({
                title: "Are you sure?",
                text: "Do you really want to withdraw your offer?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, withdraw it!",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("withdraw_offer.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify({ job_id: jobId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            let job = this.jobs.find(j => j.id === jobId);
                            if (job) job.has_offered = 0;

                            Swal.fire({
                                title: "Withdrawn!",
                                text: "Your offer has been successfully withdrawn.",
                                icon: "success",
                                confirmButtonColor: "#28a745"
                            });
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: "Failed to withdraw offer: " + data.error,
                                icon: "error",
                                confirmButtonColor: "#d33"
                            });
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        Swal.fire({
                            title: "Error!",
                            text: "Something went wrong. Please try again.",
                            icon: "error",
                            confirmButtonColor: "#d33"
                        });
                    });
                }
            });
        }
    }'>
    
    


    <div x-data="{
    showMyJobs: false,
    userId: <?php echo $_SESSION['user_id']; ?>, 
    get filteredJobs() {
        return this.showMyJobs 
            ? jobs.filter(job => job.user_id === this.userId)  // Show only user's jobs when toggled
            : jobs.filter(job => job.user_id !== this.userId); // Hide user's jobs in default view
    }
        }" class="w-full lg:w-1/4 bg-white shadow-md rounded-xl p-4 h-[300px] lg:h-[550px] overflow-y-auto border border-gray-400 mb-4 lg:mb-0">

            <h2 class="text-xl font-bold text-blue-800 mb-3 flex items-center">
                <img src="../img/pin.svg" alt="Pin Icon" class="w-6 h-6 mr-2"> Available Jobs
            </h2>

        <!-- My Posted Jobs Toggle Button -->
        <button @click="showMyJobs = !showMyJobs" 
            class="px-3 py-1 text-xs font-semibold rounded border border-blue-600 text-blue-600 
                hover:bg-blue-600 hover:text-white transition">
            <span x-text="showMyJobs ? 'All Jobs' : 'My Jobs'"></span>
        </button>
        

    <ul class="space-y-3">
        <template x-for="job in filteredJobs" :key="job.id">
            <li @click="
                selectedJob = (selectedJob && selectedJob.id === job.id) ? null : job;
                localStorage.setItem('selectedJobId', selectedJob ? selectedJob.id : '');
            "
                class="p-3 md:p-4 rounded-lg cursor-pointer transition duration-200 shadow-sm border border-gray-300 
                    bg-gray-100 hover:bg-blue-100 hover:border-blue-400 transform hover:scale-[1.03] hover:shadow-md relative"
                :class="{
                    'bg-green-200': job.has_offered,  
                    'bg-blue-600 text-white scale-[1.05] shadow-lg': selectedJob && selectedJob.id === job.id
                }"
                :id="'job-' + job.id">

                <!-- Budget Tag -->
                <span class="absolute top-2 right-3 text-xs font-bold px-2 md:px-3 py-1 rounded-lg transition duration-200"
                    :class="selectedJob && selectedJob.id === job.id ? 'bg-white text-blue-800' : 'bg-blue-800 text-white'">
                    ₱<span x-text="formatBudget(job.budget)"></span>
                </span>

                <div class="flex items-start">
                    <div class="flex-1">
                        <h3 class="text-sm md:text-md font-semibold flex items-center">
                            <img src="../img/location-info.svg" alt="Loc Icon" class="w-4 h-4 md:w-5 md:h-5 mr-1">
                            <span x-text="job.job_title" class="text-blue-800"></span>
                        </h3>
                        <p class="text-xs flex items-center mt-1">
                            <span x-text="job.location"></span>
                        </p>
                    </div>
                </div>

                <div x-show="job.has_offered" class="mt-2 flex flex-wrap items-center gap-2">
                    <span class="text-xs font-bold px-2 md:px-3 py-1 rounded-lg bg-green-500 text-white">
                        Offer Submitted
                    </span>
                    <button @click="withdrawOffer(job.id)"
                        class="text-xs font-bold px-2 py-1 rounded-lg bg-red-500 text-white hover:bg-red-600 transition">
                        Withdraw Offer
                    </button>
                </div>

            </li>
        </template>
    </ul>
</div>


        <div class="w-full lg:w-1/3 bg-white shadow-xl rounded-2xl p-4 md:p-5 h-auto lg:h-[550px] overflow-y-auto transition-all duration-300 
            transform scale-100 hover:scale-[1.02] backdrop-blur-lg border border-gray-300 mb-4 lg:mb-0 lg:mx-4"
            x-show="selectedJob" x-transition x-data="{ showPosterProfile: false, profileHover: false }">
    
    <template x-if="selectedJob">
        <div class="relative">
            <button @click="selectedJob = null"
                class="absolute top-2 right-4 text-gray-500 hover:text-red-500 transition duration-200 text-sm">
                ✖ Close
            </button>
            
            <h2 class="text-lg md:text-xl font-bold text-blue-800" x-text="selectedJob.job_title"></h2>

            <!-- Profile Section with Verification Badge & Mini Profile Toggle -->
            <div class="flex items-center gap-3 mt-2 relative">
                <img :src="selectedJob.profile_picture || 'default-profile.png'" class="w-10 h-10 rounded-full border shadow"
                    :class="{
                        'ring-2 ring-blue-500': selectedJob.verification_status === 'fully_verified',
                        'ring-2 ring-green-500': selectedJob.verification_status === 'identity_verified',
                        'ring-2 ring-red-500': selectedJob.verification_status === 'not_verified'
                    }">
                <div>
                    <p class="text-xs md:text-sm font-semibold text-blue-800 relative">
                        <a @mouseenter="showPosterProfile = true" 
                           @mouseleave="setTimeout(() => { if (!profileHover) showPosterProfile = false; }, 200)" 
                           @click="showPosterProfile = !showPosterProfile"
                           class="cursor-pointer hover:underline">
                            <span x-text="selectedJob.first_name + ' ' + selectedJob.last_name"></span>
                        </a>

                        <!-- Mini Profile Popup -->
                        <div x-show="showPosterProfile" x-transition.opacity x-cloak
                            class="absolute top-8 left-0 bg-gradient-to-b from-blue-50 to-white shadow-2xl border border-gray-200/60 
                                   rounded-2xl p-4 w-72 z-50 transition-all duration-300 ease-in-out transform origin-top scale-95 hover:scale-100"
                            @mouseenter="profileHover = true"
                            @mouseleave="profileHover = false; setTimeout(() => showPosterProfile = false, 200)">

                            <div class="flex items-center gap-4">
                                <img :src="selectedJob.profile_picture || 'default-profile.png'" alt="Profile Picture"
                                    class="w-16 h-16 rounded-full shadow-md"
                                    :class="{
                                        'ring-4 ring-blue-500': selectedJob.verification_status === 'fully_verified',
                                        'ring-4 ring-green-500': selectedJob.verification_status === 'identity_verified',
                                        'ring-4 ring-red-500': selectedJob.verification_status === 'not_verified'
                                    }">
                                <div>
                                    <h3 class="text-md font-semibold text-gray-800" x-text="selectedJob.first_name + ' ' + selectedJob.last_name"></h3>
                                    
                                    <!-- Verification Badge -->
                                    <p class="text-xs flex items-center gap-1 mt-1 text-gray-600">
                                        <i class="fas"
                                            :class="{
                                                'fa-shield-check text-blue-500': selectedJob.verification_status === 'fully_verified',
                                                'fa-user-check text-green-500': selectedJob.verification_status === 'identity_verified',
                                                'fa-exclamation-circle text-red-500': selectedJob.verification_status === 'not_verified'
                                            }"></i>
                                        <span x-text="selectedJob.verification_status === 'fully_verified' ? 'Fully Verified' :
                                                    selectedJob.verification_status === 'identity_verified' ? 'Identity Verified' : 'Not Verified'"></span>
                                    </p>
                                </div>
                            </div>

                            <!-- "View Profile" Button -->
                            <a :href="'public_profile.php?user_id=' + selectedJob.user_id + '&job_id=' + selectedJob.id"
                                class="block mt-3 text-xs text-blue-600 hover:underline text-center">
                                View Full Profile
                            </a>
                        </div>
                    </p>

                    <!-- Verification Badge -->
                    <p class="text-xs px-2 py-1 rounded text-white inline-block"
                        :class="{
                            'bg-blue-500': selectedJob.verification_status === 'fully_verified',
                            'bg-green-500': selectedJob.verification_status === 'identity_verified',
                            'bg-red-500': selectedJob.verification_status === 'not_verified'
                        }">
                        <span x-text="selectedJob.verification_status === 'fully_verified' ? 'Fully Verified' :
                                    selectedJob.verification_status === 'identity_verified' ? 'Identity Verified' : 'Not Verified'">
                        </span>
                    </p>
                </div>
            </div>

            <p class="text-gray-700 text-xs mt-1" x-text="selectedJob.location"></p>
            <p class="text-gray-600 text-xs mt-1 flex items-center">
                <img src="../img/date-range-job.svg" alt="Date Icon" class="w-4 h-4 mr-1">
                <span x-text="selectedJob.job_date"></span>
                <span class="mx-2">|</span>
                <img src="../img/time-job.svg" alt="Time Icon" class="w-4 h-4 mr-1">
                <span x-text="selectedJob.job_time"></span>
            </p>

                    <p class="text-xs md:text-sm font-semibold text-gray-700 mt-3 flex items-center">
                <img src="../img/category-job.svg" alt="Category Icon" class="w-4 h-4 md:w-5 md:h-5 mr-1">
                Category: <span class="text-blue-800 font-bold ml-1" x-text="selectedJob.category_name"></span>
            </p>

            <p class="text-xs md:text-sm font-semibold text-gray-700 mt-3 flex items-center">
                <img src="../img/sub-category-job.svg" alt="Sub-Category Icon" class="w-4 h-4 md:w-5 md:h-5 mr-1">
                Sub-Category: <span class="text-blue-800 font-bold ml-1" x-text="selectedJob.sub_category_name"></span>
            </p>

            <p class="text-sm md:text-md font-bold text-gray-700 mt-3 flex items-center">
                <img src="../img/budget-job.svg" alt="Budget Icon" class="w-4 h-4 md:w-5 md:h-5 mr-1">
                Budget: <span class="text-blue-800 font-bold ml-1"> ₱<span x-text="selectedJob.budget.toLocaleString()"></span></span>
            </p>

            <!-- Job Images Section -->
            <div x-show="selectedJob.images" x-cloak x-transition>
                <h3 class="text-sm md:text-md font-semibold text-gray-700 mt-3 flex items-center">
                    <img src="../img/image-job.svg" alt="Job Images Icon" class="w-4 h-4 md:w-5 md:h-5 mr-1">
                    Job Images
                </h3>
                <div class="flex space-x-3 overflow-x-auto p-2">
                    <template x-for="image in selectedJob.images.split(',')" :key="image">
                        <img :src="image.trim()"
                            class="w-24 h-24 md:w-32 md:h-32 object-cover rounded-lg shadow-md border border-gray-300 cursor-pointer hover:scale-105 transition"
                            @click="enlargedImage = image.trim()">
                    </template>
                </div>
            </div>
           
<div x-data="{ 
    comments: [], 
    newComment: '', 
    replyComment: '', 
    replyTo: null, 

    loadComments() {
        fetch('fetch_comments.php?job_id=' + selectedJob.id)
            .then(res => res.json())
            .then(data => {
                let map = {};
                let rootComments = [];

                data.forEach(comment => {
                    comment.replies = [];
                    map[comment.id] = comment;

                    if (comment.parent_id) {
                        if (map[comment.parent_id]) {
                            map[comment.parent_id].replies.push(comment);
                        }
                    } else {
                        rootComments.push(comment);
                    }
                });

                this.comments = rootComments;
            });
    },

    deleteComment(commentId, parentId = null) {
        if (confirm('Are you sure you want to delete this comment?')) {
            fetch('delete_comment.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'comment_id=' + commentId
            })
            .then(res => res.json())
            .then(response => {
                if (response.success) {
                    if (parentId) {
                        // If it's a reply, find parent and remove reply
                        let parentComment = this.comments.find(c => c.id === parentId);
                        if (parentComment) {
                            parentComment.replies = parentComment.replies.filter(r => r.id !== commentId);
                        }
                    } else {
                        // Remove main comment
                        this.comments = this.comments.filter(c => c.id !== commentId);
                    }
                } else {
                    alert(response.error);
                }
            });
        }
    }
}" x-init="loadComments()" class="max-w-2xl mx-auto mt-6">


    <h3 class="text-lg font-semibold text-gray-900">Questions</h3>
    <p class="text-sm text-gray-500">* Please don't share personal info! </p>

    <!-- Comment List -->
    <div class="mt-4">
        <template x-if="comments.length > 0">
            <ul class="space-y-4">
                <template x-for="comment in comments" :key="comment.id">
                    <li class="bg-white shadow-sm p-4 rounded-lg border">
                        <div class="flex items-start space-x-3">
                            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 text-sm">
                                <span x-text="comment.first_name[0] + comment.last_name[0]"></span>
                            </div>
                            <div class="w-full">
                                <p class="text-sm font-semibold text-gray-800" x-text="comment.first_name + ' ' + comment.last_name"></p>
                                <p class="text-sm text-gray-700 mt-1" x-text="comment.comment"></p>
                                <p class="text-xs text-gray-400 mt-1" x-text="new Date(comment.created_at).toLocaleString()"></p>

                                <div class="flex space-x-3 mt-1">
                                    <button class="text-blue-600 text-xs hover:underline" @click="replyTo = (replyTo === comment.id) ? null : comment.id">
                                        Reply
                                    </button>
                                    <button class="text-red-600 text-xs hover:underline" @click="deleteComment(comment.id)">
                                        Delete
                                    </button>
                                </div>

                                <!-- Reply Input -->
                                <div x-show="replyTo === comment.id" class="mt-3">
                                    <div class="flex items-center space-x-2">
                                        <input type="text" x-model="replyComment" placeholder="Write a reply..." class="w-full px-3 py-1 border rounded-lg text-sm focus:ring-2 focus:ring-blue-400">
                                        <button @click="
                                            if(replyComment.trim() !== '') {
                                                fetch('add_comment.php', {
                                                    method: 'POST',
                                                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                                    body: 'job_id=' + selectedJob.id + '&comment=' + encodeURIComponent(replyComment) + '&parent_id=' + comment.id
                                                }).then(res => res.json())
                                                .then(response => {
                                                    if(response.success) {
                                                        comment.replies.push({
                                                            id: response.id,
                                                            first_name: response.first_name,
                                                            last_name: response.last_name,
                                                            comment: response.comment,
                                                            created_at: response.created_at
                                                        });
                                                        replyComment = '';
                                                        replyTo = null;
                                                    } else {
                                                        alert(response.error);
                                                    }
                                                });

                                            } else {
                                                alert('Reply cannot be empty');
                                            }"
                                            class="bg-green-600 text-white px-3 py-1 rounded-lg text-sm hover:bg-green-700">
                                            Reply
                                        </button>
                                    </div>
                                </div>

                                <!-- Replies -->
                                <div x-show="comment.replies.length > 0" class="mt-3 space-y-3 border-l-2 border-gray-200 pl-4">
                                    <template x-for="reply in comment.replies" :key="reply.id">
                                        <div class="flex items-start space-x-3">
                                            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 text-xs">
                                                <span x-text="reply.first_name[0] + reply.last_name[0]"></span>
                                            </div>
                                            <div class="w-full">
                                                <p class="text-xs font-semibold text-gray-800" x-text="reply.first_name + ' ' + reply.last_name"></p>
                                                <p class="text-xs text-gray-700 mt-1" x-text="reply.comment"></p>
                                                <p class="text-xs text-gray-400 mt-1" x-text="new Date(reply.created_at).toLocaleString()"></p>

                                                <button class="text-red-600 text-xs mt-1 hover:underline" @click="deleteComment(reply.id, comment.id)">
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </li>
                </template>
            </ul>
        </template>

        <p x-show="comments.length === 0" class="text-sm text-gray-500">No questions yet. Be the first to ask!</p>
    </div>

    <!-- Add Comment -->
    <div class="mt-5 flex items-center space-x-2">
        <input type="text" x-model="newComment" placeholder="Write a comment..." class="w-full px-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-400">
        <button @click="
            if(newComment.trim() !== '') {
                fetch('add_comment.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'job_id=' + selectedJob.id + '&comment=' + encodeURIComponent(newComment)
                }).then(res => res.json())
                .then(response => {
                    if(response.success) {
                        comments.unshift({
                            id: response.id,
                            first_name: response.first_name,
                            last_name: response.last_name,
                            comment: response.comment,
                            created_at: response.created_at,
                            replies: []
                        });
                        newComment = '';
                    } else {
                        alert(response.error);
                    }
                });

            } else {
                alert('Comment cannot be empty');
            }"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
            Post
        </button>
    </div>

</div>




<button x-show="selectedJob.user_id !== <?php echo $_SESSION['user_id']; ?>" 
        class="mt-4 w-full bg-blue-600 text-white py-2 px-4 rounded-lg text-sm md:text-md font-semibold hover:bg-blue-700 
               shadow-md hover:shadow-lg transition transform hover:scale-[1.05] flex items-center justify-center"
        @click="open = true">
    <img src="../img/offer-job.svg" alt="Offer Icon" class="w-4 h-4 md:w-5 md:h-5 mr-2 ">
    Make an Offer
</button>
        </div>
    </template>
</div>


        <div x-show="enlargedImage" x-cloak
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-80 backdrop-blur-md z-50"
            @click.away="enlargedImage = null"
            @keydown.escape.window="enlargedImage = null">

            <div class="relative">

                <button @click="enlargedImage = null"
                    class="absolute top-4 right-6 text-white text-4xl font-bold hover:text-gray-300 transition">
                    &times;
                </button>

                <img :src="enlargedImage" class="max-w-[90vw] max-h-[85vh] rounded-lg shadow-lg border-4 border-white">
            </div>
        </div>


        <div id="offerModal" x-show="open" x-cloak style="display: none;" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm transition-opacity duration-300 z-50 p-4">
            <div class="bg-white rounded-2xl shadow-2xl p-4 max-w-md w-full relative transform transition-all scale-95 hover:scale-100 duration-200">

                <button @click="open = false" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-lg">&times;</button>

                <h2 class="text-lg font-bold text-gray-800 mb-3 text-center">Make an Offer</h2>

                <form id="offerForm" action="submit-offer.php" method="POST" class="space-y-3">
                    <input type="hidden" name="job_id" :value="selectedJob.id">

                    <div>
                        <label class="block text-xs font-medium text-gray-700">Offer Amount (₱)</label>
                        <input type="number" name="offerAmount" required min="1" step="0.01" class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700">Message to Job Poster</label>
                        <textarea name="message" required class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500 h-20 resize-none text-sm"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700">Estimated Completion Time</label>
                        <input type="text" name="completionTime" required class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg shadow-md transition-all duration-300 text-sm">
                        Submit Offer
                    </button>
                    <button type="button" @click="open = false" class="w-full bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 rounded-lg shadow-md transition-all duration-300 text-sm">
                        Cancel
                    </button>
                </form>
            </div>
        </div>


        <div id="confirmReplaceModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm transition-opacity duration-300 z-50 p-4" style="display: none;">
            <div class="bg-white rounded-2xl shadow-2xl p-4 max-w-md w-full text-center transform transition-all scale-95 hover:scale-100 duration-200">
                <h2 class="text-lg font-bold text-gray-800 mb-3">You Already Made an Offer for This Job</h2>
                <p class="text-sm mb-4">Would you like to replace your previous offer with this one?</p>
                <button onclick="replaceOffer()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg shadow-md transition-all duration-300 text-sm mb-2">
                    Yes, Replace Offer
                </button>
                <button onclick="closeConfirmReplaceModal()" class="w-full bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 rounded-lg shadow-md transition-all duration-300 text-sm">
                    No, Keep Previous Offer
                </button>
            </div>
        </div>

        <div id="successModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm transition-opacity duration-300 z-50 p-4" style="display: none;">
            <div class="bg-white rounded-2xl shadow-2xl p-4 max-w-md w-full text-center transform transition-all scale-95 hover:scale-100 duration-200">
                <h2 class="text-lg font-bold text-gray-800 mb-3">Offer Submitted Successfully!</h2>
                <button onclick="closeSuccessModal()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg shadow-md transition-all duration-300 text-sm">
                    Close
                </button>
            </div>
        </div>


        <div id="map-container" class="w-full lg:w-1/2 pt-0 lg:pt-3 pl-0 lg:pl-3 pb-0 transition-all duration-500 relative z-0">
            <div id="map" class="h-[300px] lg:h-[550px] w-full shadow-lg border-2 border-gray-400 rounded-2xl sticky top-0"></div>
        </div>

</div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                let map;
                let jobMarkers = {};
                let selectedMarker = null;
                let selectedJobId = null;
                let defaultLocation = [14.7576, 121.0453];

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(position => {
                        let lat = position.coords.latitude;
                        let lng = position.coords.longitude;
                        initMap(lat, lng);
                    }, () => {
                        initMap(defaultLocation[0], defaultLocation[1]);
                    });
                } else {
                    initMap(defaultLocation[0], defaultLocation[1]);
                }

                function initMap(lat, lng) {
                    map = L.map('map').setView([lat, lng], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

                    L.marker([lat, lng], {
                        icon: L.divIcon({
                            className: 'current-location-marker',
                            html: '<div style="background-color: blue; width: 14px; height: 14px; border-radius: 50%;"></div>',
                        })
                    }).addTo(map).bindPopup("You are here");

                    loadJobMarkers();
                }

                function loadJobMarkers() {
                    let jobs = JSON.parse('<?php echo json_encode($jobs); ?>');

                    Object.values(jobMarkers).forEach(marker => map.removeLayer(marker));
                    jobMarkers = {};

                    jobs.forEach(job => {
                        if (job.latitude && job.longitude) {
                            let marker = L.marker([job.latitude, job.longitude]).addTo(map)
                                .bindPopup(`<strong>${job.job_title}</strong><br>${job.location}`);

                            jobMarkers[job.id] = marker;

                            marker.on("click", function() {
                                toggleJobSelection(job.id);
                                scrollToJob(job.id);
                            });
                        }
                    });
                }

                function toggleJobSelection(jobId) {
                    let jobElement = document.getElementById("job-" + jobId);

                    if (selectedJobId === jobId) {

                        selectedJobId = null;
                        if (selectedMarker) {
                            selectedMarker.closePopup();
                            selectedMarker = null;
                        }
                        if (jobElement) {
                            jobElement.classList.remove("bg-blue-600", "text-white");
                        }
                    } else {

                        document.querySelectorAll("li").forEach(li => li.classList.remove("bg-blue-600", "text-white"));
                        if (jobElement) {
                            jobElement.classList.add("bg-blue-600", "text-white");
                        }

                        if (selectedMarker) {
                            selectedMarker.closePopup();
                        }

                        let job = jobMarkers[jobId];
                        if (job) {
                            map.setView(job.getLatLng(), 15);
                            job.openPopup();
                            selectedMarker = job;
                        }

                        selectedJobId = jobId;
                    }
                }

                function scrollToJob(jobId) {
                    let jobElement = document.getElementById("job-" + jobId);
                    if (jobElement) {
                        jobElement.scrollIntoView({
                            behavior: "smooth",
                            block: "center"
                        });
                    }
                }

                document.querySelectorAll("li").forEach(li => {
                    li.addEventListener("click", function() {
                        let jobId = this.id.replace("job-", "");
                        toggleJobSelection(jobId);
                    });
                });

                document.addEventListener("alpine:init", () => {
                    Alpine.data("jobHandler", () => ({
                        selectedJob: null,
                        closeJobDetails() {
                            this.selectedJob = null;
                            selectedJobId = null;
                            if (selectedMarker) {
                                selectedMarker.closePopup();
                                selectedMarker = null;
                            }
                            document.querySelectorAll("li").forEach(li => li.classList.remove("bg-blue-600", "text-white"));
                        }
                    }));
                });
            });
        </script>

        <script>
            document.getElementById('offerForm').addEventListener('submit', async function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const jobId = formData.get('job_id');

                const alreadyOffered = await hasAlreadyOffered(jobId);
                if (alreadyOffered) {

                    document.getElementById('confirmReplaceModal').style.display = 'flex';
                    return;
                }

                submitOffer(formData);
            });


            async function hasAlreadyOffered(jobId) {
                try {
                    const response = await fetch(`check-offer.php?job_id=${jobId}`);
                    const data = await response.json();
                    return data.hasOffer;
                } catch (error) {
                    console.error('Error:', error);
                    return false;
                }
            }

            function submitOffer(formData) {
                fetch('submit-offer.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())
                    .then(text => {
                        console.log("Raw Response:", text);
                        try {
                            return JSON.parse(text);
                        } catch (error) {
                            console.error("JSON Parse Error:", error, "Raw Response:", text);
                            throw new Error("Invalid JSON response");
                        }
                    })
                    .then(data => {
                        console.log("Parsed JSON:", data);
                        if (data.success) {
                            document.getElementById('successModal').style.display = 'flex';
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            alert('Error: ' + (data.message || 'Unknown error occurred.'));
                        }
                    })
                    .catch(error => {
                        console.error('Fetch Error:', error);
                        alert('An error occurred. Please check the console for details.');
                    });
            }

            function confirmReplaceOffer() {
                const formData = new FormData(document.getElementById('offerForm'));
                submitOffer(formData);
            }

            function closeSuccessModal() {
                document.getElementById('successModal').style.display = 'none';
            }



            function replaceOffer() {
                document.getElementById("confirmReplaceModal").style.display = "none";

                const form = document.getElementById("offerForm");
                const formData = new FormData(form);

                submitOffer(formData);
            }

            function closeConfirmReplaceModal() {
                document.getElementById("confirmReplaceModal").style.display = "none";
            }
            
            
            function formatBudget(budget) {
    if (typeof budget === 'string' && budget.includes('-')) {
        return budget.split('-').map(num => parseInt(num.trim())).join(' - ');
    }
    return parseInt(budget).toLocaleString();
}


            
        </script>





        <script>
            function openJob(job) {

                console.log("Opening job:", job);
            }
        </script>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


        <script>
            document.addEventListener("DOMContentLoaded", function() {
                let map;
                let jobMarkers = {};
                let selectedMarker = null;
                let selectedJobId = null;
                let userMarker = null;
                let defaultLocation = [14.7576, 121.0453];

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(position => {
                        let lat = position.coords.latitude;
                        let lng = position.coords.longitude;
                        initMap(lat, lng);
                    }, () => {
                        initMap(defaultLocation[0], defaultLocation[1]);
                    });
                } else {
                    initMap(defaultLocation[0], defaultLocation[1]);
                }

                function initMap(lat, lng) {
                    map = L.map('map').setView([lat, lng], 14);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

                    userMarker = L.marker([lat, lng], {
                        icon: L.divIcon({
                            className: 'current-location-marker',
                            html: '<div style="background-color: blue; width: 14px; height: 14px; border-radius: 50%;"></div>',
                        })
                    }).addTo(map).bindPopup("You are here").openPopup();

                    loadJobMarkers();
                }

                function loadJobMarkers() {
                    let jobs = JSON.parse('<?php echo json_encode($jobs); ?>');

                    Object.values(jobMarkers).forEach(marker => map.removeLayer(marker));
                    jobMarkers = {};

                    jobs.forEach(job => {
                        if (job.latitude && job.longitude) {
                            let marker = L.marker([job.latitude, job.longitude]).addTo(map)
                                .bindPopup(`<strong>${job.job_title}</strong><br>${job.location}`);

                            jobMarkers[job.id] = marker;

                            marker.on("click", function() {
                                highlightJobInList(job.id);
                            });
                        }
                    });
                }

                function highlightJobInList(jobId) {
                    let jobElement = document.getElementById("job-" + jobId);

                    if (!jobElement) return;

                    document.querySelectorAll("li").forEach(li => li.classList.remove("bg-blue-600", "text-black"));

                    jobElement.classList.add("bg-blue-600", "text-black");

                    jobElement.scrollIntoView({
                        behavior: "smooth",
                        block: "nearest"
                    });

                    if (selectedMarker) {
                        selectedMarker.closePopup();
                    }

                    let marker = jobMarkers[jobId];
                    if (marker) {
                        map.setView(marker.getLatLng(), 15);
                        marker.openPopup();
                        selectedMarker = marker;
                    }

                    selectedJobId = jobId;
                }

                document.querySelectorAll("li").forEach(li => {
                    li.addEventListener("click", function() {
                        let jobId = this.id.replace("job-", "");
                        highlightJobInList(jobId);
                    });
                });

                document.addEventListener("alpine:init", () => {
                    Alpine.data("jobHandler", () => ({
                        selectedJob: null,
                        closeJobDetails() {
                            this.selectedJob = null;
                            selectedJobId = null;
                            if (selectedMarker) {
                                selectedMarker.closePopup();
                                selectedMarker = null;
                            }
                            document.querySelectorAll("li").forEach(li => li.classList.remove("bg-blue-600", "text-white"));
                        }
                    }));
                });
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
                    setTimeout(() => { letter.style.animation = ''; }, 10);
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




        <script>
            function updateDistanceLabel() {
                const slider = document.querySelector("input[type='range']");
                const label = document.getElementById("distanceLabel");
                label.textContent = ["5km", "10km", "15km", "25km", "50km", "50km+"][slider.value];
            }
        </script>

        <script>
            function toggleDropdown(id) {
                document.getElementById(id).classList.toggle('hidden');
            }
        </script>


<script>
document.addEventListener("DOMContentLoaded", function () {
    let savedJobId = localStorage.getItem('selectedJobId');
    if (savedJobId) {
        let jobElement = document.querySelector(`[id='job-${savedJobId}']`);
        if (jobElement) {
            jobElement.click(); // Simulate a click to restore selection
        }
    }
});
</script>





<?php } else { ?>
    <?php include 'assesment.php'; ?>
<?php } ?>
</div>
</body>

</html>