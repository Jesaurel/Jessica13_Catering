<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Query untuk menghapus user berdasarkan ID
    $sql = "DELETE FROM users WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('User berhasil dihapus!'); window.location.href='user_manage.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus user!'); window.location.href='user_manage.php';</script>";
    }
}

$conn->close();
?>