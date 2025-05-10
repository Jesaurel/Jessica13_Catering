<?php
include 'db.php';

$sql = "SELECT menu.*, categories.name AS category_name
        FROM menu
        JOIN categories ON menu.category_id = categories.id";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Menu Products</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <script>
        function showEditModal(id, name, description, price, image, categoryId) {
            document.getElementById('edit-modal').classList.remove('hidden');
            document.getElementById('edit-menu-id').value = id;
            document.getElementById('edit-menu-name').value = name;
            document.getElementById('edit-menu-description').value = description;
            document.getElementById('edit-menu-price').value = price;
            document.getElementById('edit-menu-image-preview').src = 'data:image/jpeg;base64,' + image;

            const categoryDropdown = document.getElementById('edit-menu-category');
            for (let i = 0; i < categoryDropdown.options.length; i++) {
                if (categoryDropdown.options[i].value == categoryId) {
                    categoryDropdown.selectedIndex = i;
                    break;
                }
            }
        }

        function hideEditModal() {
            document.getElementById('edit-modal').classList.add('hidden');
        }

        function updateMenu() {
            let formData = new FormData(document.getElementById('edit-form'));

            fetch('edit_menu.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                location.reload();
            })
            .catch(error => console.error('Error:', error));
        }

        function showDeleteModal(id, name, description, image) {
            document.getElementById('delete-modal').classList.remove('hidden');
            document.getElementById('delete-product-id').textContent = id;
            document.getElementById('delete-product-name').textContent = name;
            document.getElementById('delete-product-description').textContent = description;
            document.getElementById('delete-product-image').src = 'data:image/jpeg;base64,' + image;
            document.getElementById('confirm-delete-btn').setAttribute('data-id', id);
        }

        function hideDeleteModal() {
            document.getElementById('delete-modal').classList.add('hidden');
        }

        function deleteProduct() {
            var productId = document.getElementById('confirm-delete-btn').getAttribute('data-id');

            fetch('delete_menu.php?id=' + productId, {
                method: 'GET'
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                location.reload();
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
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

        .sidebar nav a:hover,
        .sidebar nav a.active {
            background-color: #2563eb;
        }

        .main-content {
            margin-left: 16rem;
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

            <form action="logout.php" method="POST">
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-3 px-4 rounded w-full mt-4">
                    Logout
                </button>
            </form>
        </div>

        <div class="flex-1 p-6 ml-64">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold">Menu</h1>
                <button class="px-4 py-2 bg-blue-600 text-white rounded flex items-center" onclick="window.location.href='add_menu.php'">
                    <i class="fas fa-plus mr-2"></i> Tambah Menu
                </button>
            </div>

            <div class="bg-white shadow rounded-lg p-4">
                <h2 class="text-lg font-semibold mb-4">Detail Menu</h2>
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">Menu Id</th>
                            <th class="py-2 px-4 border-b">Nama</th>
                            <th class="py-2 px-4 border-b">Deskripsi</th>
                            <th class="py-2 px-4 border-b">Harga</th>
                            <th class="py-2 px-4 border-b">Foto</th>
                            <th class="py-2 px-4 border-b">Kategori</th>
                            <th class="py-2 px-4 border-b">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td class="py-2 px-4 border-b">' . $row['id'] . '</td>';
                                echo '<td class="py-2 px-4 border-b">' . $row['name'] . '</td>';
                                echo '<td class="py-2 px-4 border-b">' . $row['description'] . '</td>';
                                echo '<td class="py-2 px-4 border-b">Rp ' . number_format($row['price'], 0, ',', '.') . '</td>';
                                echo '<td class="py-2 px-4 border-b"><img class="w-16 h-16 object-cover rounded" src="data:image/jpeg;base64,' . base64_encode($row['image']) . '" /></td>';
                                echo '<td class="py-2 px-4 border-b">' . $row['category_name'] . '</td>';
                                echo '<td class="py-2 px-4 border-b">
                                    <div class="flex items-center space-x-1">
                                        <button class="px-2 py-1 bg-yellow-500 text-white text-sm rounded flex items-center mr-2"
                                                onclick="showEditModal(\'' . $row['id'] . '\', \'' . $row['name'] . '\', \'' . $row['description'] . '\', \'' . $row['price'] . '\', \'' . base64_encode($row['image']) . '\', \'' . $row['category_id'] . '\')">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </button>
                                        <button class="px-2 py-1 bg-red-500 text-white text-sm rounded flex items-center"
                                                onclick="showDeleteModal(\'' . $row['id'] . '\', \'' . $row['name'] . '\', \'' . $row['description'] . '\', \'' . base64_encode($row['image']) . '\')">
                                            <i class="fas fa-trash mr-1"></i> Delete
                                        </button>
                                    </div>
                                </td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="7" class="text-center py-4">No menu found</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="delete-modal" class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-75 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
            <h2 class="text-lg font-medium text-gray-900">Delete Product</h2>
            <p class="mt-2 text-sm text-gray-500">Are you sure you want to delete this product?</p>
            <div class="mt-4">
                <p class="text-sm text-gray-700"><strong>Product Id:</strong> <span id="delete-product-id"></span></p>
                <p class="text-sm text-gray-700"><strong>Product Name:</strong> <span id="delete-product-name"></span></p>
                <p class="text-sm text-gray-700"><strong>Product Description:</strong> <span id="delete-product-description"></span></p>
                <div class="mt-2">
                    <img id="delete-product-image" class="w-20 h-20 object-cover rounded">
                </div>
            </div>
            <div class="mt-5 flex justify-end space-x-3">
                <button class="bg-gray-300 px-4 py-2 rounded" onclick="hideDeleteModal()">Cancel</button>
                <button id="confirm-delete-btn" class="bg-red-600 px-4 py-2 text-white rounded" onclick="deleteProduct()">Delete</button>
            </div>
        </div>
    </div>

    <div id="edit-modal" class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-75 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
            <h2 class="text-lg font-medium text-gray-900">Edit Product</h2>
            <form id="edit-form">
                <input type="hidden" id="edit-menu-id" name="id">
                <div class="mt-4">
                    <label class="text-sm text-gray-700">Name:</label>
                    <input type="text" id="edit-menu-name" name="name" class="w-full border rounded px-3 py-2">
                </div>
                <div class="mt-2">
                    <label class="text-sm text-gray-700">Description:</label>
                    <textarea id="edit-menu-description" name="description" class="w-full border rounded px-3 py-2"></textarea>
                </div>
                <div class="mt-2">
                    <label class="text-sm text-gray-700">Price:</label>
                    <input type="number" id="edit-menu-price" name="price" class="w-full border rounded px-3 py-2">
                </div>
                <div class="mt-2">
                    <label class="text-sm text-gray-700">Image:</label>
                    <img id="edit-menu-image-preview" class="w-20 h-20 object-cover rounded">
                </div>
                <div class="mt-2">
                    <label class="text-sm text-gray-700">Category:</label>
                    <select id="edit-menu-category" name="category_id" class="w-full border rounded px-3 py-2">
                        <?php
                        // Query untuk mengambil semua kategori dari tabel categories
                        $categories_sql = "SELECT id, name FROM categories";
                        $categories_result = mysqli_query($conn, $categories_sql);

                        if ($categories_result && $categories_result->num_rows > 0) {
                            while ($category_row = $categories_result->fetch_assoc()) {
                                echo '<option value="' . $category_row['id'] . '">' . $category_row['name'] . '</option>';
                            }
                        } else {
                            echo '<option value="">No categories found</option>';
                        }
                        ?>
                    </select>
                </div>
            </form>
            <div class="mt-5 flex justify-end space-x-3">
                <button class="bg-gray-300 px-4 py-2 rounded" onclick="hideEditModal()">Cancel</button>
                <button class="bg-blue-600 px-4 py-2 text-white rounded" onclick="updateMenu()">Save</button>
            </div>
        </div>
    </div>
</body>
<script>
    document.getElementById('menuDropdownBtn').addEventListener('click', function() {
        var dropdown = document.getElementById('menuDropdown');
        dropdown.classList.toggle('hidden');
    });
</script>

</html>

<?php
if ($conn) {
    mysqli_close($conn);
}
?>