<?php
session_start();
require 'db.php'; // File koneksi database

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['user_id']; // Ambil user_id dari session

// Query untuk menghitung jumlah pesanan berdasarkan user yang login
$query_orders = "SELECT COUNT(*) AS order_count FROM orders WHERE user_id = ?";
$stmt_orders = $conn->prepare($query_orders);
$stmt_orders->bind_param("i", $user_id);
$stmt_orders->execute();
$result_orders = $stmt_orders->get_result();
$order_count = $result_orders->fetch_assoc()['order_count'] ?? 0;
$stmt_orders->close();

// Query untuk menghitung total review yang diberikan oleh user
$query_reviews = "SELECT COUNT(*) AS review_count FROM reviews WHERE user_id = ?";
$stmt_reviews = $conn->prepare($query_reviews);
$stmt_reviews->bind_param("i", $user_id);
$stmt_reviews->execute();
$result_reviews = $stmt_reviews->get_result();
$review_count = $result_reviews->fetch_assoc()['review_count'] ?? 0;
$stmt_reviews->close();

// Ambil data user dari database
$query_user = $conn->prepare("SELECT username, email, role, created_at FROM users WHERE id = ?");
$query_user->bind_param("i", $user_id);
$query_user->execute();
$result_user = $query_user->get_result();
$user = $result_user->fetch_assoc();
$query_user->close();

if (!$user) {
  echo "User tidak ditemukan.";
  exit();
}

// Hitung total jumlah item dalam keranjang (hanya status Pending)
$cart_count = 0;
$query_cart = "SELECT SUM(quantity) AS total_items FROM orders WHERE user_id = ? AND status = 'Pending'";
$stmt_cart = $conn->prepare($query_cart);
$stmt_cart->bind_param("i", $user_id);
$stmt_cart->execute();
$result_cart = $stmt_cart->get_result();
$cart_count = $result_cart->fetch_assoc()['total_items'] ?? 0;
$stmt_cart->close();

// Simpan data user ke dalam variabel untuk digunakan dalam halaman
$username = htmlspecialchars($user['username']);
$email = htmlspecialchars($user['email']);

// Ambil review dari database berdasarkan user_id
$user_id = $_SESSION['user_id']; // Pastikan user_id dideklarasikan
$query = "SELECT * FROM reviews WHERE user_id = ?";
$query = "SELECT reviews.*, COALESCE(menu.name, 'Menu tidak ditemukan') AS menu_name 
          FROM reviews 
          LEFT JOIN menu ON reviews.menu_id = menu.id 
          WHERE reviews.user_id = ? 
          ORDER BY reviews.created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();


$stmt->close();


// Proses penghapusan review
if (isset($_GET['delete_id'])) {
  $delete_id = $_GET['delete_id'];
  $delete_query = "DELETE FROM reviews WHERE id = ?";
  $delete_stmt = $conn->prepare($delete_query);
  $delete_stmt->bind_param("i", $delete_id);

  if ($delete_stmt->execute()) {
    echo "<script>alert('Review berhasil dihapus!'); window.location.href='profile.php';</script>";
  } else {
    echo "<script>alert('Gagal menghapus review!');</script>";
  }
  $delete_stmt->close();
}

// Proses update review
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['review_id'], $_POST['review'], $_POST['rating'])) {
    $review_id = $_POST['review_id'];
    $review_text = $_POST['review'];
    $rating = $_POST['rating'];

    if (!empty($review_id) && !empty($review_text) && !empty($rating)) {
      $query_update = "UPDATE reviews SET review = ?, rating = ? WHERE id = ?";
      $stmt_update = $conn->prepare($query_update);
      $stmt_update->bind_param("sii", $review_text, $rating, $review_id);

      if ($stmt_update->execute()) {
        echo "<script>alert('Review berhasil diperbarui!'); window.location.href='profile.php';</script>";
      } else {
        echo "<script>alert('Gagal memperbarui review!');</script>";
      }
      $stmt_update->close();
    }
  }
}

