<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$message = "";

// Reserve logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book_id'])) {
    $book_id = $_POST['book_id'];

    // Check if already reserved
    $check = $conn->prepare("SELECT * FROM reservations WHERE user_id = ? AND book_id = ?");
    $check->bind_param("ii", $user_id, $book_id);
    $check->execute();
    $check_result = $check->get_result();

    if ($check_result->num_rows > 0) {
        $message = "⚠️ You've already reserved this book.";
    } else {
        $stmt = $conn->prepare("INSERT INTO reservations (user_id, book_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $book_id);
        if ($stmt->execute()) {
            $message = "✅ Book reserved successfully!";
        } else {
            $message = "❌ Failed to reserve the book.";
        }
    }
}

// Get books with 0 available copies
$books = $conn->query("SELECT * FROM books WHERE available_copies = 0");

include 'header.php';
?>

<style>
table {
  width: 90%;
  margin: 0 auto;
  border-collapse: collapse;
  background-color: var(--card-color);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
}

th, td {
  padding: 12px;
  text-align: center;
  border: 1px solid #555;
}

th {
  background-color: var(--header-bg);
  color: white;
}

h1 {
  text-align: center;
  margin: 30px 0;
}

.message {
  text-align: center;
  margin: 20px 0;
  font-weight: bold;
  color: #ffeb99;
}

.btn {
  background-color: var(--accent);
  color: white;
  padding: 8px 14px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 14px;
  transition: background 0.3s ease;
}

.btn:hover {
  background-color: var(--hover);
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

<h1>📥 Reserve a Book</h1>

<?php if ($message): ?>
  <div class="message"> <?= $message ?> </div>
<?php endif; ?>

<table>
  <tr>
    <th>Title</th>
    <th>Author</th>
    <th>Genre</th>
    <th>Reserve</th>
  </tr>
  <?php while ($row = $books->fetch_assoc()): ?>
    <tr>
      <td><?= htmlspecialchars($row['title']) ?></td>
      <td><?= htmlspecialchars($row['author']) ?></td>
      <td><?= htmlspecialchars($row['genre']) ?></td>
      <td>
        <form method="POST" action="reserve_book.php">
          <input type="hidden" name="book_id" value="<?= $row['book_id'] ?>">
          <button class="btn" type="submit">📌 Reserve</button>
        </form>
      </td>
    </tr>
  <?php endwhile; ?>
</table>

<div class="back">
  <p><a href="user_home.php">← Back to Dashboard</a></p>
</div>

<?php include 'footer.php'; ?>

