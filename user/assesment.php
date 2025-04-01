<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../config/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: userLogin.php");
    exit;
}


$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];

    // Get answers from the form
    $answers = [
        trim($_POST['answer1']),
        trim($_POST['answer2']),
        trim($_POST['answer3'])
    ];

    // Update user to indicate assessment was taken
    $take_assesment = 1;
    $stmt = $conn->prepare("UPDATE user SET take_assesment = ? WHERE id = ?");
    $stmt->bind_param("ii", $take_assesment, $user_id);

    if ($stmt->execute()) {
        // Insert answers into assessment table
        $stmt = $conn->prepare("INSERT INTO assesment (user_id, answers) VALUES (?, ?)");

        foreach ($answers as $answer) {
            $stmt->bind_param("is", $user_id, $answer);
            $stmt->execute();
        }

        // Redirect after successful submission
        echo "<script>window.location.href='findJobs.php';</script>";
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

?>
<div id="requiredModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-md" style="z-index: 999;">
    <div class="bg-white p-6 rounded-2xl shadow-xl w-[90%] max-w-sm relative">
        <div class="flex flex-col items-center text-center">
            <div class="w-12 h-12 flex items-center justify-center bg-red-100 text-red-500 rounded-full mb-4">
                <i class="fas fa-exclamation-circle text-2xl"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-900">Verification Required</h2>
            <p class="mt-2 text-gray-600 text-sm">You must verify your account before browsing a job.</p>
            <a href="userVerifyAccount.php" class="mt-5 w-full py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-all duration-300">
                <button>
                    OK, Verify Now
                </button>
            </a>
        </div>
    </div>
</div>
<script>
    function resetAnimation() {
        const letters = document.querySelectorAll('.letter');
        letters.forEach(letter => letter.classList.add('fadeOut'));
        setTimeout(() => {
            letters.forEach(letter => {
                letter.classList.remove('fadeOut');
                letter.style.opacity = 0;
                void letter.offsetWidth;
                letter.style.animation = 'none';
                setTimeout(() => {
                    letter.style.animation = '';
                }, 10);
            });
        }, 800);
    }

    // Adjust totalFadeInTime due to faster animation
    const totalFadeInTime = 0.15 * 8 + 0.4; // = 1.6s approx
    const displayTime = 1.2; // optional tweak
    const cycleTime = (totalFadeInTime + displayTime + 0.8) * 1000; // ~3.6s
    setInterval(() => resetAnimation(), cycleTime);

    // Show main content after loader
    window.addEventListener('load', () => {
        setTimeout(() => {
            document.getElementById('loader-overlay').style.display = 'none';
            document.getElementById('main-content').style.display = 'block';
        }, 2000); // Show loader for 3 seconds
    });
</script>

</html>