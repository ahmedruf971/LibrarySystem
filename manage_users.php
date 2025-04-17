<?php
session_start();
include 'db_connect.php';

// Only admin can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$users = $conn->query("SELECT * FROM users ORDER BY username");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Users</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #1a1a2e;
      color: #f0f0f0;
      padding: 30px 20px;
    }

    h1 {
      text-align: center;
      color: #fff;
      margin-bottom: 20px;
    }

    .message {
      text-align: center;
      color: #aaffaa;
      font-weight: bold;
      margin-bottom: 15px;
    }

    .btn-add {
      display: block;
      text-align: center;
      margin-bottom: 20px;
    }

    .btn-add a {
      background-color: #5a00a0;
      color: white;
      padding: 10px 20px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: bold;
    }

    .btn-add a:hover {
      background-color: #7c00cc;
    }

    table {
      width: 90%;
      margin: auto;
      border-collapse: collapse;
      background-color: #2a2a40;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
    }

    th, td {
      padding: 12px;
      border: 1px solid #555;
      text-align: center;
    }

    th {
      background-color: #3a0057;
      color: white;
    }

    .delete-btn {
      background-color: #cc0033;
      color: white;
      padding: 6px 14px;
      border: none;
      border-radius: 6px;
      font-size: 14px;
      cursor: pointer;
      text-decoration: none;
    }

    .delete-btn:hover {
      background-color: #e6004c;
    }

    .back {
      text-align: center;
      margin-top: 30px;
    }

    .back a {
      text-decoration: none;
      color: #ccc;
      background: #333;
      padding: 10px 20px;
      border-radius: 8px;
    }

    .back a:hover {
      background: #555;
      color: white;
    }
  </style>
</head>
<body>

  <h1>👥 Manage Users</h1>

  <?php if (isset($_GET['deleted'])): ?>
    <div class="message">✅ User deleted successfully!</div>
  <?php endif; ?>

  <div class="btn-add">
    <a href="register_user.php">➕ Register New User</a>
  </div>

  <table>
    <tr>
      <th>User ID</th>
      <th>Username</th>
      <th>Email</th>
      <th>Role</th>
      <th>Action</th>
    </tr>
    <?php while ($user = $users->fetch_assoc()): ?>
      <tr>
        <td><?= $user['user_id']; ?></td>
        <td><?= htmlspecialchars($user['username']); ?></td>
        <td><?= htmlspecialchars($user['email']); ?></td>
        <td><?= $user['role']; ?></td>
        <td>
          <?php if ($user['user_id'] != $_SESSION['user_id']): ?>
            <a class="delete-btn" href="delete_user.php?id=<?= $user['user_id']; ?>" onclick="return confirm('Are you sure you want to delete this user?');">🗑️ Delete</a>
          <?php else: ?>
            👑 Admin
          <?php endif; ?>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>

  <div class="back">
    <p><a href="admin_home.php">← Back to Dashboard</a></p>
  </div>

</body>
</html>
