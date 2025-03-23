<?php
session_start();

include '../config/db_connection.php';

if (!isset($_GET['user_id'])) {
    echo "User not found.";
    exit;
}

$source = $_GET['source'] ?? 'findJobs.php'; 

$profile_user_id = intval($_GET['user_id']);

$stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
$stmt->bind_param("i", $profile_user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    echo "User not found.";
    exit;
}

$profile_picture = $user['profile_picture'] ?? '../uploads/profile_pictures/default-avatar.jpg';
$first_name = htmlspecialchars($user['first_name']);
$last_name = htmlspecialchars($user['last_name']);
$skills = $user['skills'] ? explode(',', $user['skills']) : [];
$about_me = htmlspecialchars($user['about_me'] ?? '');
$general_location = htmlspecialchars($user['general_location'] ?? '');
$portfolio = htmlspecialchars($user['portfolio'] ?? '');
$verification_status = $user['verification_status'] ?? 'unverified';
$contact_number = htmlspecialchars($user['contact_number'] ?? 'Not provided');
$email = htmlspecialchars($user['email'] ?? 'Not provided');

// Fetch completed jobs and reviews
$stmt = $conn->prepare("
    SELECT cj.job_title, cj.review, cj.rating, u.first_name AS employer_first_name, u.last_name AS employer_last_name
    FROM completed_jobs cj
    JOIN user u ON cj.user_id = u.id
    WHERE cj.provider_id = ?");
$stmt->bind_param("i", $profile_user_id);
$stmt->execute();
$result = $stmt->get_result();
$completed_jobs = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
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
        <!-- Back Button -->
        <div class="max-w-5xl mx-auto mt-6">
    <a href="<?= htmlspecialchars($source); ?>"
       class="inline-flex items-center px-4 py-2 bg-white text-blue-600 border border-blue-300 
              rounded-lg shadow-sm hover:bg-blue-800 hover:text-white transition duration-200 ease-in-out mb-2">
        <i class="fas fa-arrow-left mr-2"></i> Back
    </a>
</div>
  <div class="max-w-5xl mx-auto p-8 bg-white shadow-lg rounded-xl p-8 border border-gray-300">

        <!-- Profile Header -->
<div class="flex items-center justify-between pb-6 border-b">
    <div class="flex items-center gap-6">
        <!-- Profile Picture with Dynamic Border Color -->
        <img src="<?= $profile_picture; ?>" alt="Profile Picture" 
             class="w-28 h-28 rounded-full border-4 
             <?php if ($verification_status === 'fully_verified') echo 'border-blue-500'; 
             elseif ($verification_status === 'identity_verified') echo 'border-green-500'; 
             else echo 'border-red-500'; ?> shadow-lg">

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
                        if ($verification_status === 'fully_verified') echo '#3B82F6'; // Blue
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

            <!-- Location -->
            <p class="text-sm text-gray-500 flex items-center gap-1 mt-1">
                <i class="fas fa-map-marker-alt text-blue-500"></i> <?= htmlspecialchars($general_location); ?>
            </p>

            <!-- Skills -->
            <p class="text-gray-600 text-sm mt-1">
                <?= !empty($skills) ? implode(' • ', $skills) : 'No skills listed'; ?>
            </p>
        </div>
    </div>
</div>


        <!-- About Me -->
        <div class="mt-6">
            <h2 class="text-xl font-semibold text-gray-800">About Me</h2>
            <p class="text-gray-600 mt-2"> <?= nl2br(htmlspecialchars($about_me)); ?> </p>
        </div>

        <!-- Skills -->
        <div class="mt-6">
            <h2 class="text-xl font-semibold text-gray-800">Skills</h2>
            <div class="flex flex-wrap gap-2 mt-3">
                <?php if (!empty($skills)): ?>
                    <?php foreach ($skills as $skill): ?>
                        <span class="px-4 py-2 bg-blue-500 text-white text-xs font-semibold rounded-full shadow-sm"> <?= htmlspecialchars($skill); ?> </span>
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
                            <h3 class="font-semibold text-gray-700"> <?= htmlspecialchars($job['job_title']); ?> </h3>
                            <p class="text-gray-500 text-sm">Employer: <span class="font-medium text-gray-700"> <?= htmlspecialchars($job['employer_first_name'] . ' ' . $job['employer_last_name']); ?> </span></p>
                            <p class="text-gray-600 text-sm mt-1"> "<?= nl2br(htmlspecialchars($job['review'] ?? 'No review')); ?>" </p>
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
    </div>
</body>
</html>

