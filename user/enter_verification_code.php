<?php
$email = $_GET['email'] ?? '';  
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
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelector("form").addEventListener("submit", function (event) {
            event.preventDefault(); 

            const email = document.querySelector("input[name='email']").value;
            const verification_code = document.getElementById("verification_code").value;

            if (!verification_code.trim()) {
                Swal.fire({
                    title: "Error",
                    text: "Please enter the verification code.",
                    icon: "error",
                    confirmButtonText: "OK"
                });
                return;
            }

            fetch('verify_code.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `email=${encodeURIComponent(email)}&verification_code=${encodeURIComponent(verification_code)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                  Swal.fire({
                        title: "Success",
                        text: "Your account has been successfully verified! Redirecting to login...",
                        icon: "success",
                        showConfirmButton: false, 
                        timer: 2000, 
                        allowOutsideClick: false,
                        timerProgressBar: true,
                        allowEscapeKey: false
                    }).then(() => {
                        window.location.href = 'userLogin.php';
                    });
                } else {
                    Swal.fire({
                        title: "Error",
                        text: data.message,
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                }
            })
            .catch(error => {
                console.error("Fetch Error:", error);
                Swal.fire({
                    title: "Error",
                    text: "An error occurred. Please try again.",
                    icon: "error",
                    confirmButtonText: "OK"
                });
            });
        });
    });
  </script>
</head>

<body class="min-h-screen bg-gray-100 flex items-center justify-center p-4">
  <div class="bg-white shadow-lg rounded-3xl p-8 w-full max-w-md">
  <div id="success-message" style="display: none;"></div>
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
    document.addEventListener("DOMContentLoaded", () => {
      checkCooldown();
    });
    let cooldownActive = false;
    let remainingTime = 300;
    let countdownInterval;

    function handleResend() {
      if (cooldownActive) return;

      const urlParams = new URLSearchParams(window.location.search);
      const email = urlParams.get('email');

      if (!email) {
        Swal.fire({
          title: "Error",
          text: "Email not found in URL. Please try again.",
          icon: "error",
          confirmButtonText: "OK"
        });
        return;
      }

      const resendButton = document.getElementById('resendButton');
      resendButton.innerHTML = 'Resending...';
      resendButton.disabled = true;

      fetch('resend_verification.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `email=${encodeURIComponent(email)}`
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          Swal.fire({
            title: "Success",
            text: "Verification code has been resent to your email.",
            icon: "success",
            confirmButtonText: "OK"
          });
          startCooldown();
        } else {
          Swal.fire({
            title: "Error",
            text: data.error || "Failed to resend code. Please try again later.",
            icon: "error",
            confirmButtonText: "OK"
          });
        }
      })
      .catch(() => {
        Swal.fire({
          title: "Error",
          text: "Network error. Please try again later.",
          icon: "error",
          confirmButtonText: "OK"
        });
      })
      .finally(() => {
        resendButton.innerHTML = 'Resend Code';
        resendButton.disabled = false;
      });
    }


    function startCooldown() {
      cooldownActive = true;
      const cooldownDuration = 300; // 5 minutes
      const cooldownEndTime = Date.now() + cooldownDuration * 1000; 

      localStorage.setItem("cooldownEndTime", cooldownEndTime);

      runCountdown(cooldownEndTime);
    }

    function checkCooldown() {
      const cooldownEndTime = localStorage.getItem("cooldownEndTime");

      if (cooldownEndTime) {
        const remainingTime = Math.floor((cooldownEndTime - Date.now()) / 1000);
        
        if (remainingTime > 0) {
          runCountdown(cooldownEndTime);
        } else {
          localStorage.removeItem("cooldownEndTime")
        }
      }
    }

    function runCountdown(cooldownEndTime) {
      cooldownActive = true;
      const countdownElement = document.getElementById("countdown");
      const resendButton = document.getElementById("resendButton");

      countdownElement.classList.remove("hidden");
      resendButton.classList.add("text-gray-400");
      resendButton.classList.remove("text-primary", "hover:text-primary-hover");

      function updateCountdown() {
        let remainingTime = Math.floor((cooldownEndTime - Date.now()) / 1000);

        if (remainingTime <= 0) {
          clearInterval(countdownInterval);
          countdownElement.classList.add("hidden");
          resendButton.classList.remove("text-gray-400");
          resendButton.classList.add("text-primary", "hover:text-primary-hover");
          cooldownActive = false;
          localStorage.removeItem("cooldownEndTime");
        } else {
          const minutes = Math.floor(remainingTime / 60);
          const seconds = remainingTime % 60;
          countdownElement.textContent = `Resend available in ${minutes}:${seconds < 10 ? "0" : ""}${seconds}`;
        }
      }

      updateCountdown();
      countdownInterval = setInterval(updateCountdown, 1000);
    }
  </script>
</body>

</html>