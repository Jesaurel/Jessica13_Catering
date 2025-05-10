<?php
session_start();
require 'db.php'; // Pastikan file ini memiliki koneksi database yang benar

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil data pengguna dari database
$query = "SELECT username, email, password, profile_picture FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Konversi gambar dari BLOB ke base64 supaya bisa dipakai di <img>
$profile_picture = 'https://storage.googleapis.com/a1aa/image/Gc9m1HLOFBF-O1FmRDiJy6I--MhRp1zB-1opexjF4ZI.jpg'; // Default
if (!empty($user['profile_picture'])) {
    $profile_picture = 'data:image/jpeg;base64,' . base64_encode($user['profile_picture']);
}

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Cek apakah ada file gambar diupload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['size'] > 0) {
        $profile_picture_data = file_get_contents($_FILES['profile_picture']['tmp_name']);
    } else {
        // Kalau tidak upload baru, tetap gunakan yang lama
        $profile_picture_data = $user['profile_picture'];
    }

    // Update database tanpa hashing password
    if (!empty($password)) {
        $update_query = "UPDATE users SET username = ?, email = ?, password = ?, profile_picture = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssssi", $username, $email, $password, $profile_picture_data, $user_id);
    } else {
        $update_query = "UPDATE users SET username = ?, email = ?, profile_picture = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("sssi", $username, $email, $profile_picture_data, $user_id);
    }

    if ($stmt->execute()) {
        $_SESSION['success'] = "Profil berhasil diperbarui!";
        header("Location: profile.php");
        exit();
    } else {
        $_SESSION['error'] = "Gagal memperbarui profil.";
    }
}
?>



<html>

<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .sidebar {
            transition: transform 0.3s ease;
        }

        .sidebar-hidden {
            transform: translateX(-100%);
        }

        .wave {
            position: relative;
            width: 100%;
            height: 150px;
            background: url('img/gambar_di_pojok.png') no-repeat center center;
            background-size: cover;
            border-radius: 20px;
        }

        .wave::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 50px;
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0), rgba(255, 255, 255, 1));
            border-radius: 0 0 20px 20px;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="flex flex-col lg:flex-row min-h-screen">
        <!-- Sidebar -->
        <div class="bg-white w-64 lg:w-1/4 p-6 fixed lg:relative lg:transform-none sidebar sidebar-hidden lg:sidebar-visible h-full lg:h-auto" id="sidebar">
            <div class="flex items-center mb-6">
    <img alt="User profile picture" class="rounded-full w-12 h-12" height="50" src="<?php echo $profile_picture; ?>" width="50" />
    <div class="ml-4">
        <h2 class="text-lg font-semibold"><?php echo $user['username']; ?></h2>
    </div>
</div>
               
            <div class="mb-6">
                <ul class="space-y-4">
                    <li class="flex items-center text-green-500">
                        <a href="index.php" class="flex items-center">
                            <i class="fas fa-home mr-2"></i> Home
                        </a>
                    </li>
                    <li class="flex items-center text-green-500 bg-green-100 p-2 rounded-md">
                        <a href="profile.php" class="flex items-center">
                            <i class="fas fa-user mr-2"></i> Profile
                        </a>
                    </li>

                    <li class="flex items-center text-green-500 bg-green-100 p-2 rounded-md">
                    <a href="alamat.php" class="flex items-center">
                        <i class="fas fa-home mr-2"></i> Address
                    </li>
                </ul>
            </div>
            <div class="mt-6 wave"></div>
        </div>
        <!-- Main Content -->
        <div class="flex-1 p-6 lg:ml-64">
            <div class="flex justify-between items-center mb-6">
                <div class="hidden lg:flex items-center">
                    <a href="profile.php" class="flex items-center text-green-500 hover:underline">
                        <i class="fas fa-arrow-left mr-2"></i>
                        <span>Back to Profile</span>
                    </a>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="logout.php" class="flex items-center">
                        <i class="fas fa-sign-out-alt text-purple-500 cursor-pointer"></i>
                    </a>
                </div>

                <button class="lg:hidden text-green-500" id="menu-button">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-2xl font-semibold mb-6">Profile Settings</h2>
                <div class="border-b border-gray-200 mb-6">
                    <ul class="flex space-x-6 overflow-x-auto">
                        <li class="pb-2 border-b-2 border-green-500">Profile</li>
                    </ul>
                </div>
                <div>
                    <?php if (isset($_SESSION['success'])): ?>
                        <p class="text-green-600"><?php echo $_SESSION['success'];
                                                    unset($_SESSION['success']); ?></p>
                    <?php elseif (isset($_SESSION['error'])): ?>
                        <p class="text-red-600"><?php echo $_SESSION['error'];
                                                unset($_SESSION['error']); ?></p>
                    <?php endif; ?>
                    <h3 class="text-lg font-semibold mb-4">Your Photo</h3>
<div class="flex flex-col md:flex-row items-center mb-6">
    <img alt="Foto profil pengguna" 
         class="rounded-full" 
         style="width: 100px; height: 100px; border: 2px solid green; border-radius: 50%;" 
         src="<?php echo $profile_picture; ?>" />
    <div class="ml-4">
        <button type="button" class="text-green-500" onclick="document.getElementById('profile_picture_input').click()">Upload your photo</button>
        <p class="text-gray-500 text-sm">Photos help your friends easy to identify in the circle / Add Avatar image</p>
    </div>
</div>
<form action="" method="POST" enctype="multipart/form-data" style="display: none;" id="uploadForm">
    <input type="file" name="profile_picture" id="profile_picture_input" onchange="document.getElementById('uploadForm').submit()">
</form>

                    <h3 class="text-lg font-semibold mb-4">Personal Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-lg">
                            <h2 class="text-2xl font-semibold mb-4">Edit Profile</h2>
                            <?php if (isset($_SESSION['success'])): ?>
                                <p class="text-green-600"><?php echo $_SESSION['success'];
                                                            unset($_SESSION['success']); ?></p>
                            <?php elseif (isset($_SESSION['error'])): ?>
                                <p class="text-red-600"><?php echo $_SESSION['error'];
                                                        unset($_SESSION['error']); ?></p>
                            <?php endif; ?>
                            <form action="" method="POST">
                                <div class="mb-4">
                                    <label class="block text-gray-700">Username</label>
                                    <input type="text" name="username" class="w-full border border-gray-300 rounded-lg p-2" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700">Email</label>
                                    <input type="email" name="email" class="w-full border border-gray-300 rounded-lg p-2" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700">Password (Kosongkan jika tidak ingin mengubah)</label>
                                    <input type="password" name="password" class="w-full border border-gray-300 rounded-lg p-2">
                                </div>
                                <div class="flex justify-end space-x-4">
                                    <button type="submit" class="bg-green-500 text-white py-2 px-6 rounded-lg">Save</button>
                                    <a href="profile.php" class="bg-gray-300 text-gray-700 py-2 px-6 rounded-lg">Cancel</a>
                                </div>
                            </form>
                        </div
                            </div>
                    </div>
                </div>
            </div>
            <script>
                document.getElementById('menu-button').addEventListener('click', function() {
                    var sidebar = document.getElementById('sidebar');
                    sidebar.classList.toggle('sidebar-hidden');
                });
            </script>
</body>

</html>