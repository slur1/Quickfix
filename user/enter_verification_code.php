<?php
// Initialize session if not already started
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Handle verification code submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['resend'])) {
  include '../config/db_connection.php'; // Adjust the path to your DB connection

  if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
  }

  // Retrieve email and verification code from POST request
  $email = trim($_POST['email'] ?? '');
  $verification_code = trim($_POST['verification_code'] ?? '');

  // Validate inputs
  if (empty($email) || empty($verification_code)) {
    header("Location: enter_verification_code.php?error=All fields are required&email=" . urlencode($email));
    exit;
  }

  // Check if email and verification code match
  $stmt = $conn->prepare("SELECT email_verified FROM user WHERE email = ? AND verification_code = ?");
  $stmt->bind_param("ss", $email, $verification_code);

  if (!$stmt->execute()) {
    die("Query failed: " . $stmt->error);
  }

  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Check if email is already verified
    if ((int)$row['email_verified'] === 0) {
      // Update email_verified to 1
      $stmt_update = $conn->prepare("UPDATE user SET email_verified = 1 WHERE email = ? AND verification_code = ?");
      $stmt_update->bind_param("ss", $email, $verification_code);

      if ($stmt_update->execute()) {
        // Redirect with success flag
        header("Location: enter_verification_code.php?success=1");
        exit;
      } else {
        // Handle update error
        echo "Error during update: " . $stmt_update->error;
        exit;
      }
    } else {
      // Email already verified
      header("Location: enter_verification_code.php?error=Email already verified&email=" . urlencode($email));
      exit;
    }
  } else {
    // Invalid email or verification code
    header("Location: enter_verification_code.php?error=Invalid verification code&email=" . urlencode($email));
    exit;
  }
}

// Handle resend code request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resend'])) {
  include '../config/db_connection.php';

  if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
  }

  $email = trim($_POST['email'] ?? '');

  if (empty($email)) {
    echo json_encode(['status' => 'error', 'message' => 'Email is required']);
    exit;
  }


  $last_sent_time = $_SESSION['last_code_sent_' . md5($email)] ?? 0;
  $current_time = time();
  $cooldown_period = 300;

  if ($current_time - $last_sent_time < $cooldown_period) {
    $remaining_time = $cooldown_period - ($current_time - $last_sent_time);
    echo json_encode([
      'status' => 'error',
      'message' => 'Please wait before requesting another code',
      'remaining_time' => $remaining_time
    ]);
    exit;
  }


  $stmt = $conn->prepare("SELECT id FROM user WHERE email = ?");
  $stmt->bind_param("s", $email);

  if (!$stmt->execute()) {
    echo json_encode(['status' => 'error', 'message' => 'Database error']);
    exit;
  }

  $result = $stmt->get_result();
  if ($result->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Email not found']);
    exit;
  }


  $new_code = sprintf("%06d", mt_rand(1, 999999));

  $update_stmt = $conn->prepare("UPDATE user SET verification_code = ? WHERE email = ?");
  $update_stmt->bind_param("ss", $new_code, $email);

  if (!$update_stmt->execute()) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update verification code']);
    exit;
  }


  $to = $email;
  $subject = "Your New Verification Code";
  $message = "Your new verification code is: " . $new_code;
  $headers = "From: quickfix388@gmail.com";

  if (mail($to, $subject, $message, $headers)) {

    $_SESSION['last_code_sent_' . md5($email)] = time();

    echo json_encode([
      'status' => 'success',
      'message' => 'Verification code has been sent to your email'
    ]);
  } else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to send email']);
  }

  exit;
}

// If accessed via GET, preserve email from query parameter
$email = $_GET['email'] ?? '';
$error = $_GET['error'] ?? '';
$success = $_GET['success'] ?? '';

// Check if there's an active cooldown for this email
$last_sent_time = $_SESSION['last_code_sent_' . md5($email)] ?? 0;
$current_time = time();
$cooldown_period = 300; // 5 minutes in seconds
$remaining_time = 0;

