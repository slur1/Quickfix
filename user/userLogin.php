<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: findJobs.php');
    exit();
}

include '../config/db_connection.php';

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        try {
            $sql = "SELECT id, first_name, last_name, email, contact_number, password_hash FROM user WHERE BINARY email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $email);

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();

                if (password_verify($password, $user['password_hash'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_first_name'] = $user['first_name'];
                    $_SESSION['user_last_name'] = !empty($user['last_name']) ? $user['last_name'] : 'No Last Name';
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_contact_number'] = $user['contact_number'];

                    header('Location: findJobs.php');
                    exit();
                } else {
                    $error_message = "Invalid email or password. Please try again.";
                }
            } else {
                $error_message = "No user found with that email.";
            }
        } catch (Exception $e) {
            $error_message = "An error occurred: " . $e->getMessage();
        } finally {
            $stmt->close();
            $conn->close();
        }
    } else {
        $error_message = "Please enter both email and password.";
    }
}
?>










<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | QuickFix</title>
    <link rel="icon" type="logo" href="../img/logo1.png">
    <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">


</head>

<body style="font-family: 'Montserrat', sans-serif;">
    <form method="POST" action="">
        <div class="min-h-screen bg-gray-100 text-gray-900 flex justify-center">
            <div class="max-w-screen-xl m-0 sm:m-10 bg-white shadow sm:rounded-lg flex justify-center flex-1">
                <div class="lg:w-1/2 xl:w-5/12 p-6 sm:p-12">
                    <div class="flex justify-center items-center mb-8">
                        <div class="flex items-center space-x-2">
                            <img src="../img/logo1.png" class="w-16 h-16" alt="Quickfix Logo" />
                            <span class="text-3xl font-bold text-blue-950 leading-none">Quickfix</span>
                        </div>
                    </div>

                    <div class="mt-12 flex flex-col items-center">
                        <h1 class="text-2xl xl:text-3xl font-extrabold">
                            Sign In
                        </h1>
                        <div class="w-full flex-1 mt-8">
                            <div class="mx-auto max-w-xs">

                                <!-- Display error message above the inputs -->
                                <?php if (!empty($error_message)): ?>
                                    <div class="mb-4">
                                        <span class="text-sm text-red-500"><?php echo htmlspecialchars($error_message); ?></span>
                                    </div>
                                <?php endif; ?>

                                <input
                                    class="w-full px-8 py-4 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    type="email" id="email" name="email" placeholder="Email" />
                                <input
                                    class="w-full px-8 py-4 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent mt-5"
                                    type="password" id="password" name="password" placeholder="Password" />
                                <div class="flex justify-between space-x-4 mt-5">
                                    <!-- Go Back Button -->
                                    <a href="../index.php"
                                        class="tracking-wide font-semibold bg-gray-300 text-gray-800 w-full py-4 rounded-lg hover:bg-gray-400 transition-all duration-300 ease-in-out flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M15 19l-7-7 7-7" />
                                        </svg>
                                        <span>Go Back</span>
                                    </a>


                                    <!-- Submit Button -->
                                    <button type="submit" class="tracking-wide font-semibold bg-indigo-500 text-gray-100 w-full py-4 rounded-lg hover:bg-indigo-700 transition-all duration-300 ease-in-out flex items-center justify-center focus:shadow-outline focus:outline-none">
                                        <svg class="w-6 h-6 -ml-2" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                                            <circle cx="8.5" cy="7" r="4" />
                                            <path d="M20 8v6M23 11h-6" />
                                        </svg>
                                        <span class="ml-3">
                                            Sign In
                                        </span>
                                    </button>
                                </div>
                                <p class="mt-4 text-center text-sm text-gray-600">
                                    Don't have an account? Create an account to continue.
                                    <a href="user-registration.php" class="text-blue-500 hover:underline">Create Account</a>
                                </p>
                                <!-- Main Page Text -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex-1 bg-indigo-100 text-center hidden lg:flex">
                    <div class="m-12 xl:m-16 w-full">
                        <img src="../img/login.svg" alt="Login SVG" class="w-full h-full object-contain">
                    </div>
                </div>
            </div>
        </div>
    </form>
</body>







</html>