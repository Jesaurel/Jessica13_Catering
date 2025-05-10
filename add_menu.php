<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $category_id = mysqli_real_escape_string($conn, $_POST['category']);

    // Validasi dan proses upload gambar
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image']['tmp_name'];
        $imageData = addslashes(file_get_contents($image));

        $sql = "INSERT INTO menu (name, description, price, image, category_id) 
                VALUES ('$name', '$description', '$price', '$imageData', '$category_id')";

        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Menu berhasil ditambahkan!'); window.location='menu.php';</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "<script>alert('Gagal mengunggah gambar!');</script>";
    }
}

// Ambil daftar kategori dari database
$categoryQuery = "SELECT * FROM categories";
$categoryResult = mysqli_query($conn, $categoryQuery);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Menu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        input, textarea, select, button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        textarea {
            height: 80px;
            resize: none;
        }
        button {
            background-color: #28a745;
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin-top: 15px;
            border: none;
        }
        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Tambah Menu</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <label>Nama Menu:</label>
            <input type="text" name="name" required>

            <label>Deskripsi:</label>
            <textarea name="description" required></textarea>

            <label>Harga:</label>
            <input type="number" name="price" required>

            <label>Kategori:</label>
            <select name="category" required>
                <?php while ($row = mysqli_fetch_assoc($categoryResult)) { ?>
                    <option value="<?php echo $row['id']; ?>"> <?php echo $row['name']; ?> </option>
                <?php } ?>
            </select>

            <label>Gambar:</label>
            <input type="file" name="image" accept="image/*" required>

            <button type="submit">Tambah Menu</button>
        </form>
    </div>
</body>
</html>