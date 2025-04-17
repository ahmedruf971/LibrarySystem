<?php
session_start();
include 'db_connect.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role'] ?? '';

    $query = $conn->prepare("SELECT * FROM users WHERE username = ? AND role = ?");
    $query->bind_param("ss", $username, $role);
    $query->execute();
    $result = $query->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];

        header("Location: " . ($role === 'admin' ? "admin_home.php" : "user_home.php"));
        exit();
    } else {
        $error = "Invalid login credentials or role!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - Library System</title>
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

    .login-box {
      background-color: #2a0035;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.6);
      width: 320px;
    }

    .login-box h2 {
      margin-bottom: 25px;
      text-align: center;
      color: #f0eaff;
    }

    .login-box input[type="text"],
    .login-box input[type="password"],
    .login-box select {
      width: 100%;
      padding: 12px;
      margin-bottom: 20px;
      border: none;
      border-radius: 8px;
      background: #3e0058;
      color: #fff;
      font-size: 14px;
    }

    .login-box select {
      background: #3e0058;
      color: #fff;
      appearance: none;
    }

    .login-box button {
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

    .login-box button:hover {
      background: #8b3eff;
    }

    .error-message {
      color: #ff6b6b;
      margin-bottom: 15px;
      text-align: center;
    }

    .signup-link {
      text-align: center;
      margin-top: 20px;
      font-size: 14px;
    }

    .signup-link a {
      color: #aaaaff;
      text-decoration: none;
    }

    .signup-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="login-box">
    <h2>Login</h2>

    <?php if (!empty($error)): ?>
      <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>

      <select name="role" required>
        <option value="" disabled selected>Login as...</option>
        <option value="user">User</option>
        <option value="admin">Admin</option>
      </select>

      <button type="submit">Login</button>
    </form>

    <div class="signup-link">
      Don't have an account? <a href="register.php">Sign up here</a>
    </div>
  </div>
</body>
</html>








