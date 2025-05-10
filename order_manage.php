<?php
session_start();
include 'db.php';

// Pastikan hanya admin yang bisa melihat daftar order
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$orders = $conn->query("SELECT orders.id, users.username, menu.name AS menu_name, orders.quantity, orders.total_price, orders.status, orders.created_at 
                        FROM orders 
                        JOIN users ON orders.user_id = users.id 
                        JOIN menu ON orders.menu_id = menu.id 
                        ORDER BY orders.created_at DESC");
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penjualan - E-Commerce Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<style>
    body {
    background-color: #f3f4f6;
    font-family: Arial, sans-serif;
}

.sidebar {
    width: 16rem;
    background-color: #1f2937;
    color: white;
    padding: 1.75rem 1rem;
    height: 100vh;
    position: fixed;
}

.sidebar .title {
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 1.5rem;
}

.sidebar nav a {
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
    border-radius: 0.375rem;
    text-decoration: none;
    color: white;
    transition: background-color 0.3s;
}

.sidebar nav a:hover, .sidebar nav a.active {
    background-color: #2563eb;
}
    .main-content {
      margin-left: 16rem;
      /* Sesuaikan dengan sidebar */
      width: calc(100% - 16rem);
      overflow-x: auto;
    }
    .logout-btn {
    background-color: #dc2626;
    color: white;
    font-weight: bold;
    padding: 0.75rem 1rem;
    border-radius: 0.375rem;
    text-align: center;
    display: block;
    width: 100%;
    margin-top: 1rem;
    transition: background-color 0.3s;
}

.logout-btn:hover {
    background-color: #b91c1c;
}
  </style>
</head>

<body class="bg-gray-100">
  <div class="flex h-screen">
     <!-- Sidebar -->
     <div class="sidebar bg-gray-800 text-white py-7 px-4 fixed h-screen w-64">
            <div>
                <div class="text-2xl font-extrabold mb-6">E-Commerce</div>
                <div class="flex items-center space-x-3 mb-4">
                    <img src="https://placehold.co/40x40" class="rounded-full w-10 h-10">
                    <span>admin123</span>
                </div>
                <nav class="space-y-2">
                    <a href="dashboard.php" class="flex items-center py-3 px-4 rounded transition duration-200 hover:bg-gray-700 bg-blue-500">
                        <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
                    </a>
                    <a href="order_manage.php" class="flex items-center py-3 px-4 rounded transition duration-200 hover:bg-gray-700">
                        <i class="fas fa-box mr-3"></i> Orders
                    </a>

                    <!-- Dropdown Menu -->
                    <div class="relative">
                        <button id="menuDropdownBtn" class="flex items-center justify-between w-full py-3 px-4 rounded transition duration-200 hover:bg-gray-700">
                            <div class="flex items-center">
                                <i class="fas fa-cubes mr-3"></i> Menu
                            </div>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div id="menuDropdown" class="hidden bg-gray-700 rounded mt-2 space-y-2">
                            <a href="menu.php" class="block py-2 px-4 hover:bg-gray-600">Menu</a>
                            <a href="manage_categories.php" class="block py-2 px-4 hover:bg-gray-600">Category Menu</a>
                        </div>
                    </div>

                    <a href="user_manage.php" class="flex items-center py-3 px-4 rounded transition duration-200 hover:bg-gray-700">
                        <i class="fas fa-users mr-3"></i> Users
                    </a>
                    <a href="sales_report.php" class="flex items-center py-3 px-4 rounded transition duration-200 hover:bg-gray-700">
                        <i class="fas fa-chart-line mr-3"></i> Penjualan
                    </a>
                </nav>
            </div>

            <!-- Logout Button -->
            <form action="logout.php" method="POST">
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-3 px-4 rounded w-full mt-4">
                    Logout
                </button>
            </form>
        </div>

<body class="bg-gray-100 p-6">
<div class="flex-1 p-6 ml-64">
        <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Daftar Order</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while ($order = $orders->fetch_assoc()) { ?>
            <div class="bg-white shadow-lg rounded-lg p-5 border-l-4 <?php 
                echo ($order['status'] == 'Pending') ? 'border-red-500' : 
                     (($order['status'] == 'Diproses') ? 'border-yellow-500' : 'border-green-500');
            ?>">
                <h2 class="text-lg font-semibold text-gray-700 flex items-center">
                    <i class="fas fa-utensils mr-2"></i> <?php echo htmlspecialchars($order['menu_name']); ?>
                </h2>
                <p class="text-gray-500"><i class="fas fa-user mr-1"></i> <?php echo htmlspecialchars($order['username']); ?></p>
                <p class="text-gray-500"><i class="fas fa-hashtag mr-1"></i> Order #<?php echo $order['id']; ?></p>
                <p class="text-gray-500"><i class="fas fa-coins mr-1"></i> Rp<?php echo number_format($order['total_price'], 0, ',', '.'); ?></p>
                
                <p class="mt-3 text-sm font-semibold flex items-center <?php 
                    echo ($order['status'] == 'Pending') ? 'text-red-500' : 
                         (($order['status'] == 'Diproses') ? 'text-yellow-500' : 'text-green-500');
                ?>">
                    <i class="fas fa-circle-notch mr-2"></i> <?php echo ucfirst($order['status']); ?>
                </p>
                
                <a href="detail_order.php?id=<?php echo $order['id']; ?>" 
                   class="mt-4 block bg-blue-500 text-white text-center py-2 rounded hover:bg-blue-700 transition duration-200">
                   <i class="fas fa-eye"></i> Lihat Detail
                </a>
            </div>
            <?php } ?>
        </div>
    </div>
</body>
<script>
    document.getElementById('menuDropdownBtn').addEventListener('click', function () {
    var dropdown = document.getElementById('menuDropdown');
    dropdown.classList.toggle('hidden');
  });
</script>
</html>