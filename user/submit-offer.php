    <?php
    include '../config/db_connection.php';
    session_start();

    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *'); 
    header('Access-Control-Allow-Methods: POST, GET');
    header('Access-Control-Allow-Headers: Content-Type');

ob_clean(); 


    if (!isset($_SESSION['user_id'])) {
        header('Content-Type: application/json');
        echo json_encode(["error" => "User not logged in"]);
        exit;
    }

    $user_id = $_SESSION['user_id']; 

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        
        $job_id = isset($_POST['job_id']) ? intval($_POST['job_id']) : null;
        $provider_id = $user_id; 
        $offer_amount = isset($_POST['offerAmount']) ? floatval($_POST['offerAmount']) : null;
        $message = isset($_POST['message']) ? htmlspecialchars(trim($_POST['message'])) : null;
        $completion_time = isset($_POST['completionTime']) ? htmlspecialchars(trim($_POST['completionTime'])) : null;
        $status = "pending"; 

        if (!$job_id || !$offer_amount || empty($message) || empty($completion_time)) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "All fields are required"]);
            exit;
        }

        $checkJobQuery = "SELECT id FROM jobs WHERE id = ?";
        $checkJobStmt = $conn->prepare($checkJobQuery);
        $checkJobStmt->bind_param("i", $job_id);
        $checkJobStmt->execute();
        $checkJobStmt->store_result();

        if ($checkJobStmt->num_rows === 0) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Job does not exist"]);
            exit;
        }

        $checkOfferQuery = "SELECT id FROM offers WHERE job_id = ? AND provider_id = ?";
        $checkOfferStmt = $conn->prepare($checkOfferQuery);
        $checkOfferStmt->bind_param("ii", $job_id, $provider_id);
        $checkOfferStmt->execute();
        $checkOfferStmt->store_result();

        if ($checkOfferStmt->num_rows > 0) {
        
            $updateOfferQuery = "UPDATE offers SET offer_amount = ?, message = ?, completion_time = ?, status = ? WHERE job_id = ? AND provider_id = ?";
            $updateOfferStmt = $conn->prepare($updateOfferQuery);
            $updateOfferStmt->bind_param("dssiii", $offer_amount, $message, $completion_time, $status, $job_id, $provider_id);

            if ($updateOfferStmt->execute()) {
                header('Content-Type: application/json');
                echo json_encode(["success" => true, "message" => "Offer updated successfully"]);
            } else {
                header('Content-Type: application/json');
                echo json_encode(["error" => "Error updating offer"]);
            }

            $updateOfferStmt->close();
        } else {
           
            $insertOfferQuery = "INSERT INTO offers (job_id, provider_id, offer_amount, message, status, completion_time, creation_time) 
            VALUES (?, ?, ?, ?, ?, ?, NOW())";
            
            $insertOfferStmt = $conn->prepare($insertOfferQuery);
            $insertOfferStmt->bind_param("iidsss", $job_id, $provider_id, $offer_amount, $message, $status, $completion_time);

            if ($insertOfferStmt->execute()) {
                header('Content-Type: application/json');
                echo json_encode(["success" => true, "message" => "Offer submitted successfully"]);
            } else {
                header('Content-Type: application/json');
                echo json_encode(["error" => "Error inserting offer"]);
            }

            $insertOfferStmt->close();
        }

        $checkOfferStmt->close();
        $checkJobStmt->close();
        $conn->close();
    } else {
        header('Content-Type: application/json');
        echo json_encode(["error" => "Invalid request"]);
    }
    ?>
