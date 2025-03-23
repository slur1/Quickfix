<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin-login.php');
    exit();
}

include '../admin/header.php';

include '../config/db_connection.php';

$query = "SELECT id, first_name, last_name, email, id_type, id_file_path, contact_number FROM user";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $users = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $users = [];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.10.5/dist/cdn.min.js" defer></script>
    <style>
        .dropdown-content {
            z-index: 10;
        }
    </style>
</head>

<body>
    <div class="p-6" x-data="tableData()">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="bg-blue-600 text-white">
                <div class="grid grid-cols-8 gap-4 px-6 py-3">
                    <div class="text-left font-semibold min-w-[120px]">#</div>
                    <div class="text-left font-semibold min-w-[120px]">First Name</div>
                    <div class="text-left font-semibold min-w-[120px]">Last Name</div>
                    <div class="text-left font-semibold min-w-[200px]">Email Address</div>
                    <div class="text-left font-semibold min-w-[150px]">Contact Number</div>
                    <div class="text-left font-semibold min-w-[120px]">ID Type</div>
                    <div class="text-left font-semibold min-w-[150px]">ID File</div>
                    <div class="text-left font-semibold min-w-[120px]">Action</div>
                </div>
            </div>
            <div class="divide-y divide-gray-200">
                <template x-for="(row, index) in rows" :key="index">
                    <div class="grid grid-cols-8 gap-4 px-6 py-4 hover:bg-gray-50 relative">
                        <!-- First Name -->

                        <div class="text-left break-words" x-text="row.id"></div>

                        <div class="text-left break-words" x-text="row.first_name"></div>

                        <!-- Last Name -->
                        <div class="text-left break-words" x-text="row.last_name"></div>

                        <!-- Email Address -->
                        <div class="text-left break-words" x-text="row.email"></div>

                        <!-- Contact Number -->
                        <div class="text-left break-words" x-text="row.contact_number"></div>

                        <!-- ID Type -->
                        <div class="text-left break-words" x-text="row.id_type"></div>

                        <!-- ID File Link -->
                        <div class="text-left flex space-x-4 items-center justify-start">
                            <button @click="viewFile(row.id_file_path)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                View
                            </button>
                        </div>

                        <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center p-4">
                            <div class="bg-white p-4 rounded-lg shadow-lg relative max-w-4xl w-full flex flex-col items-center">
                                <!-- Close Button -->
                                <button @click="showModal = false" class="absolute top-2 right-2 text-gray-600 text-2xl font-bold">
                                    &times;
                                </button>

                                <!-- Image Display -->
                                <div class="relative overflow-hidden w-full max-w-2xl"
                                    @mousemove="mouseX = $event.offsetX; mouseY = $event.offsetY"
                                    @mouseenter="zoom = 2"
                                    @mouseleave="zoom = 1">

                                    <img :src="fileUrl"
                                        class="max-w-full max-h-[80vh] object-contain rounded-lg"
                                        :style="'transform-origin: ' + (mouseX + 'px ' + mouseY + 'px') + '; transform: scale(' + zoom + ')'">
                                </div>
                            </div>
                        </div>


                        <div class="text-left flex space-x-4 items-center justify-start">
                            <!-- Edit Icon -->
                            <a href="#" class="text-gray-500 hover:text-gray-700">
                                <img src="../img/edit.svg" class="w-5 h-5" alt="Edit">
                            </a>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <script>
        function tableData() {
            return {
                rows: <?php echo json_encode($users); ?>,
                showModal: false,
                fileUrl: '',
                mouseX: 0,
                mouseY: 0,
                zoom: 1,


                            viewFile(filePath) {
    
    filePath = filePath.replace(/^user-uploads\//, ''); 
    
    
    this.fileUrl = 'https://quickfixph.online/user/user-uploads/' + filePath;
    this.showModal = true;
},

            };
        }
    </script>

</body>

</html>