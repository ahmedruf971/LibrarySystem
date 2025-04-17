<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$result = $conn->query("
    SELECT l.loan_id, u.username, b.title, l.loan_date, l.return_date
    FROM loans l
    JOIN users u ON l.user_id = u.user_id
    JOIN books b ON l.book_id = b.book_id
    ORDER BY l.loan_date DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Loans</title>
  <style>
    body {
      background-color: #1a1a2e;
      color: #f0f0f0;
      font-family: 'Segoe UI', sans-serif;
      padding: 20px;
    }

    h1 {
      color: #fff;
      text-align: center;
    }

    table {
      width: 90%;
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

    .returned {
      color: #aaffaa;
      font-weight: bold;
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
  <h1>📚 Manage Loans</h1>

  <table>
    <tr>
      <th>Loan ID</th>
      <th>User</th>
      <th>Book</th>
      <th>Loan Date</th>
      <th>Return Date</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['loan_id']; ?></td>
        <td><?= htmlspecialchars($row['username']); ?></td>
        <td><?= htmlspecialchars($row['title']); ?></td>
        <td><?= $row['loan_date']; ?></td>
        <td>
          <?php if ($row['return_date']): ?>
            <span class="returned"><?= $row['return_date']; ?></span>
          <?php else: ?>
            Not Returned
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


