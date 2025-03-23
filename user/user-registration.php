<?php
include '../config/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data with validation
    $email = $_POST['email'] ?? null;
    $password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;
    

    // Validate required fields
    if (!$email || !$password) {
        die('All fields are required. Please fill out the form completely.');
    }

    // Check if the email already exists in the database
    $checkEmailSql = "SELECT email_verified FROM user WHERE email = ?";
    $stmt = $conn->prepare($checkEmailSql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->bind_result($email_verified);
    $stmt->fetch();
    $stmt->close();

    // Check if the email exists and if it is verified
    if ($email_verified !== null) {
        if ((int)$email_verified === 1) {
            header("Location: user-registration.php?error=email_verified");
            exit;
        } else {
            header("Location: user-registration.php?error=email_exists");
            exit;
        }
    }


    // Generate a unique verification token
    $verification_code = substr(md5(uniqid(rand(), true)), 0, 6);

    // Insert user data and verification token into the database
    try {
        $sql = "INSERT INTO user (email, password_hash, verification_code, email_verified)
                VALUES (?, ?, ?, 0)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $email, $password, $verification_code);

        if ($stmt->execute()) {
            header("Location: send_email.php?email=$email&code=$verification_code");
            exit;
        } else {
            throw new Exception($stmt->error);
        }

        $stmt->close();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

    $conn->close();
}
?>





<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up | QuickFix</title>
  <link rel="icon" type="logo" href="../img/logo1.png">
  <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  



  <script>
    // Function to close the modal
    function closeModal() {
      document.getElementById('errorModal').classList.add('hidden');
    }
  </script>

  <script>
    // Custom Error Placement
    function customErrorPlacement(error, element) {
      const errorContainer = element.siblings(".error-message");
      if (errorContainer.length) {
        errorContainer.text(error.text()).removeClass("hidden");
      } else {
        error.insertAfter(element); // Default behavior if .error-message is missing
      }
    }

    // Highlight input field with errors
    function customHighlight(element) {
      $(element).addClass("border-red-500").removeClass("border-gray-300");
    }

    // Remove error highlighting and hide messages when valid
    function customUnhighlight(element) {
      $(element).removeClass("border-red-500").addClass("border-gray-300");
      const errorContainer = $(element).siblings(".error-message");
      if (errorContainer.length) {
        errorContainer.addClass("hidden");
      }
    }

    // Add a custom method for pattern validation
    $.validator.addMethod(
      "pattern",
      function(value, element, param) {
        return this.optional(element) || param.test(value);
      },
      "Invalid format." // Default error message for pattern
    );

    // Sanitize input for only letter characters
    function sanitizeInput(selector) {
      $(document).on("input", selector, function() {
        const cleanValue = this.value.replace(/[^a-zA-Z\s]/g, ""); // Allow spaces too
        if (this.value !== cleanValue) {
          this.value = cleanValue;
        }
      });
    }


    $(function() {
    

      // Initialize form validation
      $("form").validate({
        ignore: [], // Include all fields in validation
        rules: {
          
          email: {
            required: true,
            email: true,
          },

          password: {
                required: true,
                minlength: 8,
                pattern: /^(?=.*\d)(?=.*[A-Z])(?=.*[a-z]).{8,}$/,
            },
            confirm_password: {
                required: true,
                equalTo: "#password",
            }
        },
        messages: {
        
          email: {
            required: "Email is required.",
            email: "Please enter a valid email address.",
          },
          password: {
            required: "Password is required.",
            pattern: "Password should contain at least one number, one lowercase letter, one uppercase letter, and be at least 8 characters long.",
          },
          confirm_password: {
            required: "Confirm your password.",
            equalTo: "Passwords do not match.",
          },
        },
        errorPlacement: customErrorPlacement,
        highlight: customHighlight,
        unhighlight: customUnhighlight,
      });
    });
  </script>


  <script>
    // Display SweetAlert if there is an error
    document.addEventListener('DOMContentLoaded', function() {
      const urlParams = new URLSearchParams(window.location.search);
      if (urlParams.has('error')) {
        let errorMessage = '';

        switch (urlParams.get('error')) {
          case 'email_exists':
            errorMessage = 'The email you entered already exists. Please use a different email.';
            break;
          case 'email_verified':
            errorMessage = 'The email you entered is already verified. Please log in.';
            break;
          default:
            errorMessage = 'An unknown error occurred. Please try again.';
        }

        // Show SweetAlert for error
        Swal.fire({
          title: 'Error',
          text: errorMessage,
          icon: 'error',
          confirmButtonText: 'OK'
        });
      }
    });
  </script>


</head>

