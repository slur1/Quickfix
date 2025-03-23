<?php
// Start the session
session_start();
include '../config/db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: userLogin.php");
    exit;
}

// Fetch verification status
$userId = $_SESSION['user_id'];
$query = "SELECT verification_status FROM user WHERE id = ?";
$verificationStatus = 'unverified'; // Default

if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($verificationStatus);
    $stmt->fetch();
    $stmt->close();
}

// Check if the button should be disabled
$disableIdentityButton = ($verificationStatus === 'identity_verified' || $verificationStatus === 'fully_verified');
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verify Account</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <script src="https://unpkg.com/alpinejs" defer></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
  <link rel="icon" type="logo" href="../img/logo1.png">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');

    body {
      font-family: 'Montserrat', sans-serif;
    }
  </style>
  <style>
/* Modal Styling */
.modal {
    position: fixed;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0, 0, 0, 0.6);
    z-index: 1000;
}

/* Glassmorphic Content Box */
.modal-content {
    background: rgba(255, 255, 255, 0.95); /* White */
    padding: 24px;
    border-radius: 12px;
    text-align: center;
    width: 380px;
    color: #333;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
    animation: fadeIn 0.3s ease-in-out;
}

/* Icons */
.icon-container {
    margin-bottom: 10px;
}

.icon {
    width: 60px;
    height: 60px;
}

/* Button Styling */
.btn-primary, .btn-danger {
    margin-top: 12px;
    padding: 12px 20px;
    border: none;
    font-size: 16px;
    font-weight: bold;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease-in-out;
    width: 100%;
}

.btn-primary {
    background-color: #007BFF; /* Blue */
    color: white;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.btn-danger {
    background-color: #e74c3c;
    color: white;
}

.btn-danger:hover {
    background-color: #c0392b;
}

/* Animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Hide by default */
.hidden {
    display: none;
}
</style>

</head>

<?php
include './userHeader.php';
?>

<body class="bg-gray-50">
  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white p-6 shadow-sm">
      <?php include './userSideBar.php'; ?>
    </aside>


    <body class="bg-gray-50" x-data="{ 
    showIdentityModal: false,
    showTaxModal: false 
}">

      <!-- Main Content -->
      <main class="max-w-2xl mx-auto p-6">
        <h1 class="text-4xl font-bold text-blue-900 mb-6">Verify account</h1>

        <p class="text-gray-800 mb-8">
          We're now legally required to verify some of your account information. Please take a few minutes to complete the following:
        </p>

        <!-- Verification Steps -->
        <div class="space-y-4 mb-8">
          <!-- Verify Identity Step -->
          <button 
              <?php if ($disableIdentityButton) : ?>
                  onclick="openPopup()" 
                  class="w-full flex items-start p-4 bg-gray-200 text-gray-500 rounded-lg cursor-not-allowed"
              <?php else : ?>
                  @click="showIdentityModal = true"
                  class="w-full flex items-start p-4 hover:bg-gray-50 rounded-lg group"
              <?php endif; ?>
          >
              <div class="flex-1">
                  <div class="flex items-center gap-4">
                      <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                      </svg>
                      <div>
                          <h2 class="text-xl font-semibold text-blue-900">Verify your identity</h2>
                          <p class="text-gray-600">Step 1: Less than 5 minutes</p>
                      </div>
                  </div>
              </div>
              <svg class="w-6 h-6 text-gray-400 group-hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
          </button>

          <!-- Submit Tax Profile Step -->
          <button @click="showTaxModal = true" class="w-full flex items-start p-4 hover:bg-gray-50 rounded-lg group">
            <div class="flex-1">
              <div class="flex items-center gap-4">
                <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <div>
                  <h2 class="text-xl font-semibold text-blue-900">Submit NC2 File</h2>
                  <p class="text-gray-600">Step 2: Less than 10 minutes</p>
                </div>
              </div>
            </div>
            <svg class="w-6 h-6 text-gray-400 group-hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </button>
        </div>

        <a href="#" class="text-blue-600 hover:text-blue-700 font-medium">Learn More</a>

        <!-- Warning Section -->
        <div class="mt-8 flex gap-4 p-4 bg-orange-50 rounded-lg">
          <svg class="w-6 h-6 text-orange-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
          <div>
            <h3 class="font-semibold text-gray-900">Avoid verification issues</h3>
            <p class="text-gray-600">If reporting as an individual, ensure the same <span class="font-medium">legal name and date of birth</span> are used on both steps to avoid having to re-verify.</p>
          </div>
        </div>

        <button class="mt-8 w-full bg-gray-100 text-gray-600 py-3 px-4 rounded-full hover:bg-gray-200 font-medium">
          Finish verifying account
        </button>

        <p class="mt-6 text-sm text-gray-500">
          Any information captured in this process is used for security and job seeker's capability to work only.
          <a href="#" class="text-blue-600 hover:text-blue-700">Learn more about how QuickFix handles your information</a>.
        </p>
      </main>

