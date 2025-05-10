<?php
session_start();
include 'db.php';

// Pastikan hanya admin yang bisa akses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: user_manage.php");
    exit();
}

// Ambil data user berdasarkan ID
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "User tidak ditemukan!";
    exit();
}

// Proses update data user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    // Cek apakah password diubah
    if (!empty($password)) {
        $query = "UPDATE users SET username=?, email=?, role=?, password=? WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssi", $username, $email, $role, $password, $id);
    } else {
        $query = "UPDATE users SET username=?, email=?, role=? WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssi", $username, $email, $role, $id);
    }

    if ($stmt->execute()) {
        header("Location: user_manage.php");
        exit();
    } else {
        echo "Gagal mengupdate user!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit User</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="max-w-xl mx-auto mt-10 p-5 bg-white shadow rounded">
        <h2 class="text-2xl font-bold mb-4">Edit User</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="block text-gray-700">Username</label>
                <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" class="w-full border p-2 rounded">
            </div>
            <div class="mb-3">
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="w-full border p-2 rounded">
            </div>
            <div class="mb-3">
                <label class="block text-gray-700">Role</label>
                <select name="role" class="w-full border p-2 rounded">
                    <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>User</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="block text-gray-700">Password (Kosongkan jika tidak ingin diubah)</label>
                <input type="text" name="password" placeholder="Masukkan password baru" class="w-full border p-2 rounded">
                <small class="text-gray-500">Password saat ini: <b><?= htmlspecialchars($user['password']) ?></b></small>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
            <a href="user_manage.php" class="ml-3 text-gray-500">Kembali</a>
        </form>
    </div>
</body>

</html>