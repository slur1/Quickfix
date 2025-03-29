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

<div class="max-w-5xl mx-auto p-6">

    <form id="takeassesmentForm" method="POST" enctype="multipart/form-data" class="w-full max-w-4xl p-6 shadow-lg bg-white rounded-lg border border-gray-300">
        <h1 class="text-3xl font-bold text-blue-900 mb-8">Take Assesment</h1>
        <div class="space-y-6">

            <h1 class="text-1xl font-bold text-blue-900 mb-8">Aircon Cleaning Assessment</h1>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">How often should an aircon filter be cleaned?</label>
                <input type="text" id="answer1" name="answer1" placeholder="Answer"
                    class="w-full p-3 rounded-lg bg-gray-50 border border-gray-300 focus:border-blue-900 focus:ring-1 focus:ring-blue-900 focus:outline-none">
                <span class="text-xs text-red-500 hidden error-message"></span>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">What safety precautions should be taken before cleaning an aircon unit?</label>
                <input type="text" id="answer2" name="answer2" placeholder="Answer"
                    class="w-full p-3 rounded-lg bg-gray-50 border border-gray-300 focus:border-blue-900 focus:ring-1 focus:ring-blue-900 focus:outline-none">
                <span class="text-xs text-red-500 hidden error-message"></span>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">A client complains that their aircon still smells bad after cleaning. What might be the issue?</label>
                <input type="text" id="answer3" name="answer3" placeholder="Answer"
                    class="w-full p-3 rounded-lg bg-gray-50 border border-gray-300 focus:border-blue-900 focus:ring-1 focus:ring-blue-900 focus:outline-none">
                <span class="text-xs text-red-500 hidden error-message"></span>
            </div>

            <div class="flex justify-end space-x-4 pt-4">
                <button type="submit" class="px-6 py-2 bg-blue-800 text-white rounded-full hover:bg-blue-900 transition-colors">
                    Take Assesment
                </button>
            </div>
        </div>
        </main>
    </form>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById("takeassesmentForm");

            form.addEventListener("submit", function(event) {
                event.preventDefault(); // Pigilan ang default form submission

                let isValid = true;

                // Kunin ang lahat ng input fields
                const answerOne = document.getElementById("answer1");
                const answerTwo = document.getElementById("answer2");
                const answerThree = document.getElementById("answer3");

                // Linisin ang error messages
                document.querySelectorAll(".error-message").forEach(error => {
                    error.textContent = "";
                    error.classList.add("hidden");
                });

                document.querySelectorAll("input").forEach(input => {
                    input.classList.remove("border-red-500");
                });

                // Field Validation
                if (answerOne.value.trim() === "") {
                    showError(answerOne, "Field is required.");
                    isValid = false;
                }

                if (answerTwo.value.trim() === "") {
                    showError(answerTwo, "Field is required.");
                    isValid = false;
                }

                if (answerThree.value.trim() === "") {
                    showError(answerThree, "Field is required.");
                    isValid = false;
                }

                // I-submit lang kapag valid
                if (isValid) {
                    form.submit();
                }
            });

            function showError(input, message) {
                const errorMessage = input.nextElementSibling;
                errorMessage.textContent = message;
                errorMessage.classList.remove("hidden");
                input.classList.add("border-red-500");
            }
        });
    </script>

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