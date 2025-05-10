<?php
session_start();
include 'db.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil informasi user
$stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Ambil alamat user
$stmt = $conn->prepare("SELECT id, recipient_name, phone, address, is_default FROM user_addresses WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$addresses = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Ambil menu yang dipilih
if (!isset($_GET['menu_id']) || !isset($_GET['quantity'])) {
    header("Location: menu_user.php");
    exit();
}

$menu_id = $_GET['menu_id'];
$quantity = $_GET['quantity'];

// Ambil detail menu
$stmt = $conn->prepare("SELECT name, price FROM menu WHERE id = ?");
$stmt->bind_param("i", $menu_id);
$stmt->execute();
$menu = $stmt->get_result()->fetch_assoc();
$stmt->close();

$total_price = $menu['price'] * $quantity;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg p-6 mt-10">
    <h2 class="text-2xl font-bold mb-4">Checkout</h2>

    <div class="border-b pb-4 mb-4">
        <h3 class="text-lg font-semibold">Ringkasan Pesanan</h3>
        <p class="mt-2"><strong>Menu:</strong> <?php echo htmlspecialchars($menu['name']); ?></p>
        <p><strong>Jumlah:</strong> <?php echo $quantity; ?></p>
        <p class="text-xl font-bold mt-2">Total: Rp<?php echo number_format($total_price, 0, ',', '.'); ?></p>
    </div>

    <form action="process_checkout.php" method="POST">
        <input type="hidden" name="menu_id" value="<?php echo $menu_id; ?>">
        <input type="hidden" name="quantity" value="<?php echo $quantity; ?>">
        <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">

        <div class="mb-4">
            <label class="block font-semibold">Opsi Pengiriman</label>
            <select name="delivery_option" class="w-full p-2 border rounded">
                <option value="Delivery">Delivery</option>
                <option value="Pickup">Pickup</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Tanggal Pengiriman</label>
            <input type="date" name="delivery_date" class="w-full p-2 border rounded" required>
        </div>
        <div class="mb-4">
            <label class="block font-semibold">Waktu Pengiriman</label>
            <input type="time" name="delivery_time" class="w-full p-2 border rounded" required>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Alamat (jika pengiriman)</label>
            <select name="address" class="w-full p-2 border rounded">
                <option value="">Pilih alamat tersimpan...</option>
                <?php foreach ($addresses as $address) : ?>
                    <option value="<?php echo htmlspecialchars($address['address']); ?>" <?php echo $address['is_default'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($address['recipient_name']) . " - " . htmlspecialchars($address['address']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <textarea name="manual_address" class="w-full p-2 border rounded mt-2" placeholder="Atau isi alamat secara manual..."></textarea>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Metode Pembayaran</label>
            <select name="payment_method" class="w-full p-2 border rounded">
                <option value="COD">Cash on Delivery (COD)</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Nama</label>
            <input type="text" name="customer_name" value="<?php echo htmlspecialchars($user['username']); ?>" class="w-full p-2 border rounded" readonly>
        </div>
        <div class="mb-4">
            <label class="block font-semibold">Nomor HP</label>
            <input type="text" name="phone" class="w-full p-2 border rounded" required>
        </div>
        <div class="mb-4">
            <label class="block font-semibold">Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="w-full p-2 border rounded" readonly>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Instruksi Khusus</label>
            <textarea name="special_instructions" class="w-full p-2 border rounded"></textarea>
        </div>

        <div class="mb-4">
            <input type="checkbox" name="terms" required>
            <label>Saya setuju dengan syarat dan ketentuan</label>
        </div>

        <button type="submit" class="w-full bg-green-500 text-white py-2 rounded-full font-bold">Pesan Sekarang</button>
    </form>
</div>
</body>
</html>