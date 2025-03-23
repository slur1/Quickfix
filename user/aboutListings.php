<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
  // Redirect to the login page if not logged in
  header("Location: userLogin.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> About Listings | QuickFix</title>
  <link rel="icon" type="image/png" href="../img/logo1.png">
  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');

    body {
      font-family: 'Montserrat', sans-serif;
    }
  </style>

</head>

<?php
include './userHeader.php';
?>

<!-- Hero Section -->
<section class="bg-blue-100 text-white py-24 px-10 text-center">
  <h1 class="text-5xl text-blue-900 font-bold">List your skills fast and free, with Listings</h1>
  <p class="mt-6 text-gray-600 text-xl">Advertise your skills to millions of people on QuickFix looking to get more done.</p>
  <button class="mt-8 bg-blue-500 text-white px-8 py-4 rounded font-semibold shadow-lg hover:bg-blue-800 text-lg">Create my first listing</button>
</section>

<!-- Features Section -->
<section class="py-16 px-10 grid grid-cols-1 lg:grid-cols-4 gap-8">
  <!-- Free Marketing -->
  <div class="bg-white shadow-lg rounded-lg p-8 text-center">
    <div class="flex justify-center mb-6">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89-3.94a2 2 0 011.78 0L21 8m-18 0v8a2 2 0 002 2h14a2 2 0 002-2V8M3 8l7.89 3.94a2 2 0 001.78 0L21 8" />
      </svg>
    </div>
    <h3 class="text-2xl font-semibold mb-6">Free Marketing</h3>
    <p class="text-lg">Create a listing to make your services visible to QuickFix customers. Free of charge.</p>
  </div>

  <!-- You Set the Terms -->
  <div class="bg-white shadow-lg rounded-lg p-8 text-center">
    <div class="flex justify-center mb-6">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-7-7v14m4-6H7" />
      </svg>
    </div>
    <h3 class="text-2xl font-semibold mb-6">You Set the Terms</h3>
    <p class="text-lg">Set the scope, price, and availability as you like.</p>
  </div>

  <!-- Customers Come to You -->
  <div class="bg-white shadow-lg rounded-lg p-8 text-center">
    <div class="flex justify-center mb-6">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M17 9l-5 5m0 0l-5-5m5 5V3" />
      </svg>
    </div>
    <h3 class="text-2xl font-semibold mb-6">Customers Come to You</h3>
    <p class="text-lg">Spend less time searching for work and more time earning.</p>
  </div>

  <!-- Build Your Brand -->
  <div class="bg-white shadow-lg rounded-lg p-8 text-center">
    <div class="flex justify-center mb-6">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c.36 1.27.96 2.37 1.72 3.24C15.21 13.2 18 15 18 15m-6-7c-.36 1.27-.96 2.37-1.72 3.24C8.79 13.2 6 15 6 15m6-7c.36-1.27.96-2.37 1.72-3.24C15.21 4.8 18 3 18 3M12 8c-.36-1.27-.96-2.37-1.72-3.24C8.79 4.8 6 3 6 3" />
      </svg>
    </div>
    <h3 class="text-2xl font-semibold mb-6">Build Your Brand</h3>
    <p class="text-lg">Stand out from the competition and grow your reputation.</p>
  </div>
</section>

<!-- Create Listings Section -->
<section class="py-24 bg-gray-100 px-10">
  <div class="max-w-5xl mx-auto text-center">
    <h2 class="text-4xl font-bold mb-8">Create unlimited listings.</h2>
    <p class="text-xl mb-8">Set up as many listings as you like for all the services you wish to offer. Add price packages and create a customized customer experience.</p>
    <button class="bg-blue-600 text-white px-8 py-4 rounded font-semibold shadow-lg hover:bg-blue-700 text-lg">Create your first listing</button>
  </div>
</section>

<!-- FAQ Section -->
<section class="bg-white py-16 px-10">
  <h2 class="text-center text-4xl font-bold mb-8">Frequently asked questions</h2>
  <div class="max-w-5xl mx-auto">
    <div class="border-t border-b divide-y">
      <details class="py-6">
        <summary class="cursor-pointer font-semibold text-xl">What is a listing?</summary>
        <p class="mt-3 text-gray-700 text-lg">A listing is a way to showcase your services about house cleaning and house repair on QuickFix.</p>
      </details>
      <details class="py-6">
        <summary class="cursor-pointer font-semibold text-xl">How much does it cost to create a listing?</summary>
        <p class="mt-3 text-gray-700 text-lg">Creating a listing is free on QuickFix.</p>
      </details>
      <details class="py-6">
        <summary class="cursor-pointer font-semibold text-xl">What services can I create a listing for?</summary>
        <p class="mt-3 text-gray-700 text-lg">You can list services about house cleaning and house repair.</p>
      </details>
      <details class="py-6">
        <summary class="cursor-pointer font-semibold text-xl">Once I create a listing, when will I start getting bookings?</summary>
        <p class="mt-3 text-gray-700 text-lg">Bookings depend on how visible and attractive your listing is to buyers.</p>
      </details>
      <details class="py-6">
        <summary class="cursor-pointer font-semibold text-xl">How do customers contact and book me?</summary>
        <p class="mt-3 text-gray-700 text-lg">Customers can contact and book you through the QuickFix platform.</p>
      </details>
    </div>
  </div>
</section>


<!-- Footer -->
<footer class="bg-gray-800 text-white py-8 text-center">
  <p class="text-sm">© QuickFix 2024. All rights reserved.</p>
</footer>

</body>

</html>