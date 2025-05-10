<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $menu_id = $_POST['menu_id'];
    $user_id = $_SESSION['user_id'];
    $rating = $_POST['rating'];
    $review = $_POST['review'];

    $stmt = $conn->prepare("INSERT INTO reviews (menu_id, user_id, rating, review) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $menu_id, $user_id, $rating, $review);

    if ($stmt->execute()) {
        header("Location: detail_menu.php?id=$menu_id");
    } else {
        echo "Gagal menyimpan review.";
    }

    $stmt->close();
}
?>