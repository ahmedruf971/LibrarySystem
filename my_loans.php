<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$result = $conn->prepare("
    SELECT l.loan_id, b.title, b.author, l.loan_date, l.due_date, l.return_date, b.book_id
    FROM loans l
    JOIN books b ON l.book_id = b.book_id
    WHERE l.user_id = ?
    ORDER BY l.loan_date DESC
");
$result->bind_param("i", $user_id);
$result->execute();
$loans = $result->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Loans</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #1c1c1c, #3a0057);
      color: #fff;
      padding: 30px 20px;
      min-height: 100vh;
    }

    h1 {
      text-align: center;
      margin-bottom: 20px;
    }

    .message {
      text-align: center;
      color: #aaffaa;
      font-weight: bold;
      margin-bottom: 15px;
    }

    table {
      width: 90%;
      margin: auto;
      border-collapse: collapse;
      background-color: #2a2a40;
      box-shadow: 0 4px 12px rgba(0,0,0,0.5);
    }

    th, td {
      padding: 12px;
      border: 1px solid #555;
      text-align: center;
    }

    th {
      background-color: #3a0057;
    }

    .btn {
      background-color: #5a00a0;
      color: white;
      padding: 8px 14px;
      border: none;
      border-radius: 6px;
      font-size: 14px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .btn:hover {
      background-color: #7c00cc;
    }

    .overdue {
      color: #ff7070;
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

  <h1>📚 My Borrowed Books</h1>

  <?php if (isset($_GET['returned'])): ?>
    <div class="message">✅ Book returned successfully!</div>
  <?php endif; ?>

  <table>
    <tr>
      <th>Title</th>
      <th>Author</th>
      <th>Loan Date</th>
      <th>Due Date</th>
      <th>Status</th>
      <th>Action</th>
    </tr>

    <?php if ($loans->num_rows > 0): ?>
      <?php while ($loan = $loans->fetch_assoc()): ?>
        <?php
          $due = $loan['due_date'];
          $isOverdue = (!$loan['return_date'] && strtotime($due) < time());
        ?>
        <tr>
          <td><?= htmlspecialchars($loan['title']) ?></td>
          <td><?= htmlspecialchars($loan['author']) ?></td>
          <td><?= $loan['loan_date'] ?></td>
          <td><?= $loan['due_date'] ?></td>
          <td>
            <?php if ($loan['return_date']): ?>
              ✅ Returned
            <?php elseif ($isOverdue): ?>
              <span class="overdue">⚠️ Overdue</span>
            <?php else: ?>
              ⏳ On Loan
            <?php endif; ?>
          </td>
          <td>
            <?php if (!$loan['return_date']): ?>
              <form method="POST" action="return_book.php" style="margin: 0;">
                <input type="hidden" name="loan_id" value="<?= $loan['loan_id']; ?>">
                <button type="submit" class="btn">🔁 Return</button>
              </form>
            <?php else: ?>
              —
            <?php endif; ?>
          </td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="6">You haven't borrowed any books yet.</td></tr>
    <?php endif; ?>
  </table>

  <div class="back">
    <p><a href="user_home.php">← Back to Dashboard</a></p>
  </div>

</body>
</html>

