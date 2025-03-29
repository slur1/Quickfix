<?php
session_start();
include '../config/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo "SESSION user_id is missing!";  // Debugging
    exit;
}


$user_id = $_SESSION['user_id'];

if (!isset($_GET['job_id'])) {
    die("Job ID not provided.");
}

$job_id = $_GET['job_id'];

// Fetch job details
$query = "SELECT * FROM jobs WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $job_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$job = $result->fetch_assoc();
$stmt->close();

if (!$job) {
    die("Job not found or you do not have permission to edit this job.");
}

// Update job logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $job_title = trim($_POST['job_title']);
    $job_date = !empty($_POST['job_date']) ? trim($_POST['job_date']) : NULL;
    $job_time = !empty($_POST['job_time']) ? trim($_POST['job_time']) : NULL;
    $location = trim($_POST['location']);
    $description = trim($_POST['description']);
    $category_id = isset($_POST['category_id']) ? $_POST['category_id'] : null;
    $sub_category_id = isset($_POST['sub_category_id']) ? $_POST['sub_category_id'] : null;
    $budget = $_POST['budget'];

    // Update job in the database
    $stmt = $conn->prepare("UPDATE jobs SET job_title=?, job_date=?, job_time=?, location=?, description=?, category_id=?, sub_category_id=?, budget=? WHERE id=? AND user_id=?");
    $stmt->bind_param("ssssssiiii", $job_title, $job_date, $job_time, $location, $description, $category_id, $sub_category_id, $budget, $job_id, $user_id);

    if ($stmt->execute()) {
        header("Location: myJobs.php?update_success=1");
        exit;
    } else {
        echo "Error updating job: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job</title>
</head>
<body>

<h2>Edit Job</h2>

<form method="POST">
    <label>Job Title:</label>
    <input type="text" name="job_title" value="<?= htmlspecialchars($job['job_title']); ?>" required>

    <label>Job Date:</label>
    <input type="date" name="job_date" value="<?= htmlspecialchars($job['job_date']); ?>">

    <label>Job Time:</label>
    <input type="text" name="job_time" value="<?= htmlspecialchars($job['job_time']); ?>">

    <label>Location:</label>
    <input type="text" name="location" value="<?= htmlspecialchars($job['location']); ?>" required>

    <label>Description:</label>
    <textarea name="description"><?= htmlspecialchars($job['description']); ?></textarea>

    <label>Category:</label>
    <select name="category_id">
        <option value="">Select Category</option>
        <?php
        $category_query = "SELECT id, name FROM categories";
        $categories = $conn->query($category_query);
        while ($cat = $categories->fetch_assoc()) {
            $selected = ($job['category_id'] == $cat['id']) ? 'selected' : '';
            echo "<option value='{$cat['id']}' $selected>{$cat['name']}</option>";
        }
        ?>
    </select>

    <label>Subcategory:</label>
    <select name="sub_category_id">
        <option value="">Select Subcategory</option>
        <?php
        $sub_query = "SELECT id, name FROM sub_categories WHERE category_id = ?";
        $stmt = $conn->prepare($sub_query);
        $stmt->bind_param("i", $job['category_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($sub = $result->fetch_assoc()) {
            $selected = ($job['sub_category_id'] == $sub['id']) ? 'selected' : '';
            echo "<option value='{$sub['id']}' $selected>{$sub['name']}</option>";
        }
        ?>
    </select>

    <label>Budget:</label>
    <input type="text" name="budget" value="<?= htmlspecialchars($job['budget']); ?>">

    <button type="submit">Update Job</button>
</form>

<a href="myJobs.php">Back to My Jobs</a>

</body>
</html>