$query_spending = "SELECT SUM(total_price) AS total_spending FROM orders WHERE user_id = $user_id AND status = 'Selesai'";
$result_spending = $conn->query($query_spending);
$row_spending = $result_spending->fetch_assoc();
$total_spending = $row_spending['total_spending'] ?? 0;

// Pesanan Paling Sering Dibeli
$query_fav_order = "SELECT menu_id, COUNT(*) AS total FROM orders WHERE user_id = $user_id GROUP BY menu_id ORDER BY total DESC LIMIT 1";
$result_fav_order = $conn->query($query_fav_order);
$row_fav_order = $result_fav_order->fetch_assoc();
$favorite_order = $row_fav_order ? "Menu ID {$row_fav_order['menu_id']} - {$row_fav_order['total']}x Dipesan" : "Belum ada pesanan favorit";

// Status Keaktifan Pengguna (Jumlah Pesanan dalam 30 Hari Terakhir)
$query_activity = "SELECT COUNT(*) AS order_count FROM orders WHERE user_id = $user_id AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
$result_activity = $conn->query($query_activity);
$row_activity = $result_activity->fetch_assoc();
$order_count = $row_activity['order_count'] ?? 0;

// Pesanan Terbaru
$query_recent_order = "SELECT menu_id, status FROM orders WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 1";
$result_recent_order = $conn->query($query_recent_order);
$row_recent_order = $result_recent_order->fetch_assoc();
$recent_order = $row_recent_order ? "Menu ID {$row_recent_order['menu_id']} - {$row_recent_order['status']}" : "Belum ada pesanan";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>CaterLez</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="" name="keywords">
  <meta content="" name="description">

  <!-- Google Web Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Playball&display=swap" rel="stylesheet">

  <!-- Icon Font Stylesheet -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Libraries Stylesheet -->
  <link href="lib/animate/animate.min.css" rel="stylesheet">
  <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
  <link href="lib/owlcarousel/owl.carousel.min.css" rel="stylesheet">

  <!-- Customized Bootstrap Stylesheet -->
  <link href="css/bootstrap.min.css" rel="stylesheet">

  <!-- Template Stylesheet -->
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


