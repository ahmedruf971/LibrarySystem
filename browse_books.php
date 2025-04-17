<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$search = '';
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    $stmt = $conn->prepare("SELECT * FROM books WHERE available_copies > 0 AND (title LIKE ? OR author LIKE ? OR genre LIKE ?) ORDER BY title");
    $like = "%" . $search . "%";
    $stmt->bind_param("sss", $like, $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM books WHERE available_copies > 0 ORDER BY title");
}

include 'header.php';
?>

<style>
  .browse-container {
    padding: 30px 20px;
    text-align: center;
    flex: 1;
  }

  form.search-form {
    margin-bottom: 30px;
  }

  input[type="text"] {
    padding: 10px;
    width: 280px;
    border-radius: 8px;
    border: 1px solid #999;
    font-size: 16px;
  }

  button.search-btn {
    padding: 10px 16px;
    margin-left: 10px;
    background-color: var(--accent);
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
  }

  button.search-btn:hover {
    background-color: var(--hover);
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

  .btn {
    background-color: var(--accent);
    color: white;
    padding: 8px 14px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
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

<div class="browse-container">
  <h1>📚 Browse Available Books</h1>

  <form class="search-form" method="GET" action="browse_books.php">
    <input type="text" name="search" placeholder="Search by title, author, or genre..." value="<?= htmlspecialchars($search); ?>">
    <button type="submit" class="search-btn">🔍 Search</button>
  </form>

  <table>
    <tr>
      <th>Title</th>
      <th>Author</th>
      <th>Genre</th>
      <th>Available Copies</th>
      <th>Action</th>
    </tr>
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['title']); ?></td>
          <td><?= htmlspecialchars($row['author']); ?></td>
          <td><?= htmlspecialchars($row['genre']); ?></td>
          <td><?= $row['available_copies']; ?></td>
          <td>
            <form action="request_loan.php" method="POST">
              <input type="hidden" name="book_id" value="<?= $row['book_id']; ?>">
              <button class="btn" type="submit">📥 Add to My Loans</button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="5">No books found matching your search.</td></tr>
    <?php endif; ?>
  </table>

  <div class="back">
    <p><a href="user_home.php">← Back to Dashboard</a></p>
  </div>
</div>

<?php include 'footer.php'; ?>


