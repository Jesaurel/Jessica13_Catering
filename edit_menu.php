<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id']; // Gunakan category_id sesuai dengan form

    if (!empty($_FILES['image']['tmp_name'])) {
        $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
        $sql = "UPDATE menu SET name='$name', description='$description', price='$price', category_id='$category_id', image='$image' WHERE id='$id'"; // Perbaiki nama kolom di sini
    } else {
        $sql = "UPDATE menu SET name='$name', description='$description', price='$price', category_id='$category_id' WHERE id='$id'"; // Perbaiki nama kolom di sini
    }

    if (mysqli_query($conn, $sql)) {
        echo "Product updated successfully!";
    } else {
        echo "Error updating product: " . mysqli_error($conn);
    }
}
?>