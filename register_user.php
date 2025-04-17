<?php
session_start();
include 'db_connect.php';

// Allow only admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

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
            $success = "✅ User registered successfully!";
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
  <title>Register User - Admin</title>
  <style>
    body {
      background: linear-gradient(to right, #1c1c1c, #3a0057);
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      color: #fff;
    }

    .form-box {
      background-color: #2a0035;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.6);
      width: 360px;
    }

    h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #f0eaff;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 12px;
      margin-bottom: 18px;
      border: none;
      border-radius: 8px;
      background: #3e0058;
      color: #fff;
      font-size: 14px;
    }

    button {
      width: 100%;
      padding: 12px;
      background: #6c2aff;
      border: none;
      border-radius: 8px;
      color: white;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s ease;
    }

    button:hover {
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

    .back {
      text-align: center;
      margin-top: 15px;
    }

    .back a {
      color: #ccc;
      text-decoration: none;
      font-size: 14px;
    }

    .back a:hover {
      color: #fff;
    }
  </style>
</head>
<body>
  <div class="form-box">
    <h2>👤 Register New User</h2>

    <?php if ($success): ?>
      <div class="message success"><?= $success ?></div>
    <?php elseif ($error): ?>
      <div class="message error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
      <input type="text" name="username" placeholder="Username" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="password" name="confirm_password" placeholder="Confirm Password" required>
      <button type="submit">Register User</button>
    </form>

    <div class="back">
      <p><a href="manage_users.php">← Back to Manage Users</a></p>
    </div>
  </div>
</body>
</html>
