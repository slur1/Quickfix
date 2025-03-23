<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: userLogin.php");
    exit;
}

include '../config/db_connection.php';

$user_id = $_SESSION['user_id'];
$errors = [];

// Fetch existing user details including verification_status
$stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Preserve existing values if not updated
$first_name = $user['first_name'];  
$last_name = $user['last_name'];
$email = $user['email'];
$contact_number = $user['contact_number'];
$id_type = $user['id_type'];
$profile_picture = $user['profile_picture'] ?? '../uploads/profile_pictures/default-avatar.jpg';
$id_file_path = $user['id_file_path'];
$skills = $user['skills'] ? explode(',', $user['skills']) : [];
$about_me = $user['about_me'] ?? '';
$general_location = $user['general_location'] ?? '';
$portfolio = $user['portfolio'] ?? '';
$reviews = json_decode($user['reviews'] ?? '[]', true); // Assume reviews are stored as JSON
$verification_status = $user['verification_status'] ?? 'unverified'; // Fetch verification status

// Fetch completed jobs and reviews
$stmt = $conn->prepare("
    SELECT cj.job_title, cj.review, cj.rating, u.first_name AS employer_first_name, u.last_name AS employer_last_name
    FROM completed_jobs cj
    JOIN user u ON cj.user_id = u.id
    WHERE cj.provider_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$completed_jobs = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Trim and sanitize inputs
    $first_name = !empty($_POST['first_name']) ? htmlspecialchars(trim($_POST['first_name'])) : $first_name;
    $last_name = !empty($_POST['last_name']) ? htmlspecialchars(trim($_POST['last_name'])) : $last_name;
    $email = !empty($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : $email;
    $contact_number = !empty($_POST['contact_number']) ? htmlspecialchars(trim($_POST['contact_number'])) : $contact_number;
    $id_type = !empty($_POST['id_type']) ? htmlspecialchars(trim($_POST['id_type'])) : $id_type;
    $about_me = !empty($_POST['about_me']) ? htmlspecialchars(trim($_POST['about_me'])) : $about_me;
    $general_location = !empty($_POST['general_location']) ? htmlspecialchars(trim($_POST['general_location'])) : $general_location;
    $portfolio = !empty($_POST['portfolio']) ? htmlspecialchars(trim($_POST['portfolio'])) : $portfolio;

    // Process skills as a comma-separated string
    $skills = !empty($_POST['skills']) 
    ? implode(',', array_map('htmlspecialchars', (array) $_POST['skills'])) 
    : '';

    // Check if "Others" is selected and update ID type
    if ($id_type === "others" && !empty($_POST['other_id_input'])) {
        $id_type = htmlspecialchars(trim($_POST['other_id_input']));
    }

    // Function to handle file upload
    function handleFileUpload($file, $allowed_types, $max_size, $upload_dir, &$errors, $existing_file) {
        if (!empty($file['name'])) {
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $file_name = time() . "_" . basename($file["name"]);
            $target_file = $upload_dir . $file_name;
            $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            if (!in_array($file_type, $allowed_types)) {
                $errors[] = "Invalid file type. Allowed types: " . implode(", ", $allowed_types);
                return $existing_file; // Keep old file if new one is invalid
            } elseif ($file["size"] > $max_size) {
                $errors[] = "File size too large. Max " . ($max_size / 1024 / 1024) . "MB allowed.";
                return $existing_file;
            } else {
                if (move_uploaded_file($file["tmp_name"], $target_file)) {
                    return $target_file;
                } else {
                    $errors[] = "Error uploading file.";
                    return $existing_file;
                }
            }
        }
        return $existing_file;
    }

    // Handle Profile Picture Upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] !== 4) { 
        $profile_picture = handleFileUpload($_FILES['profile_picture'], ["jpg", "jpeg", "png"], 2 * 1024 * 1024, "../uploads/profile_pictures/", $errors, $profile_picture);
    }

    // Handle ID Upload
    if (isset($_FILES['upload_id']) && $_FILES['upload_id']['error'] !== 4) { 
        $id_file_path = handleFileUpload($_FILES['upload_id'], ["jpg", "jpeg", "png", "pdf"], 2 * 1024 * 1024, "../uploads/ids/", $errors, $id_file_path);
    }

    // Only update database if no errors
    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE user SET first_name = ?, last_name = ?, email = ?, contact_number = ?, id_type = ?, id_file_path = ?, profile_picture = ?, skills = ?, about_me = ?, general_location = ?, portfolio = ? WHERE id = ?");
        $stmt->bind_param("sssssssssssi", $first_name, $last_name, $email, $contact_number, $id_type, $id_file_path, $profile_picture, $skills, $about_me, $general_location, $portfolio, $user_id);

        if ($stmt->execute()) {
            header("Location: userPublicProfile.php?success=1");
            exit;
        } else {
            $errors[] = "Database error: " . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile</title>
  <link rel="icon" type="logo" href="../img/logo1.png">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap');

    body {
      font-family: 'Montserrat', sans-serif;
    }

    /* Floating Plus Button */
    .floating-btn {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background-color: #1D4ED8;
      color: white;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      cursor: pointer;
      box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    }

    .floating-btn:hover {
      background-color: #1E40AF;
    }

    @keyframes highlight {
    0% { background-color: #ffcccc; }
    100% { background-color: white; }
}

.animate-highlight {
    animation: highlight 1s ease-in-out;
}
  </style>
</head>

<?php
include './userHeader.php';
?>

<body class="bg-gray-50 min-h-screen">
  <div class="max-w-5xl mx-auto p-8 bg-white shadow-lg rounded-xl p-8 border border-gray-300">

<!-- Modal -->
    <div id="formModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-lg p-6 max-w-lg w-full max-h-[80vh] overflow-y-auto relative">
    <span onclick="closeFormModal()" class="absolute top-2 right-3 text-gray-600 cursor-pointer text-xl">&times;</span>
    <h2 class="text-lg font-semibold mb-3 text-center">Update Profile</h2>
    
    <!-- Display error messages -->
    <?php if (!empty($errors)) : ?>
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul>
          <?php foreach ($errors as $error) : ?>
            <li><?= htmlspecialchars($error) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

 <form method="POST" enctype="multipart/form-data" class="space-y-6">
      <!-- Profile Picture Upload -->
      <div class="flex justify-center relative group">
        <label for="profile_picture" class="relative cursor-pointer">
          <img id="profilePicPreview" src="<?= !empty($user['profile_picture']) ? htmlspecialchars($user['profile_picture']) : '../uploads/profile_pictures/default-avatar.jpg' ?>" 
            class="w-32 h-32 object-cover rounded-full border shadow-md transition duration-300" alt="Profile Picture">
          <div class="absolute inset-0 bg-black bg-opacity-50 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M12 5v14m-7-7h14"></path>
            </svg>
          </div>
        </label>
        <input type="file" id="profile_picture" name="profile_picture" accept=".jpg,.jpeg,.png" class="hidden" onchange="previewProfilePic(event)">
      </div>

      <!-- Personal Information -->
      <div class="grid md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-blue-900">First Name *</label>
        <input type="text" id="first_name" name="first_name" placeholder="Enter first name" 
            value="<?= htmlspecialchars($user['first_name'] ?? '') ?>"
            class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
        <div class="error-message text-red-600 text-sm mt-1 hidden"></div>
    </div>
    <div>
        <label class="block text-sm font-medium text-blue-900">Last Name *</label>
        <input type="text" id="last_name" name="last_name" placeholder="Enter last name" 
            value="<?= htmlspecialchars($user['last_name'] ?? '') ?>"
            class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
        <div class="error-message text-red-600 text-sm mt-1 hidden"></div>
    </div>
</div>

<div class="grid md:grid-cols-2 gap-4 mt-4">
    <div>
        <label class="block text-sm font-medium text-blue-900">Contact Number </label>
        <div class="flex items-center">
            <span class="bg-gray-100 border border-gray-300 p-3 rounded-l-md">+63</span>
            <input type="text" id="contact_number" name="contact_number" placeholder="Phone Number" 
                value="<?= htmlspecialchars($user['contact_number'] ?? '') ?>"
                class="flex-1 p-3 border border-gray-300 rounded-r-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>
    </div>
</div>


        <!-- Skills -->
        <div>
            <label class="block text-sm font-medium text-blue-900">Skills</label>
            <textarea name="skills" placeholder="Enter your skills (e.g., Plumbing, Electrician, Housekeeping)" 
                class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"><?= 
                htmlspecialchars(is_array($skills) ? implode(', ', $skills) : $skills) 
            ?></textarea>
        </div>


    <!-- About Me -->
    <div>
        <label class="block text-sm font-medium text-blue-900">About Me</label>
        <textarea name="about_me" placeholder="Tell something about yourself"
            class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"><?= htmlspecialchars($user['about_me'] ?? '') ?></textarea>
    </div>

    <!-- General Location -->
    <div>
        <label class="block text-sm font-medium text-blue-900">General Location</label>
        <input type="text" name="general_location" placeholder="Enter your location (e.g., Quezon City, Manila)"
            value="<?= htmlspecialchars($user['general_location'] ?? '') ?>"
            class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
    </div>


<!-- Footer Buttons -->
<div class="flex flex-col md:flex-row items-center md:justify-end gap-4 w-full">
    <button type="closeFormModal"
        class="w-36 py-3 text-md font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:ring-gray-300 rounded-md flex items-center justify-center transition">
        Cancel
    </button>
    <button type="submit"
        class="w-36 py-3 text-md font-medium text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 rounded-md flex items-center justify-center gap-2 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
        </svg>
        Update
    </button>
</div>


</form>

</div>
        </div>
<!-- Profile Header -->
<div class="flex items-center justify-between pb-6 border-b relative">
    <div class="flex items-center gap-6">
        <!-- Profile Picture with Dynamic Border Color -->
        <img src="<?= $profile_picture; ?>" alt="Profile Picture" 
             class="w-28 h-28 rounded-full border-4 
             <?php 
                if ($verification_status === 'fully_verified') echo 'border-blue-500'; 
                elseif ($verification_status === 'identity_verified') echo 'border-green-500'; 
                else echo 'border-red-500'; 
             ?> shadow-lg">

        <div>
            <!-- User Name -->
            <h1 class="text-3xl font-extrabold text-gray-900">
                <?= htmlspecialchars($first_name . ' ' . $last_name); ?>
            </h1>

            <!-- Verification Status Badge -->
            <a href="../user/userVerifyAccount.php" 
               class="inline-flex items-center text-xs font-medium mt-1 px-2 py-1 rounded-md text-white"
               style="background-color: 
                    <?php 
                        if ($verification_status === 'fully_verified')  echo '#3B82F6'; // Blue
                        elseif ($verification_status === 'identity_verified') echo '#34D399'; // Green
                        else echo '#EF4444'; // Red
                    ?>;">
                <i class="fas 
                    <?php 
                        if ($verification_status === 'fully_verified') echo 'fa-check-circle'; 
                        elseif ($verification_status === 'identity_verified') echo 'fa-user-check';
                        else echo 'fa-exclamation-circle'; 
                    ?> mr-1"></i>
                <?php 
                    if ($verification_status === 'fully_verified') echo 'Fully Verified'; 
                    elseif ($verification_status === 'identity_verified') echo 'Identity Verified';
                    else echo 'Not Verified'; 
                ?>
            </a>

            <!-- Skills -->
            <p class="text-gray-600 text-sm mt-1">
                <?= !empty($skills) ? implode(' • ', $skills) : 'No skills listed'; ?>
            </p>

            <!-- Location -->
            <p class="text-gray-500 text-xs flex items-center gap-1">
                <i class="fas fa-map-marker-alt text-blue-500"></i> <?= htmlspecialchars($general_location); ?>
            </p>
        </div>
    </div>

    <!-- Edit Button at Top Right -->
    <button onclick="openFormModal()" class="px-3 py-1 border border-gray-400 text-gray-600 text-sm rounded-md hover:bg-gray-100 transition">
        Edit Profile
    </button>
</div>




    <!-- About Me -->
    <div class="mt-6">
        <h2 class="text-xl font-semibold text-gray-800">About Me</h2>
        <p class="text-gray-600 mt-2"><?= nl2br(htmlspecialchars($about_me)); ?></p>
    </div>

    <!-- Skills -->
    <div class="mt-6">
        <h2 class="text-xl font-semibold text-gray-800">Skills</h2>
        <div class="flex flex-wrap gap-2 mt-3">
            <?php if (!empty($skills)): ?>
                <?php foreach ($skills as $skill): ?>
                    <span class="px-4 py-2 bg-blue-800 text-white text-xs font-semibold rounded-full shadow-sm"><?= htmlspecialchars($skill); ?></span>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-gray-500 text-sm">No skills listed.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Contact -->
    <div class="mt-6">
        <h2 class="text-xl font-semibold text-gray-800">Contact</h2>
        <p class="text-gray-600 flex items-center gap-2"><i class="fas fa-phone text-blue-500"></i> <?= htmlspecialchars($contact_number); ?></p>
        <p class="text-gray-600 flex items-center gap-2"><i class="fas fa-envelope text-blue-500"></i> <?= htmlspecialchars($email); ?></p>
    </div>

<!-- Completed Jobs & Reviews -->
<div class="mt-6">
    <h2 class="text-xl font-semibold text-gray-800">Completed Jobs & Reviews</h2>
    <?php if (!empty($completed_jobs)): ?>
        <div class="mt-3 space-y-4">
            <?php foreach ($completed_jobs as $job): ?>
                <div class="p-4 bg-gray-100 rounded-lg shadow-sm">
                    <h3 class="font-semibold text-gray-700"><?= htmlspecialchars($job['job_title']); ?></h3>
                    <p class="text-gray-500 text-sm">Employer: <span class="font-medium text-gray-700"><?= htmlspecialchars($job['employer_first_name'] . ' ' . $job['employer_last_name']); ?></span></p>
                    <p class="text-gray-600 text-sm mt-1">"<?= nl2br(htmlspecialchars($job['review'] ?? 'No review')); ?>"</p>
                    <div class="mt-2 text-yellow-500">
                        <?php for ($i = 0; $i < ($job['rating'] ?? 0); $i++): ?>★<?php endfor; ?>
                        <?php for ($i = ($job['rating'] ?? 0); $i < 5; $i++): ?>☆<?php endfor; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-gray-500 text-sm mt-2">No completed jobs yet.</p>
    <?php endif; ?>
</div>




<script>
        function openFormModal() {
        document.getElementById('formModal').classList.remove('hidden');
    }
    function closeFormModal() {
        document.getElementById('formModal').classList.add('hidden');
    }

    document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const openFormModal = urlParams.get("openFormModal");

    if (openFormModal === "true") {
        const formModal = document.getElementById("formModal");
        if (formModal) {
            formModal.classList.remove("hidden");
        }
    }
});

document.addEventListener("DOMContentLoaded", function() {
    const formModal = document.getElementById("formModal");
    const form = formModal.querySelector("form"); // Get the form inside the modal

    form.addEventListener("submit", function(event) {
        event.preventDefault(); // Prevent default form submission

        let isValid = true;
        let firstErrorField = null;

        const firstName = document.getElementById("first_name");
        const lastName = document.getElementById("last_name");

        // Clear previous errors
        document.querySelectorAll(".error-message").forEach(error => {
            error.textContent = "";
            error.classList.add("hidden");
        });
        document.querySelectorAll("input").forEach(input => input.classList.remove("border-red-500", "animate-highlight"));

        // Validation logic
        if (firstName.value.trim() === "") {
            showError(firstName, "First Name is required.");
            if (!firstErrorField) firstErrorField = firstName;
            isValid = false;
        }

        if (lastName.value.trim() === "") {
            showError(lastName, "Last Name is required.");
            if (!firstErrorField) firstErrorField = lastName;
            isValid = false;
        }

        // Scroll to the first error field if any
        if (firstErrorField) {
            firstErrorField.scrollIntoView({ behavior: "smooth", block: "center" });
            firstErrorField.classList.add("animate-highlight");
            setTimeout(() => firstErrorField.classList.remove("animate-highlight"), 2000);
        }

        if (isValid) {
            form.submit();
        }
    });

    function showError(input, message) {
        const errorMessage = input.parentElement.querySelector(".error-message");
        if (errorMessage) {
            errorMessage.textContent = message;
            errorMessage.classList.remove("hidden");
        }
        input.classList.add("border-red-500");
    }
});

</script>


<!-- JavaScript for Modal -->
<script>
  function openModal(filePath) {
    const modal = document.getElementById("idModal");
    const modalContent = document.getElementById("modalContent");

    // Get file extension
    const fileExtension = filePath.split('.').pop().toLowerCase();
    
    if (['jpg', 'jpeg', 'png'].includes(fileExtension)) {
      modalContent.innerHTML = `<img src="${filePath}" class="max-w-full h-auto rounded-md" alt="Uploaded ID">`;
    } else if (fileExtension === 'pdf') {
      modalContent.innerHTML = `<iframe src="${filePath}" class="w-full h-[500px]"></iframe>`;
    } else {
      modalContent.innerHTML = `<p class="text-red-500">File format not supported for preview.</p>`;
    }

    modal.classList.remove("hidden");
  }

  function closeModal() {
    document.getElementById("idModal").classList.add("hidden");
  }
</script>

<!-- JavaScript -->
<script>
    function previewProfilePic(event) {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          document.getElementById('profilePicPreview').src = e.target.result;
        };
        reader.readAsDataURL(file);
      }
    }

  function openModal(filePath) {
    const modal = document.getElementById("idModal");
    const modalContent = document.getElementById("modalContent");

    const fileExtension = filePath.split('.').pop().toLowerCase();
    
    if (['jpg', 'jpeg', 'png'].includes(fileExtension)) {
      modalContent.innerHTML = `<img src="${filePath}" class="max-w-full h-auto rounded-md" alt="Uploaded ID">`;
    } else if (fileExtension === 'pdf') {
      modalContent.innerHTML = `<iframe src="${filePath}" class="w-full h-[500px]"></iframe>`;
    } else {
      modalContent.innerHTML = `<p class="text-red-500">File format not supported for preview.</p>`;
    }

    modal.classList.remove("hidden");
  }

  function closeModal() {
    document.getElementById("idModal").classList.add("hidden");
  }
</script>
</html>	