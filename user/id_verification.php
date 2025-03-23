<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["id_image"])) {
    $apiKey = "qB3mlq4fgpQyjKpGMW8bGYoJaLMjjTQ5"; // Replace with your actual API key
    $idImage = $_FILES['id_image']['tmp_name'];

    // Read image file content and encode it to base64
    $imageData = file_get_contents($idImage);
    $encodedImage = base64_encode($imageData);

    // API Endpoint
    $url = "https://api.idanalyzer.com/coreapi";

    // Prepare POST data
    $data = [
        "apikey" => $apiKey,
        "file_base64" => $encodedImage,
        "outputimage" => 0, // Set to 1 if you want processed image back
        "ocrscaledown" => 0,
        "detectdocument" => 1
    ];

    // Send request using cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode JSON response
    $result = json_decode($response, true);

    // Handle the response
    if (isset($result['error'])) {
        $message = "Error: " . $result['error'];
    } else {
        $fullName = $result['fullName'] ?? 'N/A';
        $idNumber = $result['documentNumber'] ?? 'N/A';
        $dob = $result['dob'] ?? 'N/A';
        $expiry = $result['expiryDate'] ?? 'N/A';
        $isValid = $result['validity']['isReal'] ?? false;

        if ($isValid) {
            $message = "<strong>ID is valid.</strong><br>
                        Name: $fullName<br>
                        ID Number: $idNumber<br>
                        Date of Birth: $dob<br>
                        Expiry Date: $expiry";
        } else {
            $message = "<strong>ID is not real.</strong>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ID Verification</title>
</head>
<body>
    <h2>ID Verification Form</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="id_image">Upload ID Image:</label>
        <input type="file" name="id_image" accept="image/*" required>
        <button type="submit">Verify ID</button>
    </form>

    <?php if (!empty($message)) { ?>
        <div>
            <h3>Verification Result:</h3>
            <p><?php echo $message; ?></p>
        </div>
    <?php } ?>
</body>
</html>
