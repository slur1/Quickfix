<?php
session_start();
include '../config/db_connection.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: userLogin.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verify Account</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
  <link rel="icon" type="logo" href="../img/logo1.png">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');

    body {
      font-family: 'Montserrat', sans-serif;
    }
  </style>
</head>

<body class="bg-gray-50">
  <?php include './userHeader.php'; ?>

  <?php
  if ($verificationStatus === 'identity_verified') {
  ?>
    <!-- Background Elements -->
    <div class="fixed inset-0 overflow-hidden z-0 pointer-events-none">
      <!-- Top right circle -->
      <div class="absolute top-0 right-0 w-64 h-64 bg-blue-200 rounded-full -translate-y-1/2 translate-x-1/2 opacity-70"></div>

      <!-- Bottom left circle -->
      <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-200 rounded-full translate-y-1/2 -translate-x-1/2 opacity-70"></div>

      <!-- Floating elements -->
      <div class="absolute top-1/4 left-1/4 w-8 h-8 bg-blue-300 rounded-full animate-float opacity-60"></div>
      <div class="absolute top-1/3 right-1/4 w-12 h-12 bg-blue-300 rounded-full animate-float opacity-60"></div>
      <div class="absolute bottom-1/4 left-1/3 w-10 h-10 bg-blue-300 rounded-full animate-float opacity-60"></div>
      <div class="absolute bottom-1/3 right-1/3 w-6 h-6 bg-blue-300 rounded-full animate-float opacity-60"></div>
    </div>

    <!-- Main Content -->
    <div class="relative z-10 flex items-center justify-center min-h-screen px-4 py-12">
      <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12 max-w-2xl w-full text-center">
        <!-- Success Icon -->
        <div class="mb-8 relative flex justify-center">
          <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center relative">
            <div class="w-20 h-20 bg-blue-500 rounded-full flex items-center justify-center animate-pulse-slow">
              <i class="fas fa-check text-white text-4xl"></i>
            </div>
            <div class="absolute w-24 h-24 border-4 border-blue-200 rounded-full opacity-50"></div>
            <div class="absolute w-28 h-28 border-2 border-blue-100 rounded-full"></div>
          </div>
        </div>

        <!-- Success Message -->
        <h1 class="text-3xl md:text-4xl font-bold text-blue-800 mb-4">Account Verified!</h1>
        <div class="w-16 h-1 bg-blue-500 mx-auto mb-6"></div>
        <p class="text-lg text-gray-600 mb-8">Congratulations! Your account has been successfully verified. You now have full access to our platform.</p>

        <!-- Features -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="bg-blue-50 p-6 rounded-xl flex flex-col items-center text-center">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-4">
              <i class="fas fa-search text-blue-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-blue-800 mb-2">Browse Jobs</h3>
            <p class="text-gray-600">Explore short-term job opportunities that match your skills and experience.</p>
          </div>

          <div class="bg-blue-50 p-6 rounded-xl flex flex-col items-center text-center">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-4">
              <i class="fas fa-file-alt text-blue-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-blue-800 mb-2">Post Jobs</h3>
            <p class="text-gray-600">Create job listings and find the perfect job seekers.</p>
          </div>
        </div>
      </div>
    </div>

  <?php
  } else {
  ?>
    <div class="flex min-h-screen">




      <main class="flex-1 p-4 md:p-6 overflow-auto">
        <div class="max-w-3xl mx-auto">

          <div class="md:hidden mb-4">
            <button id="sidebarToggle" class="p-2 bg-white rounded-md shadow-sm">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
              </svg>
            </button>
          </div>

          <!-- Header -->
          <header class="mb-8">
            <h1 class="text-3xl font-bold text-blue-800">Verify your identity</h1>
            <p class="text-gray-600 mt-2">Complete this verification process to continue (less than 5 minutes)</p>
          </header>

          <!-- Main Verification Section -->
          <div class="bg-white rounded-xl shadow-md p-4 md:p-6 mb-8">
            <div class="flex items-center gap-4 mb-6">
              <div class="">
                <img src="../img/userAccountVerify.svg" alt="Verify Account" class="h-10 w-10">
              </div>
              <div>
                <h2 class="text-xl font-semibold text-blue-900">ID Verification</h2>
                <p class="text-gray-600">We need to verify your identity for security purposes</p>
              </div>
            </div>

            <div class="mb-6">
              <h3 class="text-lg font-medium text-gray-900 mb-3">Make sure your ID:</h3>
              <ul class="space-y-2 mb-4 text-base text-gray-600">
                <li class="flex items-center gap-2">
                  <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                  <span>Is not blurry and all text is readable.</span>
                </li>
                <li class="flex items-center gap-2">
                  <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                  <span>Is well-lit and clearly visible.</span>
                </li>
                <li class="flex items-center gap-2">
                  <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                  <span>Has no glare or reflections.</span>
                </li>
                <li class="flex items-center gap-2">
                  <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                  <span>Is not cropped; the full ID is within the frame.</span>
                </li>
              </ul>
            </div>

            <form id="idVerificationForm" class="space-y-6">
              <input type="hidden" id="user_id" value="<?php echo $_SESSION['user_id']; ?>">
              <!-- Front ID Upload -->
              <div class="border border-gray-200 rounded-lg p-4">
                <label for="front_id" class="block text-base font-medium text-gray-700 mb-2">Front of ID:</label>
                <div class="flex flex-col items-center">
                  <div id="front_id_preview" class="w-full h-48 bg-gray-100 rounded-lg mb-3 flex items-center justify-center overflow-hidden">
                    <svg class="w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="text-gray-500 text-center mt-2">No image selected</p>
                  </div>
                  <div class="w-full">
                    <label for="front_id" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 cursor-pointer">
                      <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12" />
                      </svg>
                      Upload Front of ID
                    </label>
                    <input type="file" id="front_id" name="front_id" accept="image/*" required class="hidden" onchange="previewImage('front_id', 'front_id_preview')">
                  </div>
                </div>
              </div>

              <!-- Back ID Upload -->
              <div class="border border-gray-200 rounded-lg p-4">
                <label for="back_id" class="block text-base font-medium text-gray-700 mb-2">Back of ID:</label>
                <div class="flex flex-col items-center">
                  <div id="back_id_preview" class="w-full h-48 bg-gray-100 rounded-lg mb-3 flex items-center justify-center overflow-hidden">
                    <svg class="w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="text-gray-500 text-center mt-2">No image selected</p>
                  </div>
                  <div class="w-full">
                    <label for="back_id" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 cursor-pointer">
                      <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12" />
                      </svg>
                      Upload Back of ID
                    </label>
                    <input type="file" id="back_id" name="back_id" accept="image/*" required class="hidden" onchange="previewImage('back_id', 'back_id_preview')">
                  </div>
                </div>
              </div>

              <!-- Selfie Photo Capture -->
              <div class="border border-gray-200 rounded-lg p-4">
                <label class="block text-base font-medium text-gray-700 mb-2">Your Photo:</label>
                <div class="flex flex-col items-center">
                  <!-- Camera preview -->
                  <div id="camera_container" class="w-full h-64 bg-gray-100 rounded-lg mb-3 overflow-hidden relative">
                    <video id="camera_preview" class="w-full h-full object-cover hidden" autoplay playsinline></video>
                    <canvas id="photo_preview" class="w-full h-full object-cover hidden"></canvas>
                    <div id="camera_placeholder" class="absolute inset-0 flex flex-col items-center justify-center">
                      <svg class="w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                      </svg>
                      <p class="text-gray-500 text-center mt-2">Camera not activated</p>
                    </div>
                  </div>

                  <div class="w-full flex flex-wrap gap-2 justify-center">
                    <button type="button" id="start_camera" class="flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                      <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                      </svg>
                      Start Camera
                    </button>
                    <button type="button" id="capture_photo" class="flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50" disabled>
                      <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                      </svg>
                      Take Photo
                    </button>
                    <button type="button" id="retake_photo" class="flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 hidden">
                      <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                      </svg>
                      Retake Photo
                    </button>
                  </div>
                  <input type="hidden" id="selfie_data" name="selfie_data">
                </div>
              </div>

              <!-- Submit Button -->
              <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                Submit Verification
              </button>
            </form>
          </div>

          <p class="text-sm text-gray-500 mb-8">
            Any information captured in this process is used for security and job seeker's capability to work only.
            <a href="#" class="text-blue-600 hover:text-blue-700">Learn more about how we handle your information</a>.
          </p>
        </div>
      </main>
    </div>
  <?php
  }
  ?>

  <div id="mobileSidebar" class="fixed inset-0 bg-gray-800 bg-opacity-75 z-40 hidden">
    <div class="absolute right-0 top-0 p-4">
      <button id="closeSidebar" class="text-white">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>
    </div>
    <div class="h-full w-64 bg-white p-6 overflow-y-auto">
      <?php include './userSideBar.php'; ?>
    </div>
  </div>

  <script>
    // Variables to store camera stream and captured photo
    let stream = null;
    let photoTaken = false;

    // Function to preview uploaded images
    function previewImage(inputId, previewId) {
      const input = document.getElementById(inputId);
      const preview = document.getElementById(previewId);

      if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
          // Clear the preview container
          preview.innerHTML = '';

          // Create image element
          const img = document.createElement('img');
          img.src = e.target.result;
          img.classList.add('w-full', 'h-full', 'object-contain');

          // Add image to preview
          preview.appendChild(img);
        }

        reader.readAsDataURL(input.files[0]);
      }
    }

    // Mobile sidebar toggle
    document.addEventListener('DOMContentLoaded', function() {
      const sidebarToggle = document.getElementById('sidebarToggle');
      const mobileSidebar = document.getElementById('mobileSidebar');
      const closeSidebar = document.getElementById('closeSidebar');

      if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
          mobileSidebar.classList.remove('hidden');
        });
      }

      if (closeSidebar) {
        closeSidebar.addEventListener('click', function() {
          mobileSidebar.classList.add('hidden');
        });
      }

      // Camera functionality
      const startCameraBtn = document.getElementById('start_camera');
      const capturePhotoBtn = document.getElementById('capture_photo');
      const retakePhotoBtn = document.getElementById('retake_photo');
      const cameraPreview = document.getElementById('camera_preview');
      const photoPreview = document.getElementById('photo_preview');
      const cameraPlaceholder = document.getElementById('camera_placeholder');
      const selfieDataInput = document.getElementById('selfie_data');
      const form = document.getElementById('idVerificationForm');

      // Start camera
      if (startCameraBtn) {
        startCameraBtn.addEventListener('click', async function() {
          try {
            // Request access to the user's camera
            stream = await navigator.mediaDevices.getUserMedia({
              video: {
                facingMode: 'user',
                width: {
                  ideal: 1280
                },
                height: {
                  ideal: 720
                }
              }
            });

            // Show video stream
            cameraPreview.srcObject = stream;
            cameraPreview.classList.remove('hidden');
            cameraPlaceholder.classList.add('hidden');

            // Enable capture button
            capturePhotoBtn.disabled = false;

          } catch (err) {
            console.error('Error accessing camera:', err);
            alert('Unable to access camera. Please make sure you have granted camera permissions and are using a secure connection (HTTPS).');
          }
        });
      }

      // Capture photo
      if (capturePhotoBtn) {
        capturePhotoBtn.addEventListener('click', function() {
          if (!stream) return;

          const context = photoPreview.getContext('2d');

          // Set canvas dimensions to match video
          photoPreview.width = cameraPreview.videoWidth;
          photoPreview.height = cameraPreview.videoHeight;

          // Draw video frame to canvas
          context.drawImage(cameraPreview, 0, 0, photoPreview.width, photoPreview.height);

          // Convert canvas to data URL and store in hidden input
          const photoData = photoPreview.toDataURL('image/png');
          selfieDataInput.value = photoData;

          // Show photo preview
          photoPreview.classList.remove('hidden');
          cameraPreview.classList.add('hidden');

          // Show retake button and hide capture button
          retakePhotoBtn.classList.remove('hidden');
          capturePhotoBtn.classList.add('hidden');

          // Mark photo as taken
          photoTaken = true;

          // Stop camera stream
          if (stream) {
            stream.getTracks().forEach(track => track.stop());
          }
        });
      }

      // Retake photo
      if (retakePhotoBtn) {
        retakePhotoBtn.addEventListener('click', async function() {
          // Clear canvas
          const context = photoPreview.getContext('2d');
          context.clearRect(0, 0, photoPreview.width, photoPreview.height);

          // Hide photo preview
          photoPreview.classList.add('hidden');

          // Try to restart camera
          try {
            stream = await navigator.mediaDevices.getUserMedia({
              video: {
                facingMode: 'user',
                width: {
                  ideal: 1280
                },
                height: {
                  ideal: 720
                }
              }
            });

            // Show video stream again
            cameraPreview.srcObject = stream;
            cameraPreview.classList.remove('hidden');

            // Show capture button and hide retake button
            capturePhotoBtn.classList.remove('hidden');
            retakePhotoBtn.classList.add('hidden');

            // Reset photo taken flag
            photoTaken = false;

          } catch (err) {
            console.error('Error restarting camera:', err);
            alert('Unable to restart camera. Please refresh the page and try again.');
          }
        });
      }

      // ==================   ETO YUNG SCRIPT PARA MAPASA SA BACKEND YUNG IMAGES PARA MA-VERFIY  ============================
      if (form) {
        document.getElementById('idVerificationForm').addEventListener('submit', async function(e) {
          e.preventDefault();

          let submitButton = document.querySelector('button[type="submit"]');
          submitButton.disabled = true;
          submitButton.classList.add('bg-gray-400', 'cursor-not-allowed');
          submitButton.classList.remove('bg-blue-600', 'hover:bg-blue-800');
          submitButton.innerHTML = '<span class="spinner"></span> Verifying...';

          let userId = document.getElementById('user_id').value;
          let formData = new FormData(this);
          let jsonObject = {};

          jsonObject['user_id'] = userId;
          formData.forEach((value, key) => {
            if (value instanceof File) {
              jsonObject[key] = value;
            } else {
              jsonObject[key] = value;
            }
          });

          async function convertFileToBase64(file) {
            return new Promise((resolve, reject) => {
              let reader = new FileReader();
              reader.readAsDataURL(file);
              reader.onload = () => resolve(reader.result);
              reader.onerror = reject;
            });
          }

          if (jsonObject['front_id']) {
            jsonObject['front_id'] = await convertFileToBase64(jsonObject['front_id']);
          }
          if (jsonObject['back_id']) {
            jsonObject['back_id'] = await convertFileToBase64(jsonObject['back_id']);
          }

          let selfieCanvas = document.getElementById('photo_preview');
          if (photoTaken) {
            selfieCanvas.toBlob(async function(blob) {
              jsonObject['selfie_data'] = await convertFileToBase64(blob);
              await sendVerificationData(jsonObject);
            }, 'image/png');
          } else {
            await sendVerificationData(jsonObject);
          }
        });

        async function sendVerificationData(jsonData) {
          try {
            let response = await fetch('submit_verification.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json'
              },
              body: JSON.stringify(jsonData)
            });

            let result = await response.json();
            if (result.decision) {
              if (result.decision === 'accept') {
                Swal.fire({
                  icon: 'success',
                  title: 'Verification Approved',
                  text: 'Your Identification has been successfully verified!',
                  confirmButtonColor: '#3085d6'
                }).then(() => {
                  window.location.href = 'findJobs.php';
                });
              } else {
                Swal.fire({
                  icon: 'error',
                  title: 'Verification Rejected',
                  text: result.message,
                  confirmButtonColor: '#d33'
                });
              }
            } else if (result.error) {
              let warningText = "";
              if (result.warnings && result.warnings.length > 0) {
                warningText = `<strong style="color: #f27474; font-weight: 500;">Reasons:</strong><br>`;
                warningText += `<div style="font-size: 0.9em; margin-top: 5px;">`;
                warningText += result.warnings.map((warning, index) => `${index + 1}. ${warning}`).join('<br>');
                warningText += `</div>`;
              }
              Swal.fire({
                icon: 'warning',
                title: 'Verification Issues',
                html: warningText,
                confirmButtonColor: '#f27474'
              });
            }
          } catch (error) {
            console.error('Error submitting verification:', error);
            Swal.fire({
              icon: 'error',
              title: 'Submission Error',
              text: 'Please check your internet connection and try again.',
              confirmButtonColor: '#d33'
            });
          } finally {
            let submitButton = document.querySelector('button[type="submit"]');
            submitButton.disabled = false;
            submitButton.classList.remove('bg-gray-400', 'cursor-not-allowed');
            submitButton.classList.add('bg-blue-600', 'hover:bg-blue-800');
            submitButton.innerHTML = 'Submit Verification';
          }
        }
      } // ==================   END OF THE SCRIPT  ============================

    });
  </script>
  <!-- PARA SA LOADER -->
  <style>
    .spinner {
      display: inline-block;
      width: 16px;
      height: 16px;
      border: 3px solid rgba(255, 255, 255, 0.3);
      border-top: 3px solid white;
      border-radius: 50%;
      animation: spin 0.6s linear infinite;
      margin-right: 8px;
      vertical-align: middle;
    }

    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }
  </style>
</body>

</html>