<body class="bg-light">
  <div class="d-flex flex-column flex-md-row min-vh-100">
    <!-- Sidebar -->
    <div class="d-none d-md-block bg-white p-4 shadow-lg" style="border-top-right-radius: 20px; border-bottom-right-radius: 20px;">
      <div class="d-flex align-items-center mb-4">
        <h2 class="text-primary fw-bold">K</h2>
      </div>
      <nav class="nav flex-column">
        <a class="nav-link text-primary" href="crud_profile_user.php">
          <i class="fas fa-user me-2"></i>Edit Profil
        </a>
        <a class="nav-link text-secondary" href="index.php">
          <i class="fas fa-home me-2"></i>Beranda
        </a>
        <a class="nav-link text-secondary" href="alamat.php">
          <i class="fas fa-map-marker-alt me-2"></i>Alamat
        </a>
        <a class="nav-link text-secondary" href="order.php">
          <i class="fas fa-history me-2"></i>Riwayat Pesanan
        </a>
      </nav>
      <div class="mt-5 p-3 bg-light rounded text-center">
        <img src="https://storage.googleapis.com/a1aa/image/3grY1mUcu_jhpG6_VD-etxkQq60uU1287b9oMa8FVpU.jpg" class="img-fluid mb-3" width="80" alt="Keranjang">
        <p class="text-primary">Ada produk di keranjang Anda.</p>
        <a href="order.php" class="btn btn-primary rounded-pill px-4">Periksa keranjang Anda</a>
      </div>
    </div>

    <!-- Mobile Sidebar -->
    <div class="d-md-none position-relative">
      <button class="btn btn-light p-3" id="menu-btn">
        <i class="fas fa-bars text-primary fs-4"></i>
      </button>
      <div class="position-fixed top-0 start-0 w-100 vh-100 bg-white p-4 d-none" id="menu">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h2 class="text-primary fw-bold">K</h2>
          <button class="btn btn-light" id="close-btn">
            <i class="fas fa-times text-primary fs-4"></i>
          </button>
        </div>
        <nav class="nav flex-column">
          <a class="nav-link text-primary" href="crud_profile_user.php">
            <i class="fas fa-user me-2"></i>Edit Profil
          </a>
          <a class="nav-link text-primary" href="index.php">
            <i class="fas fa-home me-2"></i>Home
          </a>
          <a class="nav-link text-secondary" href="order.php">
            <i class="fas fa-history me-2"></i>Order History
          </a>
          <a class="nav-link text-secondary" href="404.html">
            <i class="fas fa-cog me-2"></i>Setting
          </a>
        </nav>
      </div>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1 p-4">
    <header class="d-flex justify-content-between align-items-center mb-4">
        
        <div class="d-flex align-items-center">
            <img src="https://storage.googleapis.com/a1aa/image/VwYPwOcvSCP72dApS_eayu3BhBi645rKuLO-Xj7bn9s.jpg" class="rounded-circle" width="40" height="40" alt="Pengguna">
            <span class="ms-2">Halo, <?php echo $_SESSION['username']; ?></span>
            <i class="fas fa-chevron-down text-secondary ms-2"></i>
        </div>
    </header>
      <main>
        <h1 class="text-2xl font-semibold mb-2 text-gray-800">
          PROFIL
        </h1>
        <p class="text-gray-500 mb-6 text-sm">
          Selamat datang kembali! Berikut adalah ringkasan aktivitas terbaru Anda.
        </p>
      </main>

      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="p-4 bg-white border border-gray-300 text-gray-800 rounded-lg shadow-md flex items-center space-x-3">
          <div class="text-2xl">
            💰
          </div>
          <div>
            <h2 class="text-sm font-medium">Total Pengeluaran</h2>
            <p class="text-base font-semibold">Rp <?php echo number_format($total_spending, 0, ',', '.'); ?></p>
          </div>
        </div>

        <div class="p-4 bg-white border border-gray-300 text-gray-800 rounded-lg shadow-md flex items-center space-x-3">
          <div class="text-2xl">
            🍽️
          </div>
          <div>
            <h2 class="text-sm font-medium">Pesanan Favorit Anda</h2>
            <p class="text-base font-semibold"><?php echo $favorite_order; ?></p>
          </div>
        </div>

        <div class="p-4 bg-white border border-gray-300 text-gray-800 rounded-lg shadow-md flex items-center space-x-3">
          <div class="text-2xl">
            📅
          </div>
          <div>
            <h2 class="text-sm font-medium">Aktivitas Anda</h2>
            <p class="text-base font-semibold"><?php echo $order_count; ?> Pesanan / 30 Hari</p>
          </div>
        </div>

        <div class="p-4 bg-white border border-gray-300 text-gray-800 rounded-lg shadow-md flex items-center space-x-3">
          <div class="text-2xl">
            🚚
          </div>
          <div>
            <h2 class="text-sm font-medium">Pesanan Terbaru</h2>
            <p class="text-base font-semibold"><?php echo $recent_order; ?></p>
          </div>
        </div>
      </div>

      <!--bagian review-->
      <div class="mb-8">
        <h2 class="text-xl font-bold mb-2">ULASAN ANDA</h2>
        <p class="text-gray-500 mb-4">Di sini untuk memeriksa pesan yang Anda tinggalkan</p>
        <div class="overflow-x-auto whitespace-nowrap">
          <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
              <div class="inline-block w-80 md:w-96 mr-4 p-4 bg-gray-100 rounded-xl shadow mb-4">
                <h3 class="font-bold mb-2"><?= htmlspecialchars($row['menu_name']) ?></h3>
                <p class="text-gray-600">Rating: ⭐<?= $row['rating'] ?>/5</p>
                <p class="text-gray-600 mb-4"><?= htmlspecialchars($row['review']) ?></p>
                <div class="flex space-x-4">
                  <button onclick="openEditModal(<?= $row['id'] ?>, '<?= htmlspecialchars(addslashes($row['review'])) ?>', <?= $row['rating'] ?>)" class="px-4 py-2 bg-blue-600 text-white rounded-full">Edit</button>
                  <a href="?delete_id=<?= $row['id'] ?>" class="px-4 py-2 bg-red-600 text-white rounded-full" onclick="return confirm('Yakin ingin menghapus ulasan ini?')">Hapus</a>
                </div>
              </div>
            <?php endwhile; ?>
          <?php else: ?>
            <p class="text-gray-500">Belum ada ulasan.</p>
          <?php endif; ?>
        </div>
      </div>

      <div id="editReviewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96 border border-gray-300">
        <h3 class="text-lg font-bold">Edit Ulasan</h3>
        <form action="profile.php" method="POST">
            <input type="hidden" name="review_id" id="editReviewId">
            <label class="block mt-4">Rating:</label>
            <select name="rating" id="editRating" class="w-full p-2 border rounded">
                <option value="5">⭐️⭐️⭐️⭐️⭐️</option>
                <option value="4">⭐️⭐️⭐️⭐️</option>
                <option value="3">⭐️⭐️⭐️</option>
                <option value="2">⭐️⭐️</option>
                <option value="1">⭐️</option>
            </select>
            <label class="block mt-4">Ulasan:</label>
            <textarea name="review" id="editReviewText" class="w-full p-2 border rounded" required></textarea>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-full mt-4">Simpan</button>
            <button type="button" onclick="closeEditModal()" class="w-full bg-gray-400 text-black py-2 rounded-full mt-2">Batal</button>
        </form>
    </div>
