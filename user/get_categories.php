<?php
include '../config/db_connection.php'; 
$categories = [];

$categoriesQuery = "SELECT id, name FROM categories";
$categoriesResult = mysqli_query($conn, $categoriesQuery);

while ($category = mysqli_fetch_assoc($categoriesResult)) {
    $categoryId = $category['id'];

    $subCategoriesQuery = "SELECT id, name FROM sub_categories WHERE category_id = $categoryId";
    $subCategoriesResult = mysqli_query($conn, $subCategoriesQuery);

    $subCategories = mysqli_fetch_all($subCategoriesResult, MYSQLI_ASSOC);

    $category['sub_categories'] = $subCategories;
    $categories[] = $category;
}

header('Content-Type: application/json');
echo json_encode($categories);
?>
