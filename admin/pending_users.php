<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin-login.php');
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../admin/header.php';
include '../config/db_connection.php';

$query = "SELECT * FROM pending_user";
$result = $conn->query($query);
$users = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['user_id'])) {
    $action = $_POST['action'];
    $userId = (int) $_POST['user_id'];
    $reason = $_POST['reason'] ?? 'No Reason Provided';

    $userQuery = $conn->prepare("SELECT email, first_name FROM pending_user WHERE id = ?");
    $userQuery->bind_param('i', $userId);
    $userQuery->execute();
    $userResult = $userQuery->get_result();
    $user = $userResult->fetch_assoc();
    $userQuery->close();

    if (!$user) {
        echo json_encode(['status' => 'error', 'error' => 'User not found.']);
        exit;
    }

    $userEmail = $user['email'];
    $firstName = $user['first_name'];

    try {
        if ($action === 'accept') {
            $sql = "INSERT INTO user (first_name, last_name, email, id_type, id_file_path, contact_number, password_hash, created_at, email_verified, verification_code, status) 
                    SELECT first_name, last_name, email, id_type, id_file_path, contact_number, password_hash, created_at, email_verified, verification_code, 'active' 
                    FROM pending_user WHERE id = ?";
            $message = "Dear $firstName,\n\nYour account has been approved! You can now log in using your credentials.\n\nBest regards,\nAdmin Team";
            $subject = "Account Approval Notification";
        } elseif ($action === 'reject') {
            $sql = "INSERT INTO rejected_user (first_name, last_name, email, id_type, id_file_path, contact_number, password_hash, created_at, rejection_reason) 
                    SELECT first_name, last_name, email, id_type, id_file_path, contact_number, password_hash, created_at, ? 
                    FROM pending_user WHERE id = ?";
            $message = "Dear $firstName,\n\nUnfortunately, your registration has been rejected. Reason: $reason.\n\nBest regards,\nAdmin Team";
            $subject = "Account Rejection Notification";
        } else {
            throw new Exception("Invalid action: " . $action);
        }

        $stmt = $conn->prepare($sql);
        if ($action === 'reject') {
            $stmt->bind_param('si', $reason, $userId);
        } else {
            $stmt->bind_param('i', $userId);
        }
        $stmt->execute();
        
        if ($stmt->affected_rows === 0) {
            echo json_encode(["status" => "error", "error" => "Database insertion failed."]);
            exit;
        }
        
        $stmt->close();

        $sql = "DELETE FROM pending_user WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $stmt->close();

        $mailSent = mail($userEmail, $subject, $message, "From: monicalburo12@gmail.com");
        if (!$mailSent) {
            echo json_encode(["status" => "error", "error" => "Failed to send email."]);
            exit;
        }

        echo json_encode(["status" => "success"]);
        exit;
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'error' => $e->getMessage()]);
        exit;
    }
}

