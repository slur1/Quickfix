<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: userLogin.php");
    exit;
}

include '../config/db_connection.php';

$user_id = $_SESSION['user_id'];

// Fetch user verification status
$query = "SELECT verification_status FROM user WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($verification_status);
$stmt->fetch();
$stmt->close();

$isUnverified = ($verification_status === 'unverified');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];

    $job_title = trim($_POST['job_title']);
    $job_date = !empty($_POST['job_date']) ? trim($_POST['job_date']) : NULL;
    $job_time = !empty($_POST['job_time']) ? trim($_POST['job_time']) : NULL;
    $location = trim($_POST['location']);
    $description = trim($_POST['description']);
    $category_id = isset($_POST['category_id']) ? $_POST['category_id'] : null;
    $sub_category_id = isset($_POST['sub_category_id']) ? $_POST['sub_category_id'] : null;
    $budget = $_POST['budget'];

    $imagePaths = [];
    if (!empty($_FILES['images']['name'][0])) {
        $uploadDir = "user-uploads/";
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            $fileName = basename($_FILES['images']['name'][$key]);
            $targetFilePath = $uploadDir . $fileName;
            if (move_uploaded_file($tmp_name, $targetFilePath)) {
                $imagePaths[] = $targetFilePath;
            }
        }
    }
    $images = !empty($imagePaths) ? implode(',', $imagePaths) : NULL;

    $stmt = $conn->prepare("INSERT INTO jobs (user_id, job_title, job_date, job_time, location, description, category_id, sub_category_id, budget, images) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("issssssiss", $user_id, $job_title, $job_date, $job_time, $location, $description, $category_id, $sub_category_id, $budget, $images);

    if ($stmt->execute()) {
        header("Location: userPostJob.php?success=1");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

$sql = "SELECT id, user_id, job_title, job_date, job_time, location, description, category_id, sub_category_id, budget, images, status FROM jobs";
$result = $conn->query($sql);

$jobs = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $jobs[] = $row;
    }
}

$query = "SELECT * FROM categories";
$result = $conn->query($query);

