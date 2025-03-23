<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
} else {
  // If accessed via GET, preserve email from query parameter
  $email = $_GET['email'] ?? '';
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

<body class="min-h-screen bg-blue-100 flex items-center justify-center">
  <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
    <h2 class="text-2xl font-bold text-center text-blue-600 mb-6">Verify Your Email</h2>
    <form method="POST">
      <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
      <label for="verification_code" class="block text-sm font-medium text-gray-700 mb-2">
        Enter Verification Code:
      </label>
      <input
        type="text"
        name="verification_code"
        id="verification_code"
        class="w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 mb-4"
        placeholder="Enter code"
        required>
      <button
        type="submit"
        class="w-full bg-blue-500 text-white font-bold py-2 px-4 rounded-md hover:bg-blue-600 transition duration-200">
        Verify
      </button>
    </form>
  </div>
</body>

</html>