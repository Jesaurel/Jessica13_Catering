<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM menu WHERE id='$id'";

    if (mysqli_query($conn, $sql)) {
        echo "Product deleted successfully!";
    } else {
        echo "Error deleting product: " . mysqli_error($conn);
    }
}
?>