if (isset($_POST['category_id'])) {
    $category_id = $_POST['category_id'];
    $query = "SELECT * FROM sub_categories WHERE category_id = $category_id";
    $result = $conn->query($query);

    while ($row = $result->fetch_assoc()) {
        echo "<option value='{$row['id']}'>{$row['name']}</option>";
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a Job | QuickFix</title>
    <link rel="icon" type="logo" href="../img/logo1.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/jquery.validate.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
</head>

<body class="bg-gray-50 min-h-screen flex flex-col">
    <?php include './userHeader.php'; ?>

    <div class="flex justify-center items-start mt-10">
        <form id="jobForm" method="POST" enctype="multipart/form-data" class="w-full max-w-4xl p-6 shadow-lg bg-white rounded-lg border border-gray-300">
            <h1 class="text-3xl font-bold text-blue-900 mb-8">Post a Job</h1>
            <div class="space-y-6">
                <div>
                    <label for="job_title" class="block text-sm font-medium text-gray-700 mb-2">Job Title</label>
                    <input type="text" id="job_title" name="job_title" placeholder="Enter a job title"
                        class="w-full p-3 rounded-lg bg-gray-50 border border-gray-300 focus:border-blue-900 focus:ring-1 focus:ring-blue-900 focus:outline-none">
                    <span class="text-xs text-red-500 hidden error-message"></span>
                </div>


                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Job date</label>
                    <div class="flex flex-wrap gap-3 items-center">
                        <button type="button" id="onDateBtn" class="px-4 py-2 rounded-full border transition-colors bg-white text-gray-700">
                            On date
                        </button>
                        <input type="date" id="onDateInput" class="hidden mt-2 p-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring focus:ring-blue-300">

                        <button type="button" id="beforeDateBtn" class="px-4 py-2 rounded-full border transition-colors bg-white text-gray-700">
                            Before date
                        </button>
                        <input type="date" id="beforeDateInput" class="hidden mt-2 p-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring focus:ring-blue-300">

                        <button type="button" id="flexibleBtn"
                            class="px-4 py-2 rounded-full border transition-colors bg-white text-gray-700">
                            I'm flexible
                        </button>
                        <input type="hidden" id="job_date" name="job_date">
                    </div>
                </div>

                <div>
                    <label class="flex items-center mb-4">
                        <input type="checkbox" id="specificTimeCheckbox" name="job_time" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">I need a certain time of day</span>
                    </label>
                    <div id="timeOptions" class="hidden grid grid-cols-2 md:grid-cols-4 gap-4">
                        <button type="button" name="job_time" class="time-btn p-4 rounded-lg text-center transition-colors bg-gray-50 text-gray-700" data-time="Morning (Before 10am)">
                            Morning<br><span class="text-sm opacity-80">Before 10am</span>
                        </button>
                        <button type="button" name="job_time" class="time-btn p-4 rounded-lg text-center transition-colors bg-gray-50 text-gray-700" data-time="Midday (10am - 2pm)">
                            Midday<br><span class="text-sm opacity-80">10am - 2pm</span>
                        </button>
                        <button type="button" name="job_time" class="time-btn p-4 rounded-lg text-center transition-colors bg-gray-50 text-gray-700" data-time="Afternoon (2pm - 6pm)">
                            Afternoon<br><span class="text-sm opacity-80">2pm - 6pm</span>
                        </button>
                        <button type="button" name="job_time" class="time-btn p-4 rounded-lg text-center transition-colors bg-gray-50 text-gray-700" data-time="Evening (After 6pm)">
                            Evening<br><span class="text-sm opacity-80">After 6pm</span>
                        </button>
                    </div>
                    <input type="hidden" id="job_time" name="job_time">
                </div>

                <div class="w-full relative">
                    <label for="location" class="block text-gray-700 font-semibold mb-1">Location:</label>
                    <div class="flex items-center gap-2">
                        <input type="text" id="location" name="location" placeholder="Enter address..."
                            class="w-full p-3 rounded-lg bg-gray-50 border border-gray-300 focus:border-blue-900 focus:ring-1 focus:ring-blue-900 focus:outline-none">
                        <span class="text-xs text-red-500 hidden error-message"></span>
                        <button type="button" onclick="openMapModal()"
                            class="p-2 bg-gray-200 rounded-full hover:bg-gray-300 transition flex items-center justify-center">
                            <img src="../img/location-post-job.svg" alt="Location Icon" class="w-5 h-5">
                        </button>
                    </div>


                    <div id="suggestions" class="absolute left-0 right-0 bg-white shadow-lg rounded-md mt-1 hidden border border-gray-300 z-10"></div>
                </div>

                <div id="mapModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                    <div class="bg-white p-5 rounded-lg w-full max-w-3xl shadow-lg">
                        <h3 class="text-xl font-semibold mb-3">Select Location</h3>
                        <div class="relative w-full h-[500px] bg-gray-200 rounded-lg">
                            <div id="map" class="w-full h-full"></div>
                        </div>
                        <div class="flex justify-end gap-3 mt-4">
                            <button type="button" onclick="useSelectedLocation()" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Use this Address</button>
                            <button type="button" onclick="closeMapModal()" class="px-5 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">Close</button>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="description" name="description" rows="4" placeholder="Describe the job"
                        class="w-full p-3 rounded-lg bg-gray-50 border border-gray-300 focus:border-blue-900 focus:ring-1 focus:ring-blue-900 resize-none"></textarea>
                    <span class="text-xs text-red-500 hidden error-message"></span>
                </div>

                <div x-data="{ categories: [], selectedCategory: '', subcategories: [], selectedSubcategory: '' }" x-init="
                    fetch('get_categories.php')
                    .then(response => response.json())
                    .then(data => categories = data)">

                    <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                    <select id="category" x-model="selectedCategory" @change="
                        subcategories = categories.find(cat => cat.id == selectedCategory)?.sub_categories || []"
                        class="block w-full mt-1 p-2 border rounded-md">
                        <option value="">Select Category</option>
                        <template x-for="category in categories" :key="category.id">
                            <option :value="category.id" x-text="category.name"></option>
                        </template>
                    </select>
                    <input type="hidden" name="category_id" x-model="selectedCategory">

                    <label for="sub_category" class="block text-sm font-medium text-gray-700 mt-3">Subcategory</label>
                    <select id="sub_category" x-model="selectedSubcategory" class="block w-full mt-1 p-2 border rounded-md">
                        <option value="">Select Subcategory</option>
                        <template x-for="sub in subcategories" :key="sub.id">
                            <option :value="sub.id" x-text="sub.name"></option>
                        </template>
                    </select>
                    <input type="hidden" name="sub_category_id" x-model="selectedSubcategory">

                </div>


                <div>
                    <label for="budget" class="block text-sm font-medium text-gray-700 mb-2">Budget</label>
                    <!-- Old -->
                    <!-- <input type="number" id="budget" name="budget" placeholder="Enter budget amount"
                        class="w-full p-3 rounded-lg bg-gray-50 border border-gray-300 focus:border-blue-900 focus:ring-1 focus:ring-blue-900 focus:outline-none"> -->

                    <!-- Current -->
                    <input type="text" id="budget" name="budget" placeholder="Enter budget ranging amount (0 - 100)"
                        class="w-full p-3 rounded-lg bg-gray-50 border border-gray-300 focus:border-blue-900 focus:ring-1 focus:ring-blue-900 focus:outline-none">
                    <span class="text-xs text-red-500 hidden error-message"></span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Add images (optional)</label>
                    <input type="file" id="images" name="images[]" multiple accept="image/*" class="w-full p-3 rounded-lg bg-gray-50 border border-gray-300 focus:outline-none">
                    <p class="text-sm text-gray-500 mt-2">You can upload up to 5 images.</p>
                    <span class="text-xs text-red-500 hidden error-message"></span>
                </div>

                <div class="flex justify-end space-x-4 pt-4">
                <button type="button" id="resetButton" class="px-6 py-2 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-50 transition-colors">
                    Reset
                </button>

                    <button type="submit" id="postJobBtn" data-unverified="<?= $isUnverified ? 'true' : 'false'; ?>" 
                        class="px-6 py-2 bg-blue-800 text-white rounded-full hover:bg-blue-900 transition-colors">
                        Post
                    </button>
                </div>
            </div>
            </main>
        </form>

        <div id="successModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-lg font-semibold">Job Posted Successfully!</h2>
                <p class="mt-2 text-gray-600">Your job has been posted successfully.</p>
                <button id="closeSuccessModal" class="mt-4 px-4 py-2 bg-blue-800 text-white rounded-lg hover:bg-blue-900 transition">
                    OK
                </button>
            </div>
        </div>

        <!-- Verification Modal -->
        <div id="requiredModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-md hidden">
            <div class="bg-white p-6 rounded-2xl shadow-xl w-[90%] max-w-sm relative">
                <div class="flex flex-col items-center text-center">
                    <div class="w-12 h-12 flex items-center justify-center bg-red-100 text-red-500 rounded-full mb-4">
                        <i class="fas fa-exclamation-circle text-2xl"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">Verification Required</h2>
                    <p class="mt-2 text-gray-600 text-sm">You must verify your account before posting a job.</p>
                    <button id="closeRequiredModal" class="mt-5 w-full py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-all duration-300">
                        OK, Verify Now
                    </button>
                </div>
            </div>
        </div>


<script>
function categoryDropdown() {
    return {
        categories: [],
        selectedCategory: '',
        subcategories: [],
        selectedSubcategory: '',

        async loadCategories() {
            try {
                let response = await fetch('get_categories.php');
                this.categories = await response.json();
            } catch (error) {
                console.error('Error fetching categories:', error);
            }
        },

        async loadSubcategories() {
            if (!this.selectedCategory) {
                this.subcategories = [];
                return;
            }
            
            try {
                let response = await fetch('get_subcategories.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({ category_id: this.selectedCategory })
                });
                this.subcategories = await response.json();
            } catch (error) {
                console.error('Error fetching subcategories:', error);
            }
        }
    };
}
</script>

        <script>
            document.getElementById("cancelButton").addEventListener("click", function() {
                document.getElementById("jobForm").reset();
            });
        </script>

        <script>
            function toggleDetails(id) {
                let content = document.getElementById(id);
                let arrow = document.getElementById('arrow-' + id.split('-')[1]);

                if (content.classList.contains('hidden')) {
                    content.classList.remove('hidden');
                    arrow.classList.add('rotate-180');
                } else {
                    content.classList.add('hidden');
                    arrow.classList.remove('rotate-180');
                }
            }
        </script>

        <script>
            function openMapModal() {
                document.getElementById('mapModal').classList.remove('hidden');
                if (!window.map) {
                    window.map = L.map('map').setView([14.5995, 120.9842], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(map);
                    window.marker = L.marker([14.5995, 120.9842], {
                        draggable: true
                    }).addTo(map);
                    window.marker.on('dragend', function(e) {
                        var latlng = e.target.getLatLng();
                        document.getElementById('location').value = `${latlng.lat}, ${latlng.lng}`;
                    });
                }
            }

            function useSelectedLocation() {
                document.getElementById('mapModal').classList.add('hidden');
            }

            function closeMapModal() {
                document.getElementById('mapModal').classList.add('hidden');
            }
        </script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const API_KEY = "pk.8d6caae95f83ef55c3b74214f9f81424";
                let map, marker, userLat = 14.5995,
                    userLng = 120.9842;
                const locationInput = document.getElementById("location");
                const suggestionsBox = document.getElementById("suggestions");

                function getUserLocation() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            (position) => {
                                userLat = position.coords.latitude;
                                userLng = position.coords.longitude;
                            },
                            (error) => console.warn("Geolocation error:", error), {
                                enableHighAccuracy: true
                            }
                        );
                    }
                }
                getUserLocation();

                locationInput.addEventListener("input", function() {
                    const query = locationInput.value.trim();
                    if (query.length < 2) {
                        suggestionsBox.style.display = "none";
                        return;
                    }

                    fetch(`https://api.locationiq.com/v1/autocomplete.php?key=${API_KEY}&q=${query}&limit=5&format=json`)
                        .then(response => response.json())
                        .then(data => {
                            suggestionsBox.innerHTML = "";
                            if (!data.length) {
                                suggestionsBox.style.display = "none";
                                return;
                            }

                            data.forEach(place => {
                                const suggestion = document.createElement("div");
                                suggestion.classList.add("px-4", "py-2", "cursor-pointer", "hover:bg-gray-100", "border-b");
                                suggestion.textContent = place.display_name;
                                suggestion.onclick = function() {
                                    locationInput.value = place.display_name;
                                    localStorage.setItem("savedAddress", place.display_name);
                                    suggestionsBox.style.display = "none";
                                };
                                suggestionsBox.appendChild(suggestion);
                            });

                            suggestionsBox.style.display = "block";
                        })
                        .catch(error => console.error("Error fetching locations:", error));
                });

                document.addEventListener("click", function(event) {
                    if (!locationInput.contains(event.target) && !suggestionsBox.contains(event.target)) {
                        suggestionsBox.style.display = "none";
                    }
                });

                function openMapModal() {
                    document.getElementById("mapModal").classList.remove("hidden");

                    setTimeout(() => {
                        if (!map) {
                            map = L.map("map", {
                                    zoomControl: false
                                })
                                .setView([userLat, userLng], 13);

                            L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png").addTo(map);

                            marker = L.marker([userLat, userLng], {
                                draggable: true
                            }).addTo(map);
                            marker.on("dragend", updateLocation);


                            L.control.zoom({
                                position: "topleft"
                            }).addTo(map);

                            addCustomControls();
                        } else {
                            map.invalidateSize();
                            map.setView([userLat, userLng], 13);
                            marker.setLatLng([userLat, userLng]);
                        }
                    }, 200);
                }


                function addCustomControls() {

                    const controlContainer = L.control({
                        position: "topleft"
                    });

                    controlContainer.onAdd = function(map) {
                        const div = L.DomUtil.create("div", "leaflet-bar leaflet-control leaflet-custom-controls");

                        const buttonStyle = `
                    display: block;
                    font-size: 20px;
                    text-align: center;
                    padding: 5px;
                    background: white;
                    border-top: 1px solid gray;
                `;

                        const locateButton = document.createElement("a");
                        locateButton.href = "#";


                        const locationIcon = document.createElement("img");
                        locationIcon.src = "../img/location-post-job.svg";
                        locationIcon.alt = "Location Icon";
                        locationIcon.style.width = "20px";
                        locationIcon.style.height = "20px";


                        locateButton.appendChild(locationIcon);

                        locateButton.title = "Go to My Location";
                        locateButton.style = buttonStyle;
                        locateButton.onclick = function(e) {
                            e.preventDefault();
                            getCurrentLocationOnMap();
                        };


                        const pinButton = document.createElement("a");
                        pinButton.href = "#";
                        pinButton.title = "Center Marker";
                        pinButton.style = buttonStyle;

                        // Create an image element for the SVG
                        const pinIcon = document.createElement("img");
                        pinIcon.src = "../img/pin-post-job.svg"; // Adjust the path to your actual file location
                        pinIcon.alt = "Pin Icon";
                        pinIcon.style.width = "20px"; // Set appropriate width
                        pinIcon.style.height = "20px";

                        // Append the image to the button
                        pinButton.appendChild(pinIcon);

                        pinButton.onclick = function(e) {
                            e.preventDefault();
                            centerMarker();
                        };


                        div.appendChild(locateButton);
                        div.appendChild(pinButton);
                        return div;
                    };

                    controlContainer.addTo(map);
                }

                function centerMarker() {
                    if (marker && map) {
                        map.setView(marker.getLatLng(), 13);
                    }
                }

                function getCurrentLocationOnMap() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            (position) => {
                                const lat = position.coords.latitude;
                                const lng = position.coords.longitude;

                                marker.setLatLng([lat, lng]);
                                map.setView([lat, lng], 15);
                                updateLocation();
                            },
                            (error) => alert("Failed to retrieve location. Enable location services."), {
                                enableHighAccuracy: true
                            }
                        );
                    } else {
                        alert("Geolocation is not supported by this browser.");
                    }
                }

                function updateLocation() {
                    const {
                        lat,
                        lng
                    } = marker.getLatLng();

                    fetch(`https://us1.locationiq.com/v1/reverse.php?key=${API_KEY}&lat=${lat}&lon=${lng}&format=json`)
                        .then(response => response.json())
                        .then(data => {
                            locationInput.value = data.display_name;
                            localStorage.setItem("savedAddress", data.display_name);
                        })
                        .catch(error => console.error("Error fetching location:", error));
                }

                function useSelectedLocation() {
                    updateLocation();
                    closeMapModal();
                }

                function closeMapModal() {
                    document.getElementById("mapModal").classList.add("hidden");
                }

                window.getCurrentLocationOnMap = getCurrentLocationOnMap;
                window.openMapModal = openMapModal;
                window.useSelectedLocation = useSelectedLocation;
                window.closeMapModal = closeMapModal;
                window.centerMarker = centerMarker;
            });
        </script>


        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const onDateBtn = document.getElementById('onDateBtn');
                const beforeDateBtn = document.getElementById('beforeDateBtn');
                const flexibleBtn = document.getElementById('flexibleBtn');
                const onDateInput = document.getElementById('onDateInput');
                const beforeDateInput = document.getElementById('beforeDateInput');
                const jobDateInput = document.querySelector('input[name="job_date"]');
                const jobTimeInput = document.getElementById('job_time');
                const timeButtons = document.querySelectorAll('.time-btn');
                const specificTimeCheckbox = document.getElementById('specificTimeCheckbox');
                const timeOptions = document.getElementById('timeOptions');
                const imagesInput = document.getElementById('images');
                const errorMessage = document.querySelector('.error-message');
                const files = imagesInput.files;

                const toggleVisibility = (element, isVisible) => {
                    element.classList.toggle('hidden', !isVisible);
                };

                function resetButtons() {
                    onDateBtn.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
                    beforeDateBtn.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
                    flexibleBtn.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
                }

                onDateBtn.addEventListener('click', () => {
                    resetButtons();
                    onDateBtn.classList.add('bg-blue-600', 'text-black', 'border-blue-600');
                    onDateInput.classList.remove('hidden');
                    beforeDateInput.classList.add('hidden');
                    beforeDateInput.value = '';
                    jobDateInput.value = '';
                });

                beforeDateBtn.addEventListener('click', () => {
                    resetButtons();
                    beforeDateBtn.classList.add('bg-blue-600', 'text-black', 'border-blue-600');
                    beforeDateInput.classList.remove('hidden');
                    onDateInput.classList.add('hidden');
                    onDateInput.value = '';
                    jobDateInput.value = '';
                });


                flexibleBtn.addEventListener('click', () => {
                    resetButtons();
                    flexibleBtn.classList.add('bg-blue-600', 'text-black', 'border-blue-600');
                    onDateInput.classList.add('hidden');
                    beforeDateInput.classList.add('hidden');
                    jobDateInput.value = '';
                });

                onDateInput.addEventListener('change', () => {
                    jobDateInput.value = onDateInput.value;
                });

                beforeDateInput.addEventListener('change', () => {
                    jobDateInput.value = beforeDateInput.value;
                });

                specificTimeCheckbox.addEventListener('change', () => {
                    toggleVisibility(timeOptions, specificTimeCheckbox.checked);
                    if (!specificTimeCheckbox.checked) {
                        jobTimeInput.value = '';
                    }
                });

                timeButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        timeButtons.forEach(btn => btn.classList.remove('bg-blue-500', 'text-white'));
                        button.classList.add('bg-blue-500', 'text-white');
                        jobTimeInput.value = button.getAttribute('data-time');
                    });
                });

                document.querySelectorAll('button[type="button"]').forEach(button => {
                    button.addEventListener('click', (event) => {
                        event.preventDefault();
                    });
                });
            });

            $(document).ready(function() {
                $("#jobForm").submit(function(event) {
                    event.preventDefault();

                    let isValid = true;
                    $(".error-message").text("").addClass("hidden");
                    $("input, textarea").removeClass("border-red-500");

                    let jobTitle = $("#job_title").val().trim();
                    if (jobTitle === "") {
                        $("#job_title").addClass("border-red-500");
                        $("#job_title").next(".error-message").text("Job title is required.").removeClass("hidden");
                        isValid = false;
                    }

                    let location = $("#location").val().trim();
                    if (location === "") {
                        $("#location").addClass("border-red-500");
                        $("#location").next(".error-message").text("Location is required.").removeClass("hidden");
                        isValid = false;
                    }

                    let description = $("#description").val().trim();
                    if (description === "") {
                        $("#description").addClass("border-red-500");
                        $("#description").next(".error-message").text("Description is required.").removeClass("hidden");
                        isValid = false;
                    }

                    let budget = $("#budget").val().trim();
let rangePattern = /^\d+\s*-\s*\d+$/; // Regex for "number-number" format

if (budget === "") {
    $("#budget").addClass("border-red-500");
    $("#budget").next(".error-message").text("Budget is required (Enter a range like '100-500')").removeClass("hidden");
    isValid = false;
} else if (!rangePattern.test(budget)) {
    $("#budget").addClass("border-red-500");
    $("#budget").next(".error-message").text("Budget must be in range format (e.g., '100-500')").removeClass("hidden");
    isValid = false;
} else {
    let [min, max] = budget.split("-").map(num => parseInt(num.trim()));

    if (isNaN(min) || isNaN(max) || min <= 100 || max <= 100 || min >= max) {
        $("#budget").addClass("border-red-500");
        $("#budget").next(".error-message").text("Enter a valid range (e.g., '101-500' where min < max)").removeClass("hidden");
        isValid = false;
    } else {
        $("#budget").removeClass("border-red-500");
        $("#budget").next(".error-message").addClass("hidden");
    }
}
                 if (isValid) {
                        this.submit();
                    }
                });


            });

            document.addEventListener("DOMContentLoaded", function() {

                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has("success")) {
                    document.getElementById("successModal").classList.remove("hidden");

                    const newUrl = window.location.pathname;
                    history.replaceState({}, document.title, newUrl);
                }

                document.getElementById("closeSuccessModal").addEventListener("click", function() {
                    document.getElementById("successModal").classList.add("hidden");
                });
            });

            document.addEventListener("DOMContentLoaded", function () {
                const postJobBtn = document.getElementById("postJobBtn");
                const modal = document.getElementById("requiredModal");
                const closeModalBtn = document.getElementById("closeRequiredModal");

                postJobBtn.addEventListener("click", function (event) {
                    const isUnverified = postJobBtn.getAttribute("data-unverified") === "true";

                    if (isUnverified) {
                        event.preventDefault(); // ⛔ Stop form submission
                        modal.classList.remove("hidden"); // 🔥 Show verification modal
                    }
                });

                closeModalBtn.addEventListener("click", function () {
                    modal.classList.add("hidden");
                    window.location.href = "userVerifyAccount.php"; // Redirect to verification page
                });
            });
        </script>

        <script>
            document.getElementById('resetButton').addEventListener('click', function() {
                let form = document.getElementById('jobForm');
                form.reset(); // Resets all standard inputs and selects

                // Reset manually for Alpine.js-bound fields
                document.getElementById('onDateInput').classList.add('hidden');
                document.getElementById('beforeDateInput').classList.add('hidden');
                document.getElementById('timeOptions').classList.add('hidden');
                document.getElementById('job_date').value = "";
                document.getElementById('job_time').value = "";
                
                // Reset any error messages
                document.querySelectorAll('.error-message').forEach(el => {
                    el.classList.add('hidden');
                    el.textContent = '';
                });

                // Clear file input
                document.getElementById('images').value = '';

                // If using Alpine.js, reset bound values
                if (typeof Alpine !== 'undefined') {
                    Alpine.store('selectedCategory', '');
                    Alpine.store('selectedSubcategory', '');
                }
            });
        </script>


</body>

</html>