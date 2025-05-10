<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$menu_id = $_POST['menu_id'];
$quantity = $_POST['quantity'];
$total_price = $_POST['total_price'];
$delivery_option = $_POST['delivery_option'];
$delivery_date = $_POST['delivery_date'];
$delivery_time = $_POST['delivery_time'];
$address = isset($_POST['address']) ? $_POST['address'] : NULL;
$payment_method = $_POST['payment_method'];
$special_instructions = isset($_POST['special_instructions']) ? $_POST['special_instructions'] : NULL;

// Simpan ke database
$stmt = $conn->prepare("INSERT INTO orders (user_id, menu_id, quantity, total_price, delivery_option, delivery_date, delivery_time, address, payment_method, special_instructions, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')");
$stmt->bind_param("iiisssssss", $user_id, $menu_id, $quantity, $total_price, $delivery_option, $delivery_date, $delivery_time, $address, $payment_method, $special_instructions);

if ($stmt->execute()) {
    echo "<script>alert('Pesanan berhasil!'); window.location='menu_user.php';</script>";
} else {
    echo "<script>alert('Gagal memproses pesanan!'); history.back();</script>";
}
$stmt->close();
?>