if ($current_time - $last_sent_time < $cooldown_period) {
  $remaining_time = $cooldown_period - ($current_time - $last_sent_time);
}
?>


<!DOCTYPE html>
<html>

<head>
  <title>Verify Your Email | QuickFix</title>
  <link rel="icon" type="logo" href="../img/logo1.png">
  <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css" />

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');

    body {
      font-family: 'Montserrat', sans-serif;
    }
  </style>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Show pending verification SweetAlert if success parameter is present
      const urlParams = new URLSearchParams(window.location.search);
      if (urlParams.has('success')) {
        Swal.fire({
          title: 'Account registered',
          text: 'Your account has been successfully verified. You can now log in and start using our services!',
          icon: 'success',
          confirmButtonText: 'OK',
          allowOutsideClick: false,
          allowEscapeKey: false
        }).then(() => {
          // Redirect to user login page
          window.location.href = 'userLogin.php';
        });
      }

      // Show error modal if error parameter is present
      if (urlParams.has('error')) {
        const error = urlParams.get('error');
        Swal.fire({
          title: 'Error',
          text: error,
          icon: 'error',
          confirmButtonText: 'OK'
        });
      }
    });
  </script>
</head>

<body class="min-h-screen bg-gray-100 flex items-center justify-center p-4">
  <div class="bg-white shadow-lg rounded-3xl p-8 w-full max-w-md">
    <h2 class="text-2xl font-bold text-center text-blue-800 mb-4">Enter Verification Code</h2>

    <p class="text-gray-600 text-center mb-6 justify-center">
      For security reasons, we need to verify your identity. A unique code has been sent to your registered email address. Please check your inboxand enter it in the field below to complete the login process. If you do not see the email in your inbox, please check your spam or junk folder.
    </p>

    <form method="POST">
      <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">

      <div class="mb-6">
        <input
          type="text"
          name="verification_code"
          id="verification_code"
          class="w-full p-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
          placeholder="Authentication Code"
          required>
      </div>

      <button
        type="submit"
        class="w-full bg-primary text-white font-medium py-3 px-4 rounded-lg bg-blue-500 hover:bg-blue-800 transition duration-200">
        Verify
      </button>
    </form>

    <div class="mt-6 text-center">
      <button
        id="resendButton"
        class="text-primary hover:text-primary-hover font-medium"
        onclick="handleResend()">
        Resend Code
      </button>
      <div id="countdown" class="text-sm text-gray-500 mt-1 hidden"></div>
    </div>
  </div>

  <script>
    let cooldownActive = false;
    let remainingTime = 300; // 5 minutes in seconds
    let countdownInterval;

    function handleResend() {
      if (cooldownActive) return;

      // Simulate code resend
      alert("Verification code has been resent to your email.");

      // Start cooldown
      cooldownActive = true;
      remainingTime = 300;

      const countdownElement = document.getElementById('countdown');
      countdownElement.classList.remove('hidden');

      const resendButton = document.getElementById('resendButton');
      resendButton.classList.add('text-gray-400');
      resendButton.classList.remove('text-primary', 'hover:text-primary-hover');

      updateCountdownDisplay();

      countdownInterval = setInterval(() => {
        remainingTime--;
        updateCountdownDisplay();

        if (remainingTime <= 0) {
          clearInterval(countdownInterval);
          cooldownActive = false;
          countdownElement.classList.add('hidden');
          resendButton.classList.remove('text-gray-400');
          resendButton.classList.add('text-primary', 'hover:text-primary-hover');
        }
      }, 1000);
    }

    function updateCountdownDisplay() {
      const minutes = Math.floor(remainingTime / 60);
      const seconds = remainingTime % 60;

      document.getElementById('countdown').textContent =
        `Resend available in ${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
    }
  </script>
</body>

</html>