<?php
include '../config/db_connection.php';

$offersQuery = $pdo->query("SELECT offers.*, jobs.title AS job_title FROM offers JOIN jobs ON offers.job_id = jobs.id WHERE offers.status = 'pending'");
$offers = $offersQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="p-6 bg-gray-100 rounded-lg">
    <h2 class="text-xl font-bold mb-4">Pending Offers</h2>
    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-2 border">Job Title</th>
                <th class="p-2 border">Offer Details</th>
                <th class="p-2 border">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($offers as $offer): ?>
            <tr class="border">
                <td class="p-2 border"><?= htmlspecialchars($offer['job_title']) ?></td>
                <td class="p-2 border"><?= htmlspecialchars($offer['details']) ?></td>
                <td class="p-2 border">
                    <button onclick="acceptOffer(<?= $offer['id'] ?>)" class="px-4 py-2 bg-green-500 text-white rounded">
                        Accept Offer
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