<!-- Identity Verification Modal -->
<div x-show="showIdentityModal"
    class="fixed inset-0 bg-black bg-opacity-50 flex justify-center mt-16 p-5"
    x-transition>

    <div class="bg-white rounded-2xl max-w-lg w-full p-6 relative">
      <button @click="showIdentityModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-500">
        ✖
      </button>

      <h2 class="text-2xl font-bold text-gray-900 mb-3">Verify your identity</h2>
      <p class="text-base text-gray-600 mb-5">
        Get ID verified with IDAnalyzer, a third-party provider helping us verify your identity.
      </p>

      <h3 class="text-lg font-medium text-gray-900 mb-3">Here's what you'll need:</h3>
      <ul class="space-y-2 mb-4 text-base text-gray-600">
        <li>✅ A valid ID document such as your National ID, Passport, Driver's License.</li>
      </ul>

      <h3 class="text-lg font-medium text-gray-900 mb-3">Make sure your ID:</h3>
      <ul class="space-y-2 mb-4 text-base text-gray-600">
        <li class="flex items-center gap-2">
          <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          Is not blurry and all text is readable.
        </li>
        <li class="flex items-center gap-2">
          <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          Is well-lit and clearly visible.
        </li>
        <li class="flex items-center gap-2">
          <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          Has no glare or reflections.
        </li>
        <li class="flex items-center gap-2">
          <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          Is not cropped; the full ID is within the frame.
        </li>
      </ul>

      <form id="idVerificationForm">
        <label class="text-base font-medium text-gray-700">Select ID Type:</label>
        <select id="id_type" name="id_type" required class="border p-2 w-full text-base mb-3">
          <option value="passport">Passport</option>
          <option value="nationalid">National ID</option>
          <option value="driverlicense">Driver's License</option>
        </select>

        <label class="text-base font-medium text-gray-700">Upload ID Image:</label>
        <input type="file" id="id_image" name="id_image" accept="image/*" required class="border p-2 w-full text-base mb-3">

        <button type="button" onclick="submitIDVerification()" class="mt-1 w-full bg-blue-500 text-white py-3 px-4 rounded-full hover:bg-blue-600 text-base font-medium">
          Verify now
        </button>
      </form>

<div id="result"></div>

    </div>
</div>




      <!-- Tax Profile Modal -->
      <div x-show="showTaxModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4"
        x-transition>
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 relative">
          <button @click="showTaxModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-500">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>

          <h2 class="text-3xl font-bold text-gray-900 mb-4">Submit NC2 Document</h2>
          <p class="text-gray-600 mb-6">
            The admin will verify your document.
          </p>

          <ul class="space-y-3 mb-6 list-disc pl-5 text-gray-600">
            <li>Legal name</li>
            <li>Primary address</li>
            <li>Your NC2 Document</li>
          </ul>

          <a href="#" class="text-blue-600 hover:text-blue-700 font-medium">How does this work?</a>

          <button class="mt-8 w-full bg-blue-500 text-white py-3 px-4 rounded-full hover:bg-blue-600 font-medium">
            Submit NC2 Document
          </button>
        </div>
      </div>

