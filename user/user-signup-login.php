<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quickfix Sign Up / Login</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
    rel="stylesheet">
  <link rel="icon" type="logo" href="../img/logo1.png">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-screen w-screen bg-gradient-to-r from-blue-200 to-blue-500" style="font-family: 'Montserrat', sans-serif;">

  <!-- Centered Login Card (Form) -->
  <div class="flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md text-center">
      <h1 class="text-3xl font-semibold text-blue-950 mb-4">QuickFix</h1>
      
      <!-- Sign up Button -->
      <form action="user-registration.php" method="get">
      <button class="w-full py-2 bg-gradient-to-r from-blue-200 to-blue-500 text-white rounded-full font-medium mb-4 hover:from-blue-500 hover:to-blue-950 transition">
        Sign up
      </button>
      </form>
      
      <!-- Log in Button -->
      <form action="user-login.php" method="get">
      <button type="submit" class="w-full py-2 border-2 border-round bg-transparent rounded-full font-medium mb-4 hover:bg-blue-100 transition text-blue-500">
        Log in
      </button>
      </form>

      <p class="text-xs text-gray-600 text-center">
        I agree to abide by QuickFix's
        <a href="#" id="terms-link" class="border-b border-gray-500 border-dotted">
            Terms and Conditions
        </a>
        and its
        <a href="#" id="privacy-link" class="border-b border-gray-500 border-dotted">
            Privacy Policy
        </a>
      </p>

    </div>
  </div>

  <!-- Terms and Conditions Modal -->
  <div id="terms-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
        <h2 class="text-lg font-semibold mb-4 text-gray-800">Terms and Conditions</h2>
        <div class="overflow-y-auto max-h-80 text-sm text-gray-600 space-y-4">
            <p>Welcome to QuickFix! By accessing or using our platform, you agree to be bound by these Terms and Conditions. Please read them carefully. If you do not agree, you may not use our services.</p>
            
            <p><strong>1. Definitions</strong><br>
            <strong>QuickFix:</strong> The platform connecting users who require short-term services (Clients) with service providers (Providers).<br>
            <strong>Users:</strong> Any individual accessing or using the QuickFix platform, including Clients and Providers.</p>
            
            <p><strong>2. Eligibility</strong><br>
            To use QuickFix, you must:</p>
            <ul class="list-disc pl-5 space-y-2">
                <li>Be at least 18 years old.</li>
                <li>Provide accurate and complete registration information.</li>
                <li>Comply with these Terms and Conditions.</li>
            </ul>
            
            <p><strong>3. Services</strong><br>
            QuickFix acts as a marketplace to connect Clients with Providers for short-term jobs, such as house cleaning and house repairs. QuickFix does not directly employ Providers or guarantee the quality of their work.</p>

            <p><strong>4. User Responsibilities</strong><br></p>
            <ul class="list-disc pl-5 space-y-2">
                <li>Users must ensure that all information provided, including IDs and other documents, is accurate and up to date.
                    Providers must hold appropriate qualifications or certifications for jobs that require them.</li>
                <li>Users are responsible for complying with all applicable local, state, and federal laws.</li>
            </ul>

            <p><strong>5. Payments</strong><br></p>
            <ul class="list-disc pl-5 space-y-2">
                <li>QuickFix plans to integrate a payment method to facilitate transactions between Clients and Providers. Once implemented, users must comply with payment processing rules and fees.</li>
                <li>Payments will be securely handled through a third-party payment processor.</li>
            </ul>

            <p><strong>6. Prohibited Activities</strong><br>
            Users may not:</p>
            <ul class="list-disc pl-5 space-y-2">
                <li>Misrepresent their identity or qualifications.</li>
                <li>Engage in fraudulent or unlawful activities.</li>
                <li>Use the platform for any purpose other than its intended use.</li>
            </ul>

            <p><strong>7. Limitation of Liability</strong><br>
            QuickFix is not liable for:</p>
            <ul class="list-disc pl-5 space-y-2">
                <li>Any disputes, damages, or losses arising between Clients and Providers.</li>
                <li>Unauthorized access to your data or account caused by user negligence.</li>
            </ul>

            <p><strong>8. Termination</strong><br>
             QuickFix reserves the right to terminate or suspend access to the platform for users who violate these Terms and Conditions.</p>

            <p><strong>9. Modifications</strong><br>
             QuickFix may update these Terms and Conditions from time to time. Continued use of the platform constitutes acceptance of the updated terms.</p>

            <p><strong>10. Governing Law</strong><br>
             These Terms and Conditions are governed by the laws of [Insert Jurisdiction].</p>

        </div>
        <div class="flex justify-end mt-4">
            <button id="terms-close" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-500 focus:outline-none">
                Close
            </button>
        </div>
    </div>
  </div>

  <!-- Privacy Policy Modal -->
  <div id="privacy-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
        <h2 class="text-lg font-semibold mb-4 text-gray-800">Privacy Policy</h2>
        <div class="overflow-y-auto max-h-80 text-sm text-gray-600 space-y-4">
            <p>QuickFix values your privacy and is committed to protecting your personal information. This Privacy Policy explains how we collect, use, and protect your data.</p>
            
            <p><strong>1. Information We Collect</strong><br>
            We may collect the following types of information:</p>
            <ul class="list-disc pl-5 space-y-2">
                <li><strong>Personal Information:</strong> Name, contact details, and ID documents for job verification purposes.</li>
                <li><strong>Payment Information:</strong> When payments are integrated, we will collect necessary details to process transactions.</li>
                <li><strong>Usage Data:</strong> Information about your interactions with our platform.</li>
            </ul>
            
            <p><strong>2. How We Use Your Information</strong><br>
            We use your data to:</p>
            <ul class="list-disc pl-5 space-y-2">
                <li>Facilitate connections between Clients and Providers.</li>
                <li>Verify user identities for jobs that require additional documentation.</li>
                <li>Improve our platform and services.</li>
                <li>Process payments securely (once integrated).</li>
            </ul>

            <p><strong>3. Data Sharing</strong><br>
            We may share your information with:</p>
            <ul class="list-disc pl-5 space-y-2">
                <li>Third-party service providers, such as payment processors or identity verification services.</li>
                <li>Law enforcement, if required by applicable law or regulations.</li>
            </ul>
            
            <p><strong>4. Data Security</strong><br>
             We implement reasonable security measures to protect your data. However, no system can guarantee 100% security. Users are responsible for safeguarding their account credentials.</p>

             <p><strong>5. Cookies and Tracking</strong><br>
             QuickFix uses cookies and similar technologies to enhance your user experience and analyze site traffic.</p>

             <p><strong>6. Your Rights</strong><br>
            You have the right to:</p>
            <ul class="list-disc pl-5 space-y-2">
                <li>Access, update, or delete your personal information.</li>
                <li>Opt-out of certain data collection or processing activities.</li>
            </ul>

            <p><strong>7. Retention of Data</strong><br>
             We retain your information for as long as necessary to provide our services and comply with legal obligations.</p>

             <p><strong>8. Updates to this Policy</strong><br>
             We may update this Privacy Policy from time to time. Any changes will be posted on our website with a revised effective date.</p>
             
             <p><strong>9. Contact Us</strong><br>
             If you have any questions or concerns about this Privacy Policy, please contact us at quickfix388@gmail.com</p>
            
        </div>
        <div class="flex justify-end mt-4">
            <button id="privacy-close" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-500 focus:outline-none">
                Close
            </button>
        </div>
    </div>
  </div>

  <!-- Help Button at the Bottom Left -->
  <div class="fixed bottom-4 left-4">
    <button class="flex items-center p-2 bg-blue-500 text-white rounded-full shadow-lg hover:bg-blue-950">
      <span class="text-sm font-medium">Help</span>
    </button>
  </div>

</body>
  <script>
    // Show Terms Modal
document.getElementById('terms-link').addEventListener('click', function (e) {
    e.preventDefault();
    const modal = document.getElementById('terms-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
});

// Hide Terms Modal
document.getElementById('terms-close').addEventListener('click', function () {
    const modal = document.getElementById('terms-modal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
});

// Show Privacy Modal
document.getElementById('privacy-link').addEventListener('click', function (e) {
    e.preventDefault();
    const modal = document.getElementById('privacy-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
});

// Hide Privacy Modal
document.getElementById('privacy-close').addEventListener('click', function () {
    const modal = document.getElementById('privacy-modal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
});
</script>
</html>
