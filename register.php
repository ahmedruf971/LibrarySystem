<?php
session_start();
include 'db_connect.php';

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($password !== $confirm) {
        $error = "❌ Passwords do not match!";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, 'user')");
        $stmt->bind_param("sss", $username, $email, $hashed);

        if ($stmt->execute()) {
            $success = "✅ Account created! You can now log in.";
        } else {
            $error = "❌ Username or email already exists.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - Library System</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      background: linear-gradient(to right, #1c1c1c, #3a0057);
      font-family: 'Segoe UI', sans-serif;
      color: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .register-box {
      background-color: #2a0035;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.6);
      width: 360px;
    }

    .register-box h2 {
      margin-bottom: 25px;
      text-align: center;
      color: #f0eaff;
    }

    .register-box input[type="text"],
    .register-box input[type="email"],
    .register-box input[type="password"] {
      width: 100%;
      padding: 12px;
      margin-bottom: 18px;
      border: none;
      border-radius: 8px;
      background: #3e0058;
      color: #fff;
      font-size: 14px;
    }

    .register-box button {
      width: 100%;
      padding: 12px;
      background: #6c2aff;
      border: none;
      border-radius: 8px;
      color: white;
      font-size: 16px;
      cursor: pointer;
      transition: 0.3s ease;
    }

    .register-box button:hover {
      background: #8b3eff;
    }

    .message {
      text-align: center;
      font-size: 14px;
      margin-bottom: 10px;
    }

    .success {
      color: #aaffaa;
    }

    .error {
      color: #ff6b6b;
    }

    .login-link {
      text-align: center;
      margin-top: 18px;
      font-size: 14px;
    }

    .login-link a {
      color: #aaaaff;
      text-decoration: none;
    }

    .login-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="register-box">
    <h2>Register</h2>

    <?php if ($success): ?>
      <div class="message success"><?= $success; ?></div>
    <?php elseif ($error): ?>
      <div class="message error"><?= $error; ?></div>
    <?php endif; ?>

    <form method="POST">
      <input type="text" name="username" placeholder="Username" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="password" name="confirm_password" placeholder="Confirm Password" required>
      <button type="submit">Sign Up</button>
    </form>

    <div class="login-link">
      Already have an account? <a href="login.php">Login here</a>
    </div>
  </div>
</body>
</html>