<!-- Loading Animation -->
<div id="loading" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="flex flex-col items-center">
        <div class="animate-spin border-4 border-blue-500 border-t-transparent rounded-full h-14 w-14"></div>
        <p class="text-white mt-2 font-semibold text-lg">Verifying, please wait...</p>
    </div>
</div>

<!-- Success Pop-up -->
<div id="successModal" class="modal hidden">
    <div class="modal-content success">
    <i class="fa-solid fa-circle-check text-green-500 text-4xl"></i> <!-- Success -->
        <h2>Verification Successful!</h2>
        <p>Your ID has been verified successfully. You can now access all features.</p>
        <button onclick="closeAllModals()" class="btn-primary">OK</button>
    </div>
</div>

<!-- Rejection Pop-up -->
<div id="rejectionModal" class="modal hidden">
    <div class="modal-content error">
        <i class="fa-solid fa-circle-xmark text-red-500 text-4xl mb-2"></i> <!-- Error Icon -->
        <h2 class="text-xl font-semibold">Verification Failed</h2>
        <p class="mt-2">Your ID submission was rejected. Please ensure:</p>
        <div class="text-left mt-3">
            <ul class="list-disc list-inside">
                <li>ID is clear and not blurry.</li>
                <li>Proper lighting (no shadows or glare).</li>
                <li>The entire ID is visible (not cropped).</li>
            </ul>
        </div>
        <button onclick="closeAllModals()" class="btn-danger mt-4">OK</button>
    </div>
</div>

<!-- Custom Popup Modal -->
<div id="popup" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96 text-center">
        <h2 class="text-xl font-semibold text-gray-900">Verification Already Completed</h2>
        <p class="text-gray-600 mt-2">You have already completed this step!</p>
        <button onclick="closePopup()" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">OK</button>
    </div>
</div>

<!-- JavaScript to Handle Popup -->
<script>
function openPopup() {
    document.getElementById("popup").classList.remove("hidden");
}
function closePopup() {
    document.getElementById("popup").classList.add("hidden");
}


function submitIDVerification() {
    let idType = document.getElementById("id_type").value;
    let idImage = document.getElementById("id_image").files[0];
    let loadingDiv = document.getElementById("loading");

    if (!idImage) {
        alert("Please upload an ID image.");
        return;
    }

    let formData = new FormData();
    formData.append("id_type", idType);
    formData.append("id_image", idImage);

    loadingDiv.style.display = "flex"; // Show loading animation

    fetch("verify_id.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        loadingDiv.style.display = "none"; // Hide loading animation

        if (data.success) {
            if (data.status === "approved") {
                document.getElementById("successModal").classList.remove("hidden"); // Show success modal
                updateVerificationStatus("identity_verified"); // Update database
            } else if (data.status === "rejected") {
                document.getElementById("rejectionModal").classList.remove("hidden"); // Show rejection modal
                updateVerificationStatus("unverified"); // Update database
            }
        } else {
            alert("Error: " + data.error);
        }
    })
    .catch(error => {
        loadingDiv.style.display = "none"; // Hide loading animation
        alert("Something went wrong.");
        console.error("Error:", error);
    });
}

// Function to update verification status in the database
function updateVerificationStatus(status) {
    fetch("update_verification_status.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            console.error("Error updating verification status:", data.error);
        }
    })
    .catch(error => console.error("Error:", error));
}

// Close all modals including identity and tax verification
function closeAllModals() {
    document.querySelectorAll(".modal").forEach(modal => {
        modal.classList.add("hidden");
    });

    let alpineRoot = document.querySelector("[x-data]");
    if (alpineRoot && alpineRoot.__x) {
        alpineRoot.__x.$data.showIdentityModal = false;
        alpineRoot.__x.$data.showTaxModal = false;
    }
}

</script>




    </body>

    
</html>