<?php
include '../config/db_connection.php';

session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
  header('Location: admin-login.php');
  exit();
}

include '../admin/header.php';

$sql = "SELECT job_title, job_date, job_time, location, description, budget, images, status FROM jobs";
$result = $conn->query($sql);


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Jobs List</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

  <div class="max-w-7xl mx-auto p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Jobs List</h2>

    <div class="overflow-x-auto bg-white shadow-md rounded-lg p-4">
      <table class="w-full border border-gray-300 bg-white">
        <thead>
          <tr class="bg-blue-600 text-white">
            <th class="p-3 text-left border">Job Title</th>
            <th class="p-3 text-left border">Date</th>
            <th class="p-3 text-left border">Time</th>
            <th class="p-3 text-left border">Location</th>
            <th class="p-3 text-left border">Description</th>
            <th class="p-3 text-left border">Budget</th>
            <th class="p-3 text-left border">Image</th>
            <th class="p-3 text-left border">Status</th>
          </tr>
        </thead>
        <tbody class="text-gray-700">
          <?php while ($row = $result->fetch_assoc()) { ?>
            <tr class="border-b hover:bg-gray-50">
              <td class="p-3"><?= htmlspecialchars($row['job_title']) ?></td>
              <td class="p-3"><?= htmlspecialchars($row['job_date']) ?></td>
              <td class="p-3"><?= htmlspecialchars($row['job_time']) ?></td>
              <td class="p-3"><?= htmlspecialchars($row['location']) ?></td>
              <td class="p-3"><?= htmlspecialchars($row['description']) ?></td>
              <td class="p-3 text-green-600 font-bold">$<?= htmlspecialchars($row['budget']) ?></td>
              <td class="p-3">
                <?php if (!empty($row['images'])) {
                  // Handle multiple images if they are comma-separated
                  $images = explode(',', $row['images']);
                  foreach ($images as $image) {
                    // Ensure correct path to the image
                    $imagePath = "../user/user-uploads/" . basename(trim($image));
                ?>
                    <img src="<?= htmlspecialchars($imagePath) ?>" alt="Job Image" class="w-16 h-16 object-cover rounded-lg">
                <?php }
                } else {
                  echo "No Image";
                } ?>

              <td class="p-3">
                <span class="px-3 py-1 text-white text-xs font-semibold rounded-full 
                    <?= $row['status'] === 'open' ? 'bg-green-500' : ($row['status'] === 'completed' ? 'bg-blue-500' : 'bg-red-500') ?>">
                  <?= htmlspecialchars($row['status']) ?>
                </span>
              </td>
            </tr>
          <?php } ?>
        </tbody>

      </table>
    </div>
  </div>

</body>

</html>

<?php $conn->close(); ?>