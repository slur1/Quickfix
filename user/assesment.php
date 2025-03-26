<?php
session_start();
include '../config/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: userLogin.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];

    // Get selected subcategories
    $selected_subcategories = isset($_POST['subcategory']) ? $_POST['subcategory'] : [];

    if (empty($selected_subcategories)) {
        echo "No subcategory selected!";
        exit;
    }

    // Update user status to indicate assessment was taken
    $take_assessment = 1;
    $stmt = $conn->prepare("UPDATE user SET take_assesment = ? WHERE id = ?");
    $stmt->bind_param("ii", $take_assessment, $user_id);
    $stmt->execute();
    $stmt->close();

    // Insert answers into user_assessment_answers table
    $stmt = $conn->prepare("INSERT INTO user_assessment_answers (user_id, question_id, answer) VALUES (?, ?, ?)");

    foreach ($selected_subcategories as $subcategory) {
        for ($i = 1; $i <= 3; $i++) {
            if (!empty($_POST["answer{$i}_{$subcategory}"])) {
                $question_id = $i; // Replace this with actual question ID logic
                $answer = trim($_POST["answer{$i}_{$subcategory}"]);
                $stmt->bind_param("iis", $user_id, $question_id, $answer);
                $stmt->execute();
            }
        }
    }

    $stmt->close();
    $conn->close();

    // Redirect after submission
    echo "<script>window.location.href='findJobs.php';</script>";
    exit;
}
?>


<div class="max-w-5xl mx-auto p-6">

    <form id="takeassesmentForm" method="POST" enctype="multipart/form-data"
        class="w-full max-w-4xl p-6 shadow-lg bg-white rounded-lg border border-gray-300">

        <h1 class="text-3xl font-bold text-blue-900 mb-8">Take Assessment</h1>

        <!-- Category Selection -->
        <h2 class="text-xl font-bold text-blue-900 mb-4">Choose Category</h2>
        <div class="flex flex-wrap gap-4">
            <label class="flex items-center space-x-2">
                <input type="checkbox" name="category" value="cleaning" class="category-checkbox">
                <span>Cleaning</span>
            </label>
            <label class="flex items-center space-x-2">
                <input type="checkbox" name="category" value="repair" class="category-checkbox">
                <span>Repair</span>
            </label>
        </div>

        <!-- Cleaning Subcategories -->
        <div id="cleaningSubcategories" class="hidden mt-4">
            <h3 class="text-lg font-bold text-blue-900">Cleaning Subcategory</h3>
            <div class="grid grid-cols-2 gap-4">
                <label><input type="checkbox" class="subcategory-checkbox" value="laundry"> Laundry</label>
                <label><input type="checkbox" class="subcategory-checkbox" value="upholstery"> Upholstery Cleaning</label>
                <label><input type="checkbox" class="subcategory-checkbox" value="deep_cleaning"> Deep Cleaning</label>
                <label><input type="checkbox" class="subcategory-checkbox" value="regular_cleaning"> Regular Cleaning</label>
                <label><input type="checkbox" class="subcategory-checkbox" value="carpet_cleaning"> Carpet Cleaning</label>
                <label><input type="checkbox" class="subcategory-checkbox" value="aircon_cleaning"> Aircon Cleaning</label>
            </div>
        </div>

        <!-- Repair Subcategories -->
        <div id="repairSubcategories" class="hidden mt-4">
            <h3 class="text-lg font-bold text-blue-900">Repair Subcategory</h3>
            <div class="grid grid-cols-2 gap-4">
                <label><input type="checkbox" class="subcategory-checkbox" value="electrical_repair"> Electrical Repair</label>
                <label><input type="checkbox" class="subcategory-checkbox" value="lighting_repair"> Lighting Repair</label>
                <label><input type="checkbox" class="subcategory-checkbox" value="wiring_repair"> Wiring Repair</label>
                <label><input type="checkbox" class="subcategory-checkbox" value="appliance_repair"> Appliance Repair</label>
                <label><input type="checkbox" class="subcategory-checkbox" value="furniture_repair"> Furniture Repair</label>
            </div>
        </div>

        <!-- Questions Container -->
        <div id="questionsContainer" class="mt-6 space-y-6"></div>

        <!-- Submit Button -->
        <div class="flex justify-end space-x-4 pt-4">
            <button type="submit" id="submitButton" class="px-6 py-2 bg-blue-800 text-white rounded-full hover:bg-blue-900 transition-colors hidden">
                Submit Assessment
            </button>
        </div>

    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const categoryCheckboxes = document.querySelectorAll(".category-checkbox");
        const subcategoryCheckboxes = document.querySelectorAll(".subcategory-checkbox");
        const cleaningSubcategories = document.getElementById("cleaningSubcategories");
        const repairSubcategories = document.getElementById("repairSubcategories");
        const questionsContainer = document.getElementById("questionsContainer");
        const submitButton = document.getElementById("submitButton");

        const questions = {
            aircon_cleaning: [
                "How often should an aircon filter be cleaned?",
                "What safety precautions should be taken before cleaning an aircon unit?",
                "A client complains that their aircon still smells bad after cleaning. What might be the issue?"
            ],
            upholstery: [
                "What are the best methods for cleaning upholstery?",
                "Which cleaning agents should be avoided on fabric sofas?"
            ],
            electrical_repair: [
                "What are common causes of electrical failures?",
                "How do you safely replace a damaged electrical socket?"
            ],
            appliance_repair: [
                "How do you diagnose a malfunctioning washing machine?",
                "What safety measures should be taken when repairing home appliances?"
            ]
        };

        // Show/Hide Subcategories Based on Category Selection
        categoryCheckboxes.forEach(checkbox => {
            checkbox.addEventListener("change", function() {
                cleaningSubcategories.classList.toggle("hidden", !document.querySelector("input[value='cleaning']").checked);
                repairSubcategories.classList.toggle("hidden", !document.querySelector("input[value='repair']").checked);
                updateQuestions();
            });
        });

        // Add Event Listeners for Subcategory Selection
        subcategoryCheckboxes.forEach(checkbox => {
            checkbox.addEventListener("change", updateQuestions);
        });

        function updateQuestions() {
            questionsContainer.innerHTML = ""; // Clear previous questions
            let hasQuestions = false;

            document.querySelectorAll(".subcategory-checkbox:checked").forEach(checkbox => {
                if (questions[checkbox.value]) {
                    hasQuestions = true;
                    questions[checkbox.value].forEach(question => {
                        const questionDiv = document.createElement("div");
                        questionDiv.innerHTML = `
                        <label class="block text-sm font-medium text-gray-700 mb-2">${question}</label>
                        <input type="text" name="answers[]" class="w-full p-3 rounded-lg bg-gray-50 border border-gray-300 focus:border-blue-900 focus:ring-1 focus:ring-blue-900 focus:outline-none">
                        <span class="text-xs text-red-500 hidden error-message"></span>
                    `;
                        questionsContainer.appendChild(questionDiv);
                    });
                }
            });

            submitButton.classList.toggle("hidden", !hasQuestions);
        }

        // Form Validation Before Submission
        document.getElementById("takeassesmentForm").addEventListener("submit", function(event) {
            event.preventDefault();
            let isValid = true;

            document.querySelectorAll(".error-message").forEach(error => {
                error.textContent = "";
                error.classList.add("hidden");
            });

            document.querySelectorAll("input[name='answers[]']").forEach(input => {
                if (input.value.trim() === "") {
                    showError(input, "This field is required.");
                    isValid = false;
                }
            });

            if (isValid) {
                this.submit();
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