</div>

    <div class="flex flex-col md:flex-row justify-between mb-8">

      <!-- Modal Edit Review -->
      <div id="editReviewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h3 class="text-lg font-bold">Edit Ulasan</h3>
        <form action="profile.php" method="POST">
            <input type="hidden" name="review_id" id="editReviewId">
            <label class="block mt-4">Rating:</label>
            <select name="rating" id="editRating" class="w-full p-2 border rounded">
                <option value="5">⭐️⭐️⭐️⭐️⭐️</option>
                <option value="4">⭐️⭐️⭐️⭐️</option>
                <option value="3">⭐️⭐️⭐️</option>
                <option value="2">⭐️⭐️</option>
                <option value="1">⭐️</option>
            </select>
            <label class="block mt-4">Ulasan:</label>
            <textarea name="review" id="editReviewText" class="w-full p-2 border rounded" required></textarea>
            <button type="submit" class="w-full bg-biru-500 text-putih py-2 rounded-full mt-4">Simpan</button>
            <button type="button" onclick="closeEditModal()" class="w-full bg-abu-abu-300 text-hitam py-2 rounded-full mt-2">Batal</button>
        </form>
    </div>
</div>
    </div>
    </main>
  </div>

  <script>
    document.getElementById('menu-btn').addEventListener('click', function() {
      document.getElementById('menu').classList.remove('d-none');
    });
    document.getElementById('close-btn').addEventListener('click', function() {
      document.getElementById('menu').classList.add('d-none');
    });

    function openEditModal(id, review, rating) {
      document.getElementById('editReviewId').value = id;
      document.getElementById('editReviewText').value = review;
      document.getElementById('editRating').value = rating;
      document.getElementById('editReviewModal').classList.remove('hidden');
    }

    function closeEditModal() {
      document.getElementById('editReviewModal').classList.add('hidden');
    }
  </script>
</body>

</html>