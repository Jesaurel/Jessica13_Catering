<?php
session_start();
include 'db.php';

// Pastikan hanya admin yang bisa mengakses halaman ini
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Ambil ID order dari URL
$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$order = $conn->query("SELECT orders.*, users.username, menu.name AS menu_name, menu.image AS menu_image 
                        FROM orders 
                        JOIN users ON orders.user_id = users.id 
                        JOIN menu ON orders.menu_id = menu.id 
                        WHERE orders.id = $order_id")->fetch_assoc();

if (!$order) {
    echo "<p class='text-red-500'>Order tidak ditemukan.</p>";
    exit();
}

// Update status order
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_status = $_POST['status'];
    $conn->query("UPDATE orders SET status = '$new_status' WHERE id = $order_id");
    header("Location: order_detail.php?id=$order_id&success=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Order - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-4 text-gray-800 flex items-center">
            <i class="fas fa-receipt mr-2"></i> Detail Order #<?php echo $order['id']; ?>
        </h2>

        <?php if (isset($_GET['success'])): ?>
            <p class="text-green-500 bg-green-100 p-2 rounded mb-4">Status order berhasil diperbarui!</p>
        <?php endif; ?>

        <!-- Informasi Menu -->
        <div class="flex items-center bg-gray-100 p-4 rounded-lg mb-4">
            <img src="data:image/jpeg;base64,<?php echo base64_encode($order['menu_image']); ?>" class="w-24 h-24 object-cover rounded-lg shadow-md">
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-700"><?php echo htmlspecialchars($order['menu_name']); ?></h3>
                <p class="text-gray-500"><i class="fas fa-hashtag"></i> Jumlah: <b><?php echo $order['quantity']; ?></b></p>
                <p class="text-gray-500"><i class="fas fa-money-bill-wave"></i> Total Harga: <b>Rp<?php echo number_format($order['total_price'], 0, ',', '.'); ?></b></p>
            </div>
        </div>

        <!-- Informasi Pelanggan -->
        <div class="bg-blue-100 p-4 rounded-lg mb-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-2"><i class="fas fa-user"></i> Informasi Pelanggan</h3>
            <p><i class="fas fa-user-circle"></i> <b><?php echo htmlspecialchars($order['username']); ?></b></p>
            <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($order['address'] ?: '-'); ?></p>
            <p><i class="fas fa-calendar-alt"></i> <?php echo $order['delivery_date']; ?> | <i class="fas fa-clock"></i> <?php echo $order['delivery_time']; ?></p>
        </div>

        <!-- Update Status -->
        <div class="bg-gray-200 p-4 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-800 mb-2"><i class="fas fa-edit"></i> Ubah Status Order</h3>
            <form action="" method="POST">
                <div class="flex items-center space-x-3">
                    <select name="status" class="p-2 border rounded w-40">
                        <option value="Pending" <?php if ($order['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                        <option value="Diproses" <?php if ($order['status'] == 'Diproses') echo 'selected'; ?>>Diproses</option>
                        <option value="Selesai" <?php if ($order['status'] == 'Selesai') echo 'selected'; ?>>Selesai</option>
                    </select>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- Tombol Kembali -->
        <div class="mt-4">
            <a href="order_manage.php" class="text-blue-600 hover:underline"><i class="fas fa-arrow-left"></i> Kembali ke Daftar Order</a>
        </div>
    </div>
</body>
</html>