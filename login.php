<?php
session_start();
include_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header('Location: dashboard.php');
            } else {
                header('Location: index.php');
            }
            exit;
        } else {
            $error_message = "Password salah.";
        }
    } else {
        $error_message = "Username tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        }
        .login-container {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            width: 60%;
            max-width: 1000px;
            overflow: hidden;
        }
        .login-image {
            flex: 1.2;
            background-color: #D4A762;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-image img {
            max-width: 100%;
            height: auto;
        }
        .login-form {
            flex: 1;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .login-form h1 {
            margin-bottom: 20px;
            font-size: 2.5rem;
            color: #050709;
        }
        .login-form input[type="text"],
        .login-form input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #D4A762;
            border-radius: 8px;
            font-size: 1rem;
        }
        .login-form button {
            background: #D4A762;
            color: #fff;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .login-form button:hover {
            background: #b78a4e;
        }
        .error-message {
            color: red;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }
        .register-link {
            text-align: center;
            margin-top: 15px;
            font-size: 0.9rem;
        }
        .register-link a {
            color: #D4A762;
            text-decoration: none;
            font-weight: bold;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
        .password-container {
            position: relative;
            width: 100%;
        }
        .password-container .eye-icon {
    position: absolute;
    right: 2px;
    top: 40%; /* Adjusted from 50% to 40% */
    transform: translateY(-50%);
    cursor: pointer;
    font-size: 1.2rem;
}
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-image">
            <img src="Cooking.gif" alt="Login Animation">
        </div>
        <div class="login-form">
            <h1>Login</h1>
            <?php if (isset($error_message)): ?>
                <p class="error-message"> <?php echo $error_message; ?> </p>
            <?php endif; ?>
            <form action="" method="POST">
                <input type="text" name="username" placeholder="Username" required>
                
                <div class="password-container">
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <i class="fas fa-eye eye-icon" id="togglePassword"></i>
                </div>
                
                <button type="submit">Login</button>
            </form>
            <div class="register-link">
                Belum punya akun? <a href="register.php">Daftar di sini</a>
            </div>
        </div>
    </div>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            // Toggle the password visibility
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;

            // Toggle the eye icon
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>