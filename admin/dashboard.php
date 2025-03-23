<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // If not logged in, redirect to login page
    header('Location: admin-login.php');
    exit();
}

include '../admin/header.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
</head>

<body class="bg-gray-50" x-data="{
    period: 'Weekly',
    initChart() {
        const ctx = document.getElementById('visitorsChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: Array.from({length: 30}, (_, i) => i + 1),
                datasets: [{
                    label: 'Visitors',
                    data: [150,300,180,280,170,180,280,100,200,300,250,110,120,200,170,300,110,80,300,110,220,280,160,280,110,110,280,300,250],
                    backgroundColor: 'rgb(59, 130, 246)',
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 400,
                        ticks: {
                            stepSize: 100
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    },
initMap() {
    // First Map (North Caloocan City)
    const map1 = L.map('map').setView([14.7550, 121.0450], 14); // Center at North Caloocan City
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map1);

    const locations1 = [
        { name: 'Barangay 176', lat: 14.7600, lng: 121.0500 },
        { name: 'Barangay 178', lat: 14.7500, lng: 121.0400 },
        { name: 'Barangay 182', lat: 14.7550, lng: 121.0550 }
    ];

    locations1.forEach(loc => {
        L.marker([loc.lat, loc.lng]).addTo(map1)
            .bindPopup(`<strong>${loc.name}</strong>`);
    });

    // Second Map (North Caloocan City with different locations)
    const map2 = L.map('map2').setView([14.7500, 121.0350], 14); // Center slightly differently
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map2);

    const locations2 = [
        { name: 'Barangay 175', lat: 14.7450, lng: 121.0300 },
        { name: 'Barangay 177', lat: 14.7480, lng: 121.0370 }
    ];

    locations2.forEach(loc => {
        L.marker([loc.lat, loc.lng]).addTo(map2)
            .bindPopup(`<strong>${loc.name}</strong>`);
    });
}


}" x-init="initChart(); $nextTick(() => initMap())">


    <!-- Dashboard Content -->
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Date Range and Period Selector -->
        <div class="flex flex-wrap justify-between items-center mb-6">
            <button class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Oct 1, 2024 - Dec 22, 2024
            </button>
            <select x-model="period" class="block pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md mt-4 sm:mt-0">
                <option>Yearly</option>
                <option>Monthly</option>
                <option>Weekly</option>
                <option>Daily</option>
            </select>
        </div>

        <!-- Visitors Chart -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Visitors Analytics</h3>
                <div style="height: 300px;">
                    <canvas id="visitorsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mt-6">
            <!-- Unique Visitors -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-2xl font-semibold text-gray-900">5</p>
                            <p class="mt-1 text-sm text-gray-500">Total Users</p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            ↑ 18%
                        </span>
                    </div>
                </div>
            </div>

            <!-- Total Pageviews -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-2xl font-semibold text-gray-900">3</p>
                            <p class="mt-1 text-sm text-gray-500">Total Jobs</p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            ↑ 5%
                        </span>
                    </div>
                </div>
            </div>

            <!-- Bounce Rate -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-2xl font-semibold text-gray-900">2</p>
                            <p class="mt-1 text-sm text-gray-500">Total Services</p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            ↓ 2%
                        </span>
                    </div>
                </div>
            </div>

            <!-- Visit Duration -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-2xl font-semibold text-gray-900">15</p>
                            <p class="mt-1 text-sm text-gray-500">Total Visits</p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            ↑ 12%
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Jobs by Barangay 1 -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Jobs by Barangay</h3>
                    <select class="text-sm border-gray-300 rounded-md">
                        <option>Last 7 days</option>
                        <option>Last 30 days</option>
                        <option>Last 90 days</option>
                    </select>
                </div>
                <!-- Map -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <div id="map" style="height: 400px;"></div>
                    </div>
                </div>
                <!-- Country List -->
                <div class="mt-4 space-y-2">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <img src="../img/reg-clean.svg" class="w-5 h-4 mr-2" alt="Regular Cleaning">
                            <span>Regular Cleaning</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-24 h-2 bg-gray-200 rounded-full mr-2">
                                <div class="w-[55%] h-full bg-blue-500 rounded-full"></div>
                            </div>
                            <span class="text-sm text-gray-600">55%</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <img src="../img/mop.svg" class="w-5 h-4 mr-2" alt="Deep Cleaning">
                            <span>Deep Cleaning</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-24 h-2 bg-gray-200 rounded-full mr-2">
                                <div class="w-[25%] h-full bg-blue-500 rounded-full"></div>
                            </div>
                            <span class="text-sm text-gray-600">25%</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <img src="../img/electrical-cord.svg" class="w-5 h-4 mr-2" alt="Electrical Repair">
                            <span>Electrical Repair</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-24 h-2 bg-gray-200 rounded-full mr-2">
                                <div class="w-[20%] h-full bg-blue-500 rounded-full"></div>
                            </div>
                            <span class="text-sm text-gray-600">20%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jobs by Barangay 2 -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Services by Barangay</h3>
                    <select class="text-sm border-gray-300 rounded-md">
                        <option>Last 7 days</option>
                        <option>Last 30 days</option>
                        <option>Last 90 days</option>
                    </select>
                </div>
                <!-- Map -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <div id="map2" style="height: 400px;"></div>
                    </div>
                </div>
                <!-- Country List -->
                <div class="mt-4 space-y-2">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <img src="../img/appliance1.svg" class="w-5 h-4 mr-2" alt="Regular Cleaning">
                            <span>Appliance Repair</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-24 h-2 bg-gray-200 rounded-full mr-2">
                                <div class="w-[55%] h-full bg-blue-500 rounded-full"></div>
                            </div>
                            <span class="text-sm text-gray-600">55%</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <img src="../img/furniture1.svg" class="w-5 h-4 mr-2" alt="Deep Cleaning">
                            <span>Furniture Repair</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-24 h-2 bg-gray-200 rounded-full mr-2">
                                <div class="w-[45%] h-full bg-blue-500 rounded-full"></div>
                            </div>
                            <span class="text-sm text-gray-600">45%</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Content -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        <!-- Top Jobs -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Top Jobs</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="text-left text-sm font-medium text-gray-500">
                                <th class="pb-2">Name</th>
                                <th class="pb-2 text-right">Success</th>
                                <th class="pb-2 text-right">Total Earned</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <tr class="border-t">
                                <td class="py-2">Regular Cleaning</td>
                                <td class="py-2 text-right">5</td>
                                <td class="py-2 text-right">₱5000</td>
                            </tr>
                            <tr class="border-t">
                                <td class="py-2">Deep Cleaning</td>
                                <td class="py-2 text-right">4</td>
                                <td class="py-2 text-right">₱4500</td>
                            </tr>
                            <tr class="border-t">
                                <td class="py-2">Electrical Repair</td>
                                <td class="py-2 text-right">3</td>
                                <td class="py-2 text-right">₱4000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Top Services -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Top Services</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="text-left text-sm font-medium text-gray-500">
                                <th class="pb-2">Name</th>
                                <th class="pb-2 text-right">Success</th>
                                <th class="pb-2 text-right">Total Earned</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <tr class="border-t">
                                <td class="py-2">Appliance Repair</td>
                                <td class="py-2 text-right">4</td>
                                <td class="py-2 text-right">₱3500</td>
                            </tr>
                            <tr class="border-t">
                                <td class="py-2">Furniture Repair</td>
                                <td class="py-2 text-right">3</td>
                                <td class="py-2 text-right">₱3000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</body>

</html>