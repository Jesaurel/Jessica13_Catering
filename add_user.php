<?php
session_start();
include 'db.php';

// Pastikan hanya admin yang bisa akses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    $query = "INSERT INTO users (username, email, role, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $username, $email, $role, $password);

    if ($stmt->execute()) {
        header("Location: user_manage.php");
        exit();
    } else {
        echo "Gagal menambahkan user!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <title>Tambah User - Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 800px;
            margin: 50px auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin: 0 0 20px;
            font-size: 24px;
            color: #333;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        a {
            color: #555;
            text-decoration: none;
            margin-left: 10px;
        }

        a:hover {
            text-decoration: underline;
        }

        .breadcrumbs {
            font-size: 14px;
            margin-bottom: 20px;
            color: #666;
        }

        .breadcrumbs a {
            color: #007bff;
            text-decoration: none;
        }

        .breadcrumbs a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="breadcrumbs">
            <a href="dashboard.php">Dashboard</a> &gt; <a href="user_manage.php">Manajemen User</a> &gt; Tambah User
        </div>
        <h2>Tambah User Baru</h2>
        <form method="POST">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="role">Role</label>
            <select id="role" name="role" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>

            <label for="password">Password</label>
            <input type="text" id="password" name="password" required>

            <button type="submit">Simpan</button>
            <a href="user_manage.php">Kembali</a>
        </form>
    </div>
</body>

</html>