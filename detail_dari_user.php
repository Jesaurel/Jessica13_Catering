<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Pastikan order_id dikirim dari order.php
if (!isset($_GET['id'])) {
    echo "Pesanan tidak ditemukan.";
    exit();
}

$order_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// Query ambil data order lengkap + menu
$query = "
    SELECT orders.*, menu.name AS menu_name, menu.image AS menu_image, menu.price
    FROM orders 
    JOIN menu ON orders.menu_id = menu.id
    WHERE orders.id = ? AND orders.user_id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Pesanan tidak ditemukan atau bukan milik Anda.";
    exit();
}

$order = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4 py-8">
    <div class="w-full max-w-xl bg-white rounded-xl shadow-lg p-6 animate-fade-in">
        <!-- Header -->
        <div class="flex items-center justify-between pb-4 border-b border-gray-200 mb-4">
            <h1 class="text-xl font-bold text-gray-800">Detail Pesanan</h1>
            <a href="order.php" class="text-sm text-pink-500 hover:underline">Kembali</a>
        </div>

        <!-- Menu -->
        <div class="mb-5">
            <div class="flex items-start space-x-4">
                <img src="data:image/jpeg;base64,<?= base64_encode($order['menu_image']) ?>" 
                     alt="Menu Image"
                     class="w-20 h-20 rounded-lg object-cover border border-gray-300">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($order['menu_name']) ?></h2>
                    <p class="text-sm text-gray-500 mt-1">Tanggal: <?= date('d M Y, H:i', strtotime($order['delivery_date'].' '.$order['delivery_time'])) ?></p>
                </div>
            </div>
        </div>

        <!-- Status dan Harga -->
        <div class="grid grid-cols-2 gap-y-3 text-sm text-gray-700 mb-5">
            <div><span class="font-medium">Status:</span></div>
            <div>
                <span class="<?= 
                    $order['status'] === 'Pending' ? 'text-yellow-500' : 
                    ($order['status'] === 'Diproses' ? 'text-blue-500' : 
                    'text-green-600') ?>">
                    <?= htmlspecialchars($order['status']) ?>
                </span>
            </div>

            <div><span class="font-medium">Jumlah:</span></div>
            <div><?= $order['quantity'] ?> porsi</div>

            <div><span class="font-medium">Total Harga:</span></div>
            <div>Rp <?= number_format($order['total_price'], 0, ',', '.') ?></div>
        </div>

        <!-- Pembayaran & Pengiriman -->
        <div class="mb-5 text-sm text-gray-700">
            <p class="mb-1"><span class="font-medium">Metode Pembayaran:</span> <?= htmlspecialchars($order['payment_method']) ?></p>
            <p class="mb-1"><span class="font-medium">Pengiriman:</span> <?= $order['delivery_option'] === 'Delivery' ? 'Diantar' : 'Ambil Sendiri' ?></p>
            <?php if ($order['delivery_option'] === 'Delivery'): ?>
                <p class="text-gray-600 ml-2 mt-1 text-sm">Alamat: <?= nl2br(htmlspecialchars($order['address'])) ?></p>
            <?php endif; ?>
        </div>

        <!-- Catatan -->
        <div class="text-sm text-gray-700">
            <p class="font-medium mb-1">Catatan Khusus:</p>
            <p class="text-gray-600"><?= $order['special_instructions'] ? htmlspecialchars($order['special_instructions']) : '-' ?></p>
        </div>
    </div>
</body>
</html>