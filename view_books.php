<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$result = $conn->query("SELECT * FROM books ORDER BY title");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View All Books</title>
  <style>
    body {
      background-color: #1a1a2e;
      color: #f0f0f0;
      font-family: 'Segoe UI', sans-serif;
      padding: 20px;
    }

    h1 {
      text-align: center;
      color: #fff;
    }

    .success-message {
      text-align: center;
      color: #aaffaa;
      font-weight: bold;
      margin-bottom: 15px;
    }

    table {
      width: 95%;
      margin: 20px auto;
      border-collapse: collapse;
      background-color: #2a2a40;
    }

    th, td {
      padding: 12px;
      border: 1px solid #555;
      text-align: center;
    }

    th {
      background-color: #3a0057;
    }

    .action-buttons {
      display: flex;
      justify-content: center;
      gap: 8px;
    }

    .action-buttons a {
      color: white;
      padding: 8px 14px;
      border-radius: 6px;
      text-decoration: none;
      font-size: 14px;
      font-weight: bold;
      display: inline-block;
      transition: background 0.2s ease;
    }

    .edit-btn {
      background-color: #5a00a0;
    }

    .edit-btn:hover {
      background-color: #7c00cc;
    }

    .delete-btn {
      background-color: #cc0033;
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
      transition: background 0.2s;
    }

    .back a:hover {
      background: #555;
      color: white;
    }
  </style>
</head>
<body>

  <h1>📖 All Books in Library</h1>

  <?php if (isset($_GET['deleted'])): ?>
    <div class="success-message">✅ Book deleted successfully!</div>
  <?php endif; ?>

  <table>
    <tr>
      <th>ID</th>
      <th>Title</th>
      <th>Author</th>
      <th>Genre</th>
      <th>ISBN</th>
      <th>Available Copies</th>
      <th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['book_id']; ?></td>
        <td><?= htmlspecialchars($row['title']); ?></td>
        <td><?= htmlspecialchars($row['author']); ?></td>
        <td><?= htmlspecialchars($row['genre']); ?></td>
        <td><?= htmlspecialchars($row['isbn']); ?></td>
        <td><?= $row['available_copies']; ?></td>
        <td>
          <div class="action-buttons">
            <a href="edit_book.php?id=<?= $row['book_id']; ?>" class="edit-btn">✏️ Edit</a>
            <a href="delete_book.php?id=<?= $row['book_id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this book?');">🗑️ Delete</a>
          </div>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>

  <div class="back">
    <p><a href="admin_home.php">← Back to Dashboard</a></p>
  </div>

</body>
</html>
