<?php
session_start();
include_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password']; // Plain text password
    $confirm_password = $_POST['confirm_password']; // Confirm password
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Validasi password
    if (strlen($password) < 6) {
        $error_message = "Password harus memiliki minimal 6 karakter.";
    } elseif (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/\d/', $password)) {
        $error_message = "Password harus mengandung huruf besar, huruf kecil, dan angka.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Konfirmasi password tidak cocok.";
    } else {
        // Cek apakah username sudah ada
        $query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $error_message = "Username atau email sudah terdaftar.";
        } else {
            // Menyimpan data ke database
            $hashed_password = $password; // Tanpa hashing, sesuai permintaan
            $insert_query = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";
            if (mysqli_query($conn, $insert_query)) {
                $_SESSION['user_id'] = mysqli_insert_id($conn);
                $_SESSION['username'] = $username;
                $_SESSION['role'] = 'user';
                header('Location: index.html');
                exit;
            } else {
                $error_message = "Gagal mendaftar, coba lagi.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #FFFCF8;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }
        .register-container {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            overflow: hidden;
            width: 900px;
            max-width: 100%;
        }
        .register-image {
            flex: 1;
            background-color: #D4A762;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .register-image img {
            max-width: 80%;
            height: auto;
        }
        .register-form {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .register-form h1 {
            margin-bottom: 20px;
            font-size: 2rem;
            color: #050709;
        }
        .register-form input[type="text"],
        .register-form input[type="password"],
        .register-form input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #D4A762;
            border-radius: 5px;
            font-size: 1rem;
            color: #9A9A9A;
        }
        .register-form button {
            background: #D4A762;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.3s ease;
        }
        .register-form button:hover {
            background: #B78A4E;
            transform: scale(1.05);
        }
        .error-message {
            color: red;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }
        .login-link {
            color: #D4A762;
            text-decoration: none;
        }
        .login-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-image">
            <img src="Cooking.gif" alt="Register Animation">
        </div>
        <div class="register-form">
            <h1>Register</h1>
            <?php if (isset($error_message)): ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <form action="" method="POST" id="registerForm">
                <input type="text" name="username" placeholder="Username" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password (min. 6 karakter)" required>
                <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required>
                <p>Sudah punya akun? <a href="login.php" class="login-link">Login di sini</a></p>
                <button type="submit" id="registerButton">Daftar</button>
            </form>
        </div>
    </div>
</body>
</html>