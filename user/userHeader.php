<?php
include '../config/db_connection.php';

$isVerified = false; // Default to false

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Fetch user details
    $query = "SELECT first_name, last_name, profile_picture, verification_status FROM user WHERE id = ?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($firstName, $lastName, $profilePicture, $verificationStatus);

        if ($stmt->fetch()) {
            // Use a default profile image if none is provided
            $defaultProfileImage = '../uploads/profile_pictures/default-avatar.jpg';
            $_SESSION['user_first_name'] = $firstName;
            $_SESSION['user_last_name'] = $lastName;
            $_SESSION['user_profile_image'] = !empty($profilePicture) ? $profilePicture : $defaultProfileImage;

            // Check verification status
            $isVerified = ($verificationStatus === 'identity_verified' || $verificationStatus === 'fully_verified');
        }
        $stmt->close();
    }
}

// Assign values to variables for easy use in HTML
$userFirstName = $_SESSION['user_first_name'] ?? 'Guest';
$userLastName = $_SESSION['user_last_name'] ?? '';
$profileImage = $_SESSION['user_profile_image'] ?? '../img/default-profile.png';

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quickfix</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@100;1..900&display=swap');

        body {
            font-family: 'Montserrat', sans-serif;
        }
    </style>

</head>

<body class="bg-gray-100">
    <header class="bg-white shadow px-6 py-4 flex items-center justify-between sticky top-0 z-50">
        <div class="flex items-center space-x-4">
            <img src="../img/logo1.png" alt="Logo" class="h-12">
        </div>

        <!-- Mobile Menu Button -->
        <div class="md:hidden" x-data="{ open: false }">
            <button @click="open = !open" class="text-blue-900 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>

            <div x-show="open" class="absolute top-16 left-0 w-full bg-white shadow-md py-4 flex flex-col items-center space-y-4 md:hidden">
                <a href="./userPostJob.php" class="hover:text-blue-500 text-blue-900 font-semibold">Post a Job</a>
                <a href="./findJobs.php" class="hover:text-blue-500 text-blue-900">Browse Jobs</a>
                <a href="./myJobs.php" class="hover:text-blue-500 text-blue-900">My Job</a>
                <!-- <a href="./aboutListings.php" class="hover:text-blue-500 text-blue-900">My Services</a> -->
                <a href="#" class="hover:text-blue-500 text-blue-900">Help</a>
                <a href="#" class="hover:text-blue-500 text-blue-900">Notifications</a>
            </div>
        </div>

        <!-- Desktop Menu -->
        <nav class="hidden md:flex space-x-6">
            <a href="./userPostJob.php" class="hover:text-blue-500 text-blue-900 font-semibold">Post a Job</a>
            <a href="./findJobs.php" class="hover:text-blue-500 text-blue-900">Browse Jobs</a>
            <a href="./myJobs.php" class="hover:text-blue-500 text-blue-900">My Job</a>
            <!-- <a href="./aboutListings.php" class="hover:text-blue-500 text-blue-900">My Services</a> -->
        </nav>

        <div class="hidden md:flex items-center space-x-6">
            <a href="#" class="hover:text-blue-500 text-blue-900">Help</a>
            <a href="#" class="hover:text-blue-500 text-blue-900">Notifications</a>
            <div x-data="{ isOpen: false }" class="relative">
                <button @click="isOpen = !isOpen" @keydown.escape="isOpen = false" class="focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full">
                    <img src="<?= htmlspecialchars($profileImage); ?>"
                        alt="User Profile"
                        class="w-10 h-10 rounded-full object-cover border border-gray-300 shadow-sm">

                </button>


                <!-- User Menu Dropdown -->
                <div x-show="isOpen" @click.away="isOpen = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                    <a href="#" class="block px-4 py-2 text-md text-blue-900">
                        <?php
                        if (isset($_SESSION['user_first_name']) && isset($_SESSION['user_last_name'])) {
                            echo htmlspecialchars($_SESSION['user_first_name'] . ' ' . $_SESSION['user_last_name']);
                        } else {
                            echo "Guest";
                        }
                        ?>
                    </a>
                    <a href="../user/userVerifyAccount.php"
                        class="block px-4 py-2 text-sm flex items-center gap-2">

                        <!-- Status Indicator -->
                        <span class="text-xs px-2 py-1 rounded-full text-white"
                            style="background-color: 
                                    <?php
                                    if ($verificationStatus === 'fully_verified')  echo '#3B82F6'; // Blue
                                    elseif ($verificationStatus === 'identity_verified') echo '#34D399'; // Green
                                    else echo '#EF4444'; // Red
                                    ?>;">
                            <?php
                            if ($verificationStatus === 'fully_verified') echo 'Fully Verified';
                            elseif ($verificationStatus === 'identity_verified') echo 'Identity Verified';
                            else echo 'Not Verified';
                            ?>
                        </span>
                    </a>
                    <a href="../user/userPublicProfile.php" class="block px-4 py-2 text-sm text-blue-900 hover:bg-gray-100">
                        Public Profile
                    </a>

                    <hr class="my-1 border-gray-200">

                    <!--<a href="../user/userJobSeekerDashboard.php" class="block px-4 py-2 text-sm text-blue-900 hover:bg-gray-100">My Job Seeker Dashboard</a>
                    <a href="../user/userPaymentHistory.php" class="block px-4 py-2 text-sm text-blue-900 hover:bg-gray-100">Payment history</a>
                    <a href="#" class="block px-4 py-2 text-sm text-blue-900 hover:bg-gray-100">Payment methods</a>
                    <hr class="my-1 border-gray-200">-->
                    <!--<a href="#" class="px-4 py-2 text-sm text-blue-900 hover:bg-gray-100 flex justify-between items-center" @click="showSettings = !showSettings">
                        Settings
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>-->
                    <!--<div x-show="showSettings" class="mt-2 space-y-4 bg-gray-50 px-4 py-4">
                        <a href="../user/userMobile.php" class="flex items-center text-sm text-blue-900 hover:bg-gray-100">Mobile</a>
                        <a href="../user/userEmail.php" class="flex items-center text-sm text-blue-900 hover:bg-gray-100">Email</a>
                        <a href="../user/userAccount.php" class="flex items-center text-sm text-blue-900 hover:bg-gray-100">Account</a>
                        <a href="../user/userVerifyAccount.php" class="flex items-center text-sm text-blue-900 hover:bg-gray-100">Verify Account</a>
                        <a href="../user/userChangePassword.php" class="flex items-center text-sm text-blue-900 hover:bg-gray-100">Change Password</a>
                    </div>-->
                    <a href="../user/userLogout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</a>
                </div>
            </div>
        </div>
    </header>
</body>

</html>