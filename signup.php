<?php
session_start();
include 'db_connect.php';

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Check if user already exists
    $check = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $check->bind_param("ss", $username, $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $error = "Username or email already exists.";
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $role = 'user';

        $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $password_hash, $role);

        if ($stmt->execute()) {
            $success = "Registration successful! You can now <a href='login.php'>login</a>.";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sign Up</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #1c1c1c, #3a0057);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      color: #fff;
    }

    .signup-box {
      background-color: #2a0035;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.6);
      width: 370px;
      text-align: center;
    }

    h2 {
      margin-bottom: 20px;
      color: #e0d8ff;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
      width: 90%;
      padding: 10px;
      margin: 10px 0;
      border: none;
      border-radius: 6px;
      outline: none;
    }

    button {
      background-color: #5a00a0;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: background 0.3s ease;
      width: 100%;
      font-weight: bold;
    }

    button:hover {
      background-color: #7c00cc;
    }

    .error {
      color: #ff7b7b;
      margin-top: 10px;
    }

    .success {
      color: #aaffaa;
      margin-top: 10px;
    }

    .login-link {
      margin-top: 20px;
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
  <div class="signup-box">
    <h2>Create an Account</h2>
    <form method="POST" action="">
      <input type="text" name="username" placeholder="Username" required><br>
      <input type="email" name="email" placeholder="Email" required><br>
      <input type="password" name="password" placeholder="Password" required><br>
      <button type="submit">Sign Up</button>
    </form>
    <?php if ($error): ?>
      <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
      <div class="success"><?php echo $success; ?></div>
    <?php endif; ?>
    <div class="login-link">
      Already have an account? <a href="login.php">Login here</a>
    </div>
  </div>
</body>
</html>
