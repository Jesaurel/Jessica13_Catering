<?php
session_start();
include 'db.php';

// Pastikan hanya admin yang bisa melihat daftar order
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Get total orders from the database
$total_orders_query = "SELECT COUNT(*) as total_orders FROM orders";
$total_orders_result = mysqli_query($conn, $total_orders_query);
$total_orders_data = mysqli_fetch_assoc($total_orders_result);
$total_orders = $total_orders_data['total_orders'];

// Get total menu items (assuming you have a menu table)
$total_menu_query = "SELECT COUNT(*) as total_menu FROM menu";
$total_menu_result = mysqli_query($conn, $total_menu_query);
$total_menu_data = mysqli_fetch_assoc($total_menu_result);
$total_menu = $total_menu_data['total_menu'];

// Get total users from the database
$total_users_query = "SELECT COUNT(*) as total_users FROM users";
$total_users_result = mysqli_query($conn, $total_users_query);
$total_users_data = mysqli_fetch_assoc($total_users_result);
$total_users = $total_users_data['total_users'];

// Get the total sales
$total_sales_query = "SELECT SUM(total_price) AS total_sales FROM orders";
$total_sales_result = mysqli_query($conn, $total_sales_query);
$row = mysqli_fetch_assoc($total_sales_result);
$total_sales = $row['total_sales'];

// Get the order count
$order_count_query = "SELECT COUNT(id) AS order_count FROM orders";
$order_count_result = mysqli_query($conn, $order_count_query);
$order_row = mysqli_fetch_assoc($order_count_result);
$order_count = $order_row['order_count'];

// Get sales by date (monthly sales for example)
$sales_by_date_query = "SELECT DATE_FORMAT(delivery_date, '%Y-%m') AS month, SUM(total_price) AS monthly_sales FROM orders GROUP BY month ORDER BY month DESC";
$sales_by_date_result = mysqli_query($conn, $sales_by_date_query);

// Get best-selling items
$best_selling_query = "SELECT m.name, SUM(o.quantity) AS total_sold FROM orders o JOIN menu m ON o.menu_id = m.id GROUP BY m.id ORDER BY total_sold DESC LIMIT 5";
$best_selling_result = mysqli_query($conn, $best_selling_query);

// Close the database connection
mysqli_close($conn);
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce Dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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

.sidebar nav a i {
    margin-right: 0.75rem;
}

.dashboard-container {
    margin-left: 16rem;
    padding: 2.5rem;
}

.card {
    background-color: white;
    padding: 1rem;
    border-radius: 0.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.card .card-header {
    font-size: 1.25rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
}

.card .card-body {
    font-size: 2rem;
    font-weight: bold;
}

.card a {
    color: white;
    text-decoration: none;
    display: inline-block;
    margin-top: 0.5rem;
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
    <div class="flex">
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

        <!-- Main content -->
        <div class="flex-1 p-10 ml-64"> <!-- Tambahkan ml-64 di sini -->
            <div class="bg-green-500 text-white text-center py-2 rounded mb-4">Login Successful...</div>
            <div class="bg-white shadow rounded-lg p-4">
                <div class="flex items-center space-x-2 mb-4">
                    <i class="fas fa-bars"></i>
                    <span class="text-lg font-semibold">Beranda</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Total Orders -->
                    <div class="bg-teal-500 text-white p-4 rounded-lg shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-2xl font-bold"><?php echo $total_orders; ?></div>
                                <div>Total Order</div>
                            </div>
                            <i class="fas fa-shopping-bag text-4xl"></i>
                        </div>
                        <div class="mt-4">
                            <a href="order_manage.php" class="text-white hover:underline">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- Total Menu -->
                    <div class="bg-green-500 text-white p-4 rounded-lg shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-2xl font-bold"><?php echo $total_menu; ?></div>
                                <div>Total Menu</div>
                            </div>
                            <i class="fas fa-chart-bar text-4xl"></i>
                        </div>
                        <div class="mt-4">
                            <a href="menu.php" class="text-white hover:underline">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- Total Users -->
                    <div class="bg-yellow-500 text-white p-4 rounded-lg shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-2xl font-bold"><?php echo $total_users; ?></div>
                                <div>Total Users</div>
                            </div>
                            <i class="fas fa-user-plus text-4xl"></i>
                        </div>
                        <div class="mt-4">
                            <a href="user_manage.php" class="text-white hover:underline">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- Total Sales -->
                    <div class="bg-red-500 text-white p-4 rounded-lg shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-2xl font-bold"><?php echo number_format($total_sales); ?></div>
                                <div>Total Sales</div>
                            </div>
                            <i class="fas fa-chart-pie text-4xl"></i>
                        </div>
                        <div class="mt-4">
                            <a href="sales_report.php" class="text-white hover:underline">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body><script>
  document.getElementById('menuDropdownBtn').addEventListener('click', function () {
    var dropdown = document.getElementById('menuDropdown');
    dropdown.classList.toggle('hidden');
  });
</script>

</html>