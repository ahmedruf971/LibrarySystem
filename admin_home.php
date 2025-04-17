<?php
session_start();
include 'db_connect.php';

// Redirect if not logged in or not admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username']; // we set this during login
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #1a1a2e;
      color: #f0f0f0;
    }

    header {
      background-color: #3a0057;
      padding: 20px;
      text-align: center;
      font-size: 28px;
      color: #fff;
    }

    .container {
      padding: 40px;
      text-align: center;
    }

    .btn {
      display: inline-block;
      margin: 20px 10px;
      padding: 14px 24px;
      background-color: #5a00a0;
      color: white;
      text-decoration: none;
      border-radius: 8px;
      font-weight: bold;
      font-size: 16px;
      transition: background 0.3s ease;
    }

    .btn:hover {
      background-color: #7c00cc;
    }

    .welcome {
      font-size: 22px;
      margin-bottom: 30px;
    }

    .logout {
      position: absolute;
      top: 20px;
      right: 20px;
      color: #ddd;
      text-decoration: none;
      background: #444;
      padding: 8px 14px;
      border-radius: 6px;
      font-size: 14px;
    }

    .logout:hover {
      background: #666;
    }
  </style>
</head>
<body>

<header>
  📚 Admin Dashboard
</header>

<a class="logout" href="logout.php">Logout</a>

<div class="container">
  <div class="welcome">Welcome, Admin<strong><?php echo htmlspecialchars($username); ?></strong></div>

  <a href="add_book.php" class="btn">➕ Add New Book</a>
  <a href="view_books.php" class="btn">📖 View All Books</a>
  <a href="manage_users.php" class="btn">👥 Manage Users</a>
  <a href="manage_loans.php" class="btn">📚 Manage Loans</a>
</div>

</body>
</html>