<body style="font-family: 'Montserrat', sans-serif;">
    <form method="POST" action="user-registration.php" enctype="multipart/form-data">
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
                        <h1 class="text-2xl xl:text-3xl font-extrabold">Sign Up</h1>
                        <div class="w-full flex-1 mt-8">
                            <div class="mx-auto max-w-xs">
                                <input class="w-full px-8 py-4 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" type="email" id="email" name="email" placeholder="Email" required />
                                <input class="w-full px-8 py-4 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent mt-5" type="password" id="password" name="password" placeholder="Password" required />
                                <input class="w-full px-8 py-4 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent mt-5" type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required />

                              <!-- Terms and Conditions Checkbox -->
                                <div class="mt-5">
                                  <div class="flex items-center">
                                    <input type="checkbox" id="accept-terms" class="w-4 h-4 text-blue-500 border-gray-300 rounded focus:ring-2 focus:ring-blue-500" />
                                    <label for="accept-terms" class="ml-2 text-sm text-gray-700">
                                      I agree to the 
                                      <a href="#" id="terms-link" class="font-semibold text-blue-600 hover:text-blue-800 transition-all duration-200 underline">
                                        Terms and Conditions
                                      </a> 
                                      and 
                                      <a href="#" id="privacy-link" class="font-semibold text-blue-600 hover:text-blue-800 transition-all duration-200 underline">
                                        Privacy Policy
                                      </a>.
                                    </label>
                                  </div>
                                  <p id="terms-error" class="text-red-500 text-xs mt-1 hidden">You must agree to the Terms & Conditions.</p>
                                </div>

                                                                
                                <div class="flex justify-between space-x-4 mt-5">
                                    <a href="../index.php" class="tracking-wide font-semibold bg-gray-300 text-gray-800 w-full py-4 rounded-lg hover:bg-gray-400 transition-all duration-300 ease-in-out flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M15 19l-7-7 7-7" />
                                        </svg>
                                        <span>Go Back</span>
                                    </a>
                                    <button type="submit" class="tracking-wide font-semibold text-base bg-indigo-500 text-gray-100 min-w-[100px] w-full py-4 rounded-lg hover:bg-indigo-700 transition-all duration-300 ease-in-out flex items-center justify-center focus:shadow-outline focus:outline-none">
                                        <span class="ml-3">Create Account</span>
                                    </button>
                                </div>
                                <p class="mt-4 text-center text-sm text-gray-600">
                                    Already have an account?
                                    <a href="userlogin.php" class="text-blue-500 hover:underline">Sign In</a>
                                </p>
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


    <!-- Terms and Conditions Modal -->
    <div id="terms-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
      <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
        <h2 class="text-lg font-semibold mb-4 text-gray-800">Terms and Conditions</h2>
        <div class="overflow-y-auto max-h-80 text-sm text-gray-600 space-y-4">
          <p>Welcome to QuickFix! By accessing or using our platform, you agree to be bound by these Terms and Conditions. Please read them carefully. If you do not agree, you may not use our services.</p>

          <p><strong>1. Definitions</strong><br>
            <strong>QuickFix:</strong> The platform connecting users who require short-term services (Clients) with service providers (Providers).<br>
            <strong>Users:</strong> Any individual accessing or using the QuickFix platform, including Clients and Providers.
          </p>

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
        </section>
  </main>

  <script>
  const termsCheckbox = document.getElementById("accept-terms");
  const errorText = document.getElementById("terms-error");
  const form = document.querySelector("form");

  // Real-time validation (removes red error when checked)
  termsCheckbox.addEventListener("change", function () {
    if (termsCheckbox.checked) {
      errorText.classList.add("hidden");
      termsCheckbox.classList.remove("border-red-500", "ring-2", "ring-red-500");
    }
  });

  // Form submission validation
  form.addEventListener("submit", function (e) {
    if (!termsCheckbox.checked) {
      e.preventDefault(); // Prevent form submission

      // Show error message & red border
      errorText.classList.remove("hidden");
      termsCheckbox.classList.add("border-red-500", "ring-2", "ring-red-500");
    }
  });

    // Show Terms Modal Automatically on Page Load
  window.onload = function () {
    const modal = document.getElementById('terms-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
  };

  // Hide Terms Modal
  document.getElementById('terms-close').addEventListener('click', function () {
    const modal = document.getElementById('terms-modal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
  });

    // Show Terms Modal
    document.getElementById('terms-link').addEventListener('click', function(e) {
      e.preventDefault();
      const modal = document.getElementById('terms-modal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    });

    // Hide Terms Modal
    document.getElementById('terms-close').addEventListener('click', function() {
      const modal = document.getElementById('terms-modal');
      modal.classList.remove('flex');
      modal.classList.add('hidden');
    });

    // Show Privacy Modal
    document.getElementById('privacy-link').addEventListener('click', function(e) {
      e.preventDefault();
      const modal = document.getElementById('privacy-modal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    });

    // Hide Privacy Modal
    document.getElementById('privacy-close').addEventListener('click', function() {
      const modal = document.getElementById('privacy-modal');
      modal.classList.remove('flex');
      modal.classList.add('hidden');
    });
  </script>

</body>

</html>