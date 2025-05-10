<?php
session_start();
include 'db.php'; // Koneksi ke database

$user_id = $_SESSION['user_id'];

// Buat token CSRF jika belum ada
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Tambah Alamat
if (isset($_POST['add_address'])) {
    $recipient_name = $_POST['recipient_name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $is_default = isset($_POST['is_default']) ? 1 : 0;

    if ($is_default) {
        $stmt = $conn->prepare("UPDATE user_addresses SET is_default = 0 WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
    }

    $stmt = $conn->prepare("INSERT INTO user_addresses (user_id, recipient_name, phone, address, is_default) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isssi", $user_id, $recipient_name, $phone, $address, $is_default);
    $stmt->execute();
    $stmt->close();

    header("Location: alamat.php");
    exit();
}

// Edit Alamat
if (isset($_POST['edit_address'])) {
    $id = $_POST['id'];
    $recipient_name = $_POST['recipient_name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $is_default = isset($_POST['is_default']) ? 1 : 0;

    if ($is_default) {
        $stmt = $conn->prepare("UPDATE user_addresses SET is_default = 0 WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
    }

    $stmt = $conn->prepare("UPDATE user_addresses SET recipient_name = ?, phone = ?, address = ?, is_default = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("sssiii", $recipient_name, $phone, $address, $is_default, $id, $user_id);
    $stmt->execute();
    $stmt->close();

    header("Location: alamat.php");
    exit();
}

// Hapus Alamat
if (isset($_POST['delete_address'])) {
    $id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM user_addresses WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    $stmt->close();

    header("Location: alamat.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Alamat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        :root {
            --primary: #D4A762;
            --secondary: #9A9A9A;
            --light: #FFFCF8;
            --dark: #050709;
        }
    </style>
</head>

<body class="bg-[var(--light)] text-[var(--dark)] font-['Open_Sans',sans-serif]">
    <div class="container mx-auto p-5">
        <h1 class="text-3xl font-bold text-[var(--dark)] mb-5">Kelola Alamat</h1>
        <button onclick="openModal('addModal')" class="bg-[var(--primary)] text-white px-4 py-2 rounded">Tambah Alamat</button>
        <div class="overflow-x-auto">
            <table class="w-full mt-4 border-collapse border border-[var(--secondary)] rounded-lg text-sm md:text-base">
                <thead class="bg-[var(--primary)] text-white">
                    <tr>
                        <th class="p-2 border">Nama Penerima</th>
                        <th class="p-2 border">Telepon</th>
                        <th class="p-2 border">Alamat</th>
                        <th class="p-2 border">Utama</th>
                        <th class="p-2 border">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $limit = 10;
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $offset = ($page - 1) * $limit;

                    // Cek koneksi database
                    if (!isset($conn)) {
                        die("Koneksi ke database tidak ditemukan.");
                    }

                    // Cek validitas user_id
                    if (!isset($user_id) || !is_numeric($user_id)) {
                        die("User ID tidak valid.");
                    }

                    // Query menggunakan prepared statement
                    $stmt = $conn->prepare("SELECT * FROM user_addresses WHERE user_id = ? LIMIT ? OFFSET ?");
                    $stmt->bind_param("iii", $user_id, $limit, $offset);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $menuId = "menu" . $row['id']; // ID unik dropdown

                            echo "<tr class='border-b border-[var(--secondary)] text-center text-[var(--dark)]'>
                    <td class='p-2 border'>" . htmlspecialchars($row['recipient_name']) . "</td>
                    <td class='p-2 border'>" . htmlspecialchars($row['phone']) . "</td>
                    <td class='p-2 border'>" . htmlspecialchars($row['address']) . "</td>
                    <td class='p-2 border'>" . ($row['is_default'] ? '✔' : '') . "</td>
                    <td class='p-2 border relative'>
                        <i class='fas fa-ellipsis-v cursor-pointer p-2 rounded-full bg-white hover:bg-gray-200 text-gray-700 text-lg' 
                           onclick=\"toggleMenu('$menuId')\"></i>

                        <div id='$menuId' 
                             class='dropdown absolute right-0 mt-2 min-w-[8rem] bg-white border rounded-lg shadow-lg hidden p-2 z-50'>
                            <a href='javascript:void(0);' 
                               onclick=\"openEditModal('{$row['id']}', '{$row['recipient_name']}', '{$row['phone']}', '{$row['address']}', '{$row['is_default']}')\" 
                               class='block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded'>Edit</a>

                            <a href='javascript:void(0);' 
                               onclick='openDeleteModal(" . htmlspecialchars($row['id']) . ")' 
                               class='block px-4 py-2 text-red-600 hover:bg-red-100 rounded'>Hapus</a>
                        </div>
                    </td>
                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='p-2 text-center'>Tidak ada alamat yang ditemukan.</td></tr>";
                    }

                    $stmt->close(); // Tutup statement
                    ?>
                </tbody>




                <div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center p-4">
                    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md relative">
                        <h2 class="text-2xl font-semibold mb-4 text-gray-800">Tambah Alamat</h2>

                        <!-- Input Nama Penerima -->
                        <input type="text" name="recipient_name" placeholder="Nama Penerima"
                            class="w-full border border-gray-300 rounded-md p-3 mb-3 focus:ring focus:ring-blue-200 focus:border-blue-500" required>

                        <!-- Input Nomor Telepon -->
                        <input type="text" name="phone" placeholder="Telepon"
                            class="w-full border border-gray-300 rounded-md p-3 mb-3 focus:ring focus:ring-blue-200 focus:border-blue-500" required>

                        <!-- Input Alamat -->
                        <textarea name="address" placeholder="Alamat"
                            class="w-full border border-gray-300 rounded-md p-3 mb-3 focus:ring focus:ring-blue-200 focus:border-blue-500" required></textarea>

                        <!-- Checkbox Jadikan Default -->
                        <label class="flex items-center text-gray-700">
                            <input type="checkbox" name="is_default" class="mr-2">
                            Jadikan Default
                        </label>

                        <!-- Tombol Aksi -->
                        <div class="mt-5 flex justify-end space-x-3">
                            <button type="button" onclick="closeModal('addModal')"
                                class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">
                                Batal
                            </button>
                            <button type="submit" name="add_address"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                                Simpan
                            </button>
                        </div>

                        <!-- Tombol Close Modal -->
                        <button type="button" onclick="closeModal('addModal')" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                            ✖
                        </button>
                    </div>
                </div>


                <!-- Modal Edit -->
                <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center p-4">
                    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md relative">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4 text-center">Edit Alamat</h2>

                        <form method="POST" onsubmit="return validateEditForm()">
                            <!-- ID Alamat (Hidden) -->
                            <input type="hidden" name="id" id="edit_id">

                            <!-- Input Nama Penerima -->
                            <div class="mb-3">
                                <label class="block text-gray-700 font-medium mb-1">Nama Penerima</label>
                                <input type="text" name="recipient_name" id="edit_name" required
                                    class="w-full border border-gray-300 p-3 rounded-md focus:ring focus:ring-blue-200 focus:border-blue-500">
                            </div>

                            <!-- Input Nomor Telepon -->
                            <div class="mb-3">
                                <label class="block text-gray-700 font-medium mb-1">Nomor Telepon</label>
                                <input type="text" name="phone" id="edit_phone" required
                                    class="w-full border border-gray-300 p-3 rounded-md focus:ring focus:ring-blue-200 focus:border-blue-500">
                            </div>

                            <!-- Input Alamat Lengkap -->
                            <div class="mb-3">
                                <label class="block text-gray-700 font-medium mb-1">Alamat Lengkap</label>
                                <textarea name="address" id="edit_address" required rows="3"
                                    class="w-full border border-gray-300 p-3 rounded-md focus:ring focus:ring-blue-200 focus:border-blue-500"></textarea>
                            </div>

                            <!-- Checkbox Jadikan Default -->
                            <div class="flex items-center gap-2 mb-4">
                                <input type="checkbox" name="is_default" id="edit_default" class="w-4 h-4 text-blue-600">
                                <label for="edit_default" class="text-gray-700">Jadikan sebagai alamat default</label>
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="flex justify-end space-x-3">
                                <button type="button" onclick="closeModal('editModal')"
                                    class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">
                                    Batal
                                </button>
                                <button type="submit" name="edit_address"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                                    Simpan
                                </button>
                            </div>
                        </form>

                        <!-- Tombol Close Modal -->
                        <button type="button" onclick="closeModal('editModal')"
                            class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                            ✖
                        </button>
                    </div>
                </div>




                <!-- Modal Hapus -->
                <div id="deleteModal" class="hidden fixed inset-0 bg-[var(--dark)] bg-opacity-75 flex justify-center items-center p-4">
                    <div class="bg-[var(--light)] p-6 rounded-lg shadow-lg w-full max-w-md relative">
                        <h2 class="text-2xl font-semibold text-[var(--dark)] mb-4 text-center">Hapus Alamat?</h2>

                        <form method="POST">
                            <!-- Input Hidden ID -->
                            <input type="hidden" name="id" id="delete_id">

                            <!-- Pesan Konfirmasi -->
                            <p class="text-[var(--secondary)] text-center mb-4">Apakah kamu yakin ingin menghapus alamat ini?</p>

                            <!-- Tombol Aksi -->
                            <div class="flex justify-between">
                                <button type="button" onclick="closeModal('deleteModal')"
                                    class="px-4 py-2 bg-[var(--secondary)] text-white rounded-md hover:bg-gray-600 transition">
                                    Batal
                                </button>
                                <button type="submit" name="delete_address"
                                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                                    Ya, Hapus
                                </button>
                            </div>
                        </form>

                        <!-- Tombol Close Modal -->
                        <button type="button" onclick="closeModal('deleteModal')"
                            class="absolute top-3 right-3 text-[var(--secondary)] hover:text-[var(--dark)]">
                            ✖
                        </button>
                    </div>
                </div>


                <script>
                    function openModal(id) {
                        document.getElementById(id).classList.remove('hidden');
                    }

                    function closeModal(id) {
                        document.getElementById(id).classList.add('hidden');
                    }

                    function openEditModal(id, name, phone, address, is_default) {
                        document.getElementById('edit_id').value = id;
                        document.getElementById('edit_name').value = name;
                        document.getElementById('edit_phone').value = phone;
                        document.getElementById('edit_address').value = address;
                        document.getElementById('edit_default').checked = is_default == '1';
                        openModal('editModal');
                    }

                    function openDeleteModal(id) {
                        document.getElementById('delete_id').value = id;
                        openModal('deleteModal');
                    }

                    document.addEventListener("click", function(event) {
                        if (!event.target.closest(".relative")) {
                            document.querySelectorAll(".dropdown").forEach(menu => menu.classList.add("hidden"));
                        }
                    });

                    function toggleMenu(id) {
                        document.querySelectorAll(".dropdown").forEach(menu => {
                            if (menu.id !== id) menu.classList.add("hidden");
                        });
                        document.getElementById(id).classList.toggle("hidden");
                    }

                    function validateEditForm() {
                        let name = document.getElementById('edit_name').value.trim();
                        let phone = document.getElementById('edit_phone').value.trim();
                        let address = document.getElementById('edit_address').value.trim();

                        if (name === '' || phone === '' || address === '') {
                            alert('Semua kolom harus diisi!');
                            return false;
                        }
                        return true;
                    }
                </script>
</body>

</html>