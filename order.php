<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$status_filter = isset($_GET['status']) ? trim($_GET['status']) : '';

$query = "SELECT orders.*, menu.image AS menu_image, menu.name AS menu_name, menu.price
          FROM orders
          JOIN menu ON orders.menu_id = menu.id
          WHERE orders.user_id = ? ";

if ($status_filter) {
    $query .= "AND LOWER(orders.status) = LOWER(?) "; // Menggunakan LOWER() agar tidak case-sensitive
}

$query .= "ORDER BY orders.created_at DESC";
$stmt = $conn->prepare($query);

if ($stmt === false) {
    die("Error preparing query: " . $conn->error); // Tambahkan penanganan error prepare
}

if ($status_filter) {
    $stmt->bind_param("is", $user_id, $status_filter);
} else {
    $stmt->bind_param("i", $user_id);
}

$stmt->execute();

if ($stmt->errno) {
    die("Error executing query: " . $stmt->error); // Tambahkan penanganan error execute
}

$result = $stmt->get_result();

function time_elapsed_string($datetime)
{
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    if ($diff->y > 0) return $diff->y . ' thn lalu';
    if ($diff->m > 0) return $diff->m . ' bln lalu';
    if ($diff->d > 7) return floor($diff->d / 7) . ' mgg lalu';
    if ($diff->d > 0) return $diff->d . ' hr lalu';
    if ($diff->h > 0) return $diff->h . ' jam lalu';
    if ($diff->i > 0) return $diff->i . ' mnt lalu';
    return 'baru saja';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Riwayat Pesanan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <meta content="" name="keywords">
    <meta content="" name="description">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Playball&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/owl.carousel.min.css" rel="stylesheet">

    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="css/style.css" rel="stylesheet">
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>
        Profile Page
    </title>
    <script src="https://cdn.tailwindcss.com">
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&amp;display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">
    <div class="max-w-md md:max-w-2xl lg:max-w-4xl mx-auto bg-white p-4 rounded-xl shadow-lg mt-6">

        <div class="flex items-center justify-between border-b border-gray-200 pb-4 mb-4">
            <h1 class="text-xl font-bold text-indigo-700">📦 Riwayat Pesanan</h1>

            <form method="GET">
                <select name="status" onchange="this.form.submit()" class="border border-gray-300 text-gray-700 p-2 rounded-lg focus:ring-2 focus:ring-indigo-300">
                    <option value="">Semua</option>
                    <option value="pending" <?= $status_filter == 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="diproses" <?= $status_filter == 'diproses' ? 'selected' : '' ?>>Diproses</option>
                    <option value="selesai" <?= $status_filter == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                </select>
            </form>
        </div>

        <div class="space-y-4">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="bg-white p-4 border border-gray-200 rounded-xl shadow hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                        <div class="flex items-center mb-2">
                            <img src="data:image/jpeg;base64,<?= base64_encode($row['menu_image']) ?>"
                                 alt="Menu Image" class="w-14 h-14 rounded-full object-cover mr-4 border border-gray-300" />
                            <div class="flex-1">
                                <h3 class="font-semibold text-indigo-700"><?= htmlspecialchars($row['menu_name']) ?></h3>
                                <p class="text-sm text-gray-500"><?= time_elapsed_string($row['created_at']) ?></p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-700">Rp <?= number_format($row['price'], 0, ',', '.') ?></p>
                            </div>
                        </div>

                        <p class="text-sm text-gray-600 mb-3">🧮 Jumlah: <?= $row['quantity'] ?></p>

                        <?php if (trim(strtolower($row['status'])) == 'selesai'): ?>
                            <div class="flex flex-col sm:flex-row sm:justify-center sm:gap-3 mt-4">
                                <a href="javascript:void(0);"
                                   onclick="showPesanLagiModal('detail_menu.php?id=<?= $row['menu_id'] ?>')"
                                   class="bg-yellow-100 text-yellow-800 py-2 px-4 rounded-xl text-center text-sm font-semibold hover:bg-yellow-200 shadow hover:scale-105 transform transition-all duration-300 w-full sm:w-auto mb-2 sm:mb-0">
                                    🔁 Pesan Lagi
                                </a>

                                <a href="detail_dari_user.php?id=<?= $row['id'] ?>"
                                   class="bg-indigo-500 text-white py-2 px-4 rounded-xl text-center text-sm font-semibold hover:bg-indigo-600 shadow hover:scale-105 transform transition-all duration-300 w-full sm:w-auto">
                                    📄 Lihat Detail
                                </a>
                            </div>
                        <?php else: ?>
                            <a href="detail_dari_user.php?id=<?= $row['id'] ?>"
                               class="block w-full bg-indigo-500 text-white py-2 px-4 rounded-xl text-center text-sm font-semibold hover:bg-indigo-600 shadow hover:scale-105 transform transition-all duration-300">
                                📄 Lihat Detail
                            </a>
                        <?php endif; ?>

                        <p class="text-gray-600 text-sm mt-3">📌 Status:
                            <span class="font-semibold
                                  <?= $row['status'] === 'pending' ? 'text-yellow-600' : ($row['status'] === 'diproses' ? 'text-blue-600' : 'text-green-600') ?>">
                                <?= ucfirst($row['status']) ?>
                            </span>
                        </p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-gray-500 text-center">Tidak ada riwayat pesanan untuk saat ini.</p>
            <?php endif; ?>
        </div>
    </div>

    <div id="pesanLagiModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6 animate-fade-in">
            <div class="text-center">
                <div class="text-5xl mb-3">🍱🔁</div>
                <h5 class="text-xl font-semibold text-indigo-700 mb-2">Pesan Lagi?</h5>
                <p class="text-gray-600 text-sm mb-4">Kamu akan diarahkan ke menu yang sama seperti sebelumnya. Isi ulang detailnya jika ingin memesan ulang.</p>
                <div class="flex justify-center gap-4">
                    <button onclick="hidePesanLagiModal()" class="px-4 py-2 bg-gray-300 rounded-xl hover:bg-gray-400 transition">❌ Batal</button>
                    <a id="confirmPesanLagiBtn" href="#" class="px-4 py-2 bg-yellow-500 text-white rounded-xl hover:bg-yellow-600 shadow hover:scale-105 transition transform duration-300">🛒 Beli Lagi</a>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }
    </style>

    <script>
        function showPesanLagiModal(url) {
            document.getElementById('pesanLagiModal').classList.remove('hidden');
            document.getElementById('confirmPesanLagiBtn').setAttribute('href', url);
        }

        function hidePesanLagiModal() {
            document.getElementById('pesanLagiModal').classList.add('hidden');
        }
    </script>
</body>
</html>