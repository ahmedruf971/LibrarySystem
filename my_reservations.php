<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$query = $conn->prepare("
    SELECT r.reserved_at, b.title, b.author, b.genre, b.available_copies
    FROM reservations r
    JOIN books b ON r.book_id = b.book_id
    WHERE r.user_id = ?
    ORDER BY r.reserved_at DESC
");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();

include 'header.php';
?>

<style>
  .reservations-container {
    padding: 30px 20px;
    text-align: center;
    flex: 1;
  }

  table {
    width: 90%;
    margin: 20px auto;
    border-collapse: collapse;
    background-color: var(--card-color);
    color: var(--text-color);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
  }

  th, td {
    padding: 12px;
    border: 1px solid #555;
    text-align: center;
  }

  th {
    background-color: var(--header-bg);
    color: #fff;
  }

  .available {
    color: #aaffaa;
    font-weight: bold;
  }

  .unavailable {
    color: #ff9999;
    font-style: italic;
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

<div class="reservations-container">
  <h1>📌 My Reserved Books</h1>

  <table>
    <tr>
      <th>Title</th>
      <th>Author</th>
      <th>Genre</th>
      <th>Reserved On</th>
      <th>Status</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['title']) ?></td>
        <td><?= htmlspecialchars($row['author']) ?></td>
        <td><?= htmlspecialchars($row['genre']) ?></td>
        <td><?= date('d M Y, H:i', strtotime($row['reserved_at'])) ?></td>
        <td>
          <?php if ($row['available_copies'] > 0): ?>
            <span class="available">Now Available</span>
          <?php else: ?>
            <span class="unavailable">Still Unavailable</span>
          <?php endif; ?>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>

  <div class="back">
    <p><a href="user_home.php">← Back to Dashboard</a></p>
  </div>
</div>

<?php include 'footer.php'; ?>