function getImageUrl($path) {
    if (empty($path)) {
        return 'https://quickfixph.online/default-avatar.png'; 
    }
    if (filter_var($path, FILTER_VALIDATE_URL)) {
        return $path;
    }
    $baseUrl = 'https://quickfixph.online/user-uploads/'; 
    return $baseUrl . ltrim($path, '/');
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Users</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.10.5/dist/cdn.min.js" defer></script>
    <style>
        .dropdown-content {
            z-index: 10;
        }
    </style>
</head>
<body>
    <div class="p-6 overflow-x-auto" x-data="tableData()">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="bg-blue-600 text-white flex justify-between items-center p-3">
                <h2 class="text-lg font-semibold">Pending Users</h2>
                <button @click="refreshData()" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Refresh
                </button>
            </div>
            <div class="bg-blue-600 text-white">
                <div class="grid grid-cols-8 min-w-[800px] px-6 py-3">
                    <div class="text-left font-semibold">#</div>
                    <div class="text-left font-semibold">First Name</div>
                    <div class="text-left font-semibold">Last Name</div>
                    <div class="text-left font-semibold">Email</div>
                    <div class="text-left font-semibold">Contact #</div>
                    <div class="text-left font-semibold">ID Type</div>
                    <div class="text-left font-semibold">ID File</div>
                    <div class="text-left font-semibold">Action</div>
                </div>
            </div>
            <div class="divide-y divide-gray-200">
                <template x-if="rows.length === 0">
                    <div class="text-center py-6 text-gray-500">No pending user requests at the moment.</div>
                </template>

                <template x-for="(row, index) in rows" :key="index">
                    <div class="grid grid-cols-8 gap-4 px-6 py-4 hover:bg-gray-50 min-w-[800px]">
                        <div class="break-words" x-text="row.id"></div>
                        <div class="break-words" x-text="row.first_name"></div>
                        <div class="break-words" x-text="row.last_name"></div>
                        <div class="break-words" x-text="row.email"></div>
                        <div class="break-words" x-text="row.contact_number"></div>
                        <div class="break-words" x-text="row.id_type"></div>
                        <div>
                            <button @click="viewFile(row.id_file_path)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                View
                            </button>
                        </div>
                        <div class="flex flex-col space-y-2">
                            <button @click="confirmAccept(row.id)" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded">
                                Accept
                            </button>
                            <button @click="confirmReject(row.id)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded">
                                Reject
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- View File Modal -->
        <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center p-4">
            <div class="bg-white p-4 rounded-lg shadow-lg relative max-w-2xl w-full">
                <button @click="showModal = false" class="absolute top-2 right-2 text-gray-600 text-2xl font-bold">&times;</button>
                <div class="flex justify-center">
                    <img :src="fileUrl" class="max-w-full max-h-[80vh] object-contain rounded-lg">
                </div>
            </div>
        </div>

        <!-- Reject Modal -->
        <div x-show="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center p-4">
            <div class="bg-white p-4 rounded-lg shadow-lg relative max-w-md w-full">
                <button @click="rejectModal = false" class="absolute top-2 right-2 text-gray-600 text-2xl font-bold">&times;</button>
                <h2 class="text-lg font-semibold mb-4">Reject User</h2>
                <label class="block text-sm font-medium text-gray-700">Select Rejection Reason:</label>
                <select x-model="selectedReason" class="w-full border p-2 rounded mt-2">
                    <option value="">-- Select a reason --</option>
                    <template x-for="reason in reasons">
                        <option :value="reason" x-text="reason"></option>
                    </template>
                </select>
                <div x-show="selectedReason === 'Other (please specify)'" class="mt-2">
                    <label class="block text-sm font-medium text-gray-700">Specify Reason:</label>
                    <input type="text" x-model="customReason" class="w-full border p-2 rounded mt-2">
                </div>
                <button @click="rejectUser()" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mt-4">
                    Confirm Reject
                </button>
            </div>
        </div>
    </div>
</body>



   <script>
function tableData() {
    return {
        rows: <?php echo json_encode($users); ?>,
        showModal: false,
        rejectModal: false, // Ensure rejectModal is initialized
        rejectUserId: null,
        selectedReason: '',
        customReason: '',
        reasons: ["Invalid ID", "Fake Information", "Other (please specify)"],
        fileUrl: '',

        viewFile(filePath) {
            filePath = filePath.replace(/^user-uploads\//, '');
            this.fileUrl = 'https://quickfixph.online/user/user-uploads/' + filePath;
            this.showModal = true;
        },

confirmAccept(userId) {
    Swal.fire({
        title: "Are you sure?",
        text: "You are about to approve this user.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes!",
        cancelButtonText: "Cancel"
    }).then((result) => {
        if (result.isConfirmed) {
            this.acceptUser(userId);
        }
    });
},

acceptUser(userId) {
    fetch(window.location.href, { 
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ action: 'accept', user_id: userId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            Swal.fire({
                title: "Approved!",
                text: "User has been approved.",
                icon: "success",
                confirmButtonColor: "#28a745"
            });
            this.rows = this.rows.filter(row => row.id !== userId);
        } else {
            Swal.fire({
                title: "Error!",
                text: data.error || "Unknown error occurred.",
                icon: "error",
                confirmButtonColor: "#d33"
            });
        }
    })
    .catch(error => {
        console.error("Error:", error);
        Swal.fire({
            title: "Approved!",
            text: "User has been approved.",
            icon: "success",
            confirmButtonColor: "#28a745"
        });
    });
},

confirmReject(userId) {
    this.rejectUserId = userId;
    this.rejectModal = true;
},

rejectUser() {
    if (!this.selectedReason) {
        Swal.fire({
            title: "Error!",
            text: "Please select a rejection reason.",
            icon: "warning",
            confirmButtonColor: "#d33"
        });
        return;
    }

    let reason = this.selectedReason === "Other (please specify)" ? this.customReason : this.selectedReason;
    
    // Store the user ID locally to avoid reactivity issues
    const userIdToRemove = this.rejectUserId;
    
    fetch(window.location.href, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ 
            action: 'reject', 
            user_id: userIdToRemove, 
            reason: reason 
        }).toString()
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            Swal.fire({
                title: "Rejected!",
                text: "User has been rejected.",
                icon: "success",
                confirmButtonColor: "#d33"
            });

            // Ensure `userIdToRemove` and `row.id` are compared as the same type
            this.rows = this.rows.filter(row => String(row.id) !== String(userIdToRemove));

            // Close modal and reset values
            this.rejectModal = false;
            this.rejectUserId = null;
            this.selectedReason = '';
            this.customReason = '';

            // Ensure Alpine.js updates the DOM
            Alpine.nextTick(() => {});
        } else {
            Swal.fire({
                title: "Error!",
                text: data.error || "Unknown error",
                icon: "error",
                confirmButtonColor: "#28a745"
            });
        }
    })
    .catch(error => {
        console.error("Error:", error);
        
        Swal.fire({
            title: "Rejected!",
            text: "User has been rejected.",
            icon: "success",
            confirmButtonColor: "#28a745"
        });

        // Still remove user from the table in case of an error
        this.rows = this.rows.filter(row => String(row.id) !== String(userIdToRemove));

        // Close modal and reset values
        this.rejectModal = false;
        this.rejectUserId = null;
        this.selectedReason = '';
        this.customReason = '';

        // Ensure Alpine.js updates the DOM
        Alpine.nextTick(() => {});
    });
}
  };
} 

</script>

</html>
