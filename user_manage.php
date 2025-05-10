<?php
session_start();
include 'db.php';

// Pastikan hanya admin yang bisa melihat daftar order
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Ambil data user dari database
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Users</title>
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
    <!-- Users Section -->
    <div class="main-content p-6">
      <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-semibold text-gray-700">Users</h2>
          <div class="flex space-x-2">
          <a href="add_user.php" class="bg-blue-500 text-white px-4 py-2 rounded">NEW</a>
          </div>
        </div>

        <!-- Perbaiki tabel agar bisa scroll -->
        <div class="overflow-x-auto">
          <table class="min-w-full bg-white border">
            <thead>
              <tr>
                <th class="py-2 px-4 border">ID</th>
                <th class="py-2 px-4 border">Username</th>
                <th class="py-2 px-4 border">Email</th>
                <th class="py-2 px-4 border">Role</th>
                <th class="py-2 px-4 border">Created At</th>
                <th class="py-2 px-4 border">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                  <td class="py-2 px-4 border"><?php echo htmlspecialchars($row['id']); ?></td>
                  <td class="py-2 px-4 border"><?php echo htmlspecialchars($row['username']); ?></td>
                  <td class="py-2 px-4 border"><?php echo htmlspecialchars($row['email']); ?></td>
                  <td class="py-2 px-4 border"><?php echo htmlspecialchars($row['role']); ?></td>
                  <td class="py-2 px-4 border"><?php echo htmlspecialchars($row['created_at']); ?></td>
                  <td class="py-2 px-4 border relative">
                    <i class="fas fa-ellipsis-v cursor-pointer p-2 rounded-full hover:bg-gray-200" onclick="toggleMenu('menu<?php echo $row['id']; ?>')"></i>
                    <div id="menu<?php echo $row['id']; ?>" 
     class="dropdown absolute right-0 mt-2 min-w-[8rem] bg-white border rounded-lg shadow-lg hidden p-2 z-50">
    <a href="javascript:void(0);" 
       onclick="openEditModal('<?php echo $row['id']; ?>', '<?php echo addslashes($row['username']); ?>', '<?php echo addslashes($row['email']); ?>', '<?php echo $row['role']; ?>')" 
       class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">Edit</a>

    <a href="javascript:void(0);" 
       onclick="openDeleteModal('<?php echo $row['id']; ?>')" 
       class="block px-4 py-2 text-red-600 hover:bg-red-100 rounded">Hapus</a>
</div>

                  </td>

                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div> <!-- Tambah div ini -->
      </div>
    </div>
  </div>
</body>
<script>
    function toggleMenu(id) {
      document.querySelectorAll(".dropdown").forEach(menu => {
        if (menu.id !== id) menu.classList.add("hidden");
      });
      document.getElementById(id).classList.toggle("hidden");
    }

    document.addEventListener("click", function(event) {
      if (!event.target.closest(".relative")) {
        document.querySelectorAll(".dropdown").forEach(menu => menu.classList.add("hidden"));
      }
    });
    document.getElementById('menuDropdownBtn').addEventListener('click', function () {
    var dropdown = document.getElementById('menuDropdown');
    dropdown.classList.toggle('hidden');
  });
  function openDeleteModal(userId) {
    if (confirm("Apakah Anda yakin ingin menghapus user ini?")) {
        window.location.href = "delete_user.php?id=" + userId;
    }
}
  </script>
</html>