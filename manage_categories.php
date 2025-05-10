<?php
session_start();
include 'db.php';

// Pastikan hanya admin yang bisa melihat daftar order
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Handle Add Category
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action == "add") {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $query = "INSERT INTO categories (name) VALUES ('$name')";
        if (mysqli_query($conn, $query)) {
            // Ambil ID terbaru agar kategori yang baru masuk ada di urutan paling atas
            $last_id = mysqli_insert_id($conn);
            echo "success|$last_id|$name"; 
        } else {
            echo "error";
        }
    }
    
    
    if ($action == "edit") {
        $id = $_POST['id'];
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $query = "UPDATE categories SET name = '$name' WHERE id = $id";
        echo mysqli_query($conn, $query) ? "success" : "error";
    }
    
    if ($action == "delete") {
        $id = $_POST['id'];
        $query = "DELETE FROM categories WHERE id = $id";
        echo mysqli_query($conn, $query) ? "success" : "error";
    }
    exit();
}

$categories_result = mysqli_query($conn, "SELECT * FROM categories ORDER BY id DESC");
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
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
    
<body class="bg-gray-100">
  <div class="ml-64 p-6 w-full">
    <div class="bg-blue-500 text-white text-center py-2 rounded mb-4">Manage Categories</div>
    <button class="bg-green-500 text-white py-2 px-4 rounded mb-4" onclick="openModal('addModal')">
        <i class="fas fa-plus"></i> Add Category
    </button>
    <div class="bg-white shadow rounded-lg p-4">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr>
                    <th class="border px-4 py-2">ID</th>
                    <th class="border px-4 py-2">Category Name</th>
                    <th class="border px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($categories_result)): ?>
                    <tr>
                        <td class="border px-4 py-2"> <?php echo $row['id']; ?> </td>
                        <td class="border px-4 py-2"> <?php echo $row['name']; ?> </td>
                        <td class="border px-4 py-2">
                            <button class="bg-yellow-500 text-white px-3 py-1 rounded" onclick="openEditModal(<?php echo $row['id']; ?>, '<?php echo $row['name']; ?>')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="bg-red-500 text-white px-3 py-1 rounded" onclick="confirmDelete(<?php echo $row['id']; ?>)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
  </div>
  
  <!-- Add & Edit Modal -->
  <div id="addModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white p-6 rounded shadow-lg">
      <h2 class="text-xl font-bold mb-4">Add Category</h2>
      <input type="text" id="newCategory" class="border p-2 w-full mb-4" placeholder="Category Name">
      <button class="bg-blue-500 text-white px-4 py-2 rounded" onclick="manageCategory('add')">Add</button>
      <button class="bg-gray-500 text-white px-4 py-2 rounded" onclick="closeModal('addModal')">Cancel</button>
    </div>
  </div>
  <div id="editModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white p-6 rounded shadow-lg">
      <h2 class="text-xl font-bold mb-4">Edit Category</h2>
      <input type="hidden" id="editCategoryId">
      <input type="text" id="editCategoryName" class="border p-2 w-full mb-4">
      <button class="bg-blue-500 text-white px-4 py-2 rounded" onclick="manageCategory('edit')">Update</button>
      <button class="bg-gray-500 text-white px-4 py-2 rounded" onclick="closeModal('editModal')">Cancel</button>
    </div>
  </div>

  <script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }
    function openEditModal(id, name) {
        document.getElementById('editCategoryId').value = id;
        document.getElementById('editCategoryName').value = name;
        openModal('editModal');
    }
    function manageCategory(action) {
        let formData = new FormData();
        formData.append("action", action);
        
        if (action === "add") {
            let name = document.getElementById('newCategory').value;
            if (name.trim() === "") return alert("Nama kategori tidak boleh kosong!");
            formData.append("name", name);
        }
        if (action === "edit") {
            let id = document.getElementById('editCategoryId').value;
            let name = document.getElementById('editCategoryName').value;
            if (name.trim() === "") return alert("Nama kategori tidak boleh kosong!");
            formData.append("id", id);
            formData.append("name", name);
        }
        fetch(window.location.href, { method: "POST", body: formData })
            .then(response => response.text())
            .then(data => { if (data === "success") location.reload(); else alert("Gagal!"); });
    }
    function confirmDelete(id) {
        if (!confirm("Yakin ingin menghapus kategori ini?")) return;
        let formData = new FormData();
        formData.append("action", "delete");
        formData.append("id", id);
        fetch(window.location.href, { method: "POST", body: formData })
            .then(response => response.text())
            .then(data => { if (data === "success") location.reload(); else alert("Gagal menghapus!"); });
    }
    function manageCategory(action) {
    let formData = new FormData();
    formData.append("action", action);
    
    if (action === "add") {
        let name = document.getElementById('newCategory').value;
        if (name.trim() === "") return alert("Nama kategori tidak boleh kosong!");
        formData.append("name", name);
    }

    fetch(window.location.href, { method: "POST", body: formData })
        .then(response => response.text())
        .then(data => { 
            let res = data.split("|");
            if (res[0] === "success") {
                if (action === "add") {
                    let id = res[1];
                    let name = res[2];
                    let tableBody = document.querySelector("tbody");
                    let newRow = document.createElement("tr");
                    newRow.innerHTML = `
                        <td class="border px-4 py-2">${id}</td>
                        <td class="border px-4 py-2">${name}</td>
                        <td class="border px-4 py-2">
                            <button class="bg-yellow-500 text-white px-3 py-1 rounded" onclick="openEditModal(${id}, '${name}')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="bg-red-500 text-white px-3 py-1 rounded" onclick="confirmDelete(${id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    `;
                    tableBody.prepend(newRow); // Tambahkan di paling atas
                }
                closeModal('addModal');
            } else {
                alert("Gagal menambahkan kategori!");
            }
        });
}
document.getElementById('menuDropdownBtn').addEventListener('click', function () {
    var dropdown = document.getElementById('menuDropdown');
    dropdown.classList.toggle('hidden');
  });
  </script>
</body>
</html>