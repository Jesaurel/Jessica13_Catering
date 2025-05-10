<?php
require 'db.php'; // Pastikan ada koneksi ke database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $review_id = $_POST['review_id'];
    $review_text = $_POST['review'];
    $rating = $_POST['rating'];

    if (!empty($review_id) && !empty($review_text) && !empty($rating)) {
        $query = "UPDATE reviews SET review = ?, rating = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sii", $review_text, $rating, $review_id);

        if ($stmt->execute()) {
            echo "<script>alert('Review berhasil diperbarui!'); window.location.href='profile.php';</script>";
        } else {
            echo "<script>alert('Gagal memperbarui review!');</script>";
        }
    }
}
?>