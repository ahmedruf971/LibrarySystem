<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid book ID.");
}

$book_id = intval($_GET['id']);
$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $genre = trim($_POST['genre']);
    $isbn = trim($_POST['isbn']);
    $copies = intval($_POST['available_copies']);

    $stmt = $conn->prepare("UPDATE books SET title = ?, author = ?, genre = ?, isbn = ?, available_copies = ? WHERE book_id = ?");
    $stmt->bind_param("ssssii", $title, $author, $genre, $isbn, $copies, $book_id);

    if ($stmt->execute()) {
        $message = "✅ Book updated successfully!";
    } else {
        $message = "❌ Failed to update book: " . $conn->error;
    }
}

// Fetch current book data
$stmt = $conn->prepare("SELECT * FROM books WHERE book_id = ?");
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();

if (!$book) {
    die("Book not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Book</title>
  <style>
    body {
      background: linear-gradient(to right, #1c1c1c, #3a0057);
      color: #fff;
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .edit-container {
      background-color: #2a0035;
      padding: 30px;
      border-radius: 12px;
      width: 400px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.5);
    }

    h2 {
      text-align: center;
      color: #e0d8ff;
      margin-bottom: 20px;
    }

    input {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border-radius: 6px;
      border: none;
      outline: none;
    }

    button {
      width: 100%;
      padding: 12px;
      background-color: #5a00a0;
      color: #fff;
      border: none;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    button:hover {
      background-color: #7c00cc;
    }

    .message {
      text-align: center;
      margin-top: 15px;
      font-size: 15px;
    }

    .success {
      color: #aaffaa;
    }

    .error {
      color: #ff9999;
    }

    .back {
      text-align: center;
      margin-top: 20px;
    }

    .back a {
      color: #ccc;
      text-decoration: none;
      background: #333;
      padding: 8px 16px;
      border-radius: 6px;
      font-size: 14px;
    }

    .back a:hover {
      background: #555;
      color: #fff;
    }
  </style>
</head>
<body>

  <div class="edit-container">
    <h2>✏️ Edit Book</h2>

    <form method="POST">
      <input type="text" name="title" placeholder="Title" value="<?= htmlspecialchars($book['title']) ?>" required>
      <input type="text" name="author" placeholder="Author" value="<?= htmlspecialchars($book['author']) ?>" required>
      <input type="text" name="genre" placeholder="Genre" value="<?= htmlspecialchars($book['genre']) ?>">
      <input type="text" name="isbn" placeholder="ISBN" value="<?= htmlspecialchars($book['isbn']) ?>" required>
      <input type="number" name="available_copies" placeholder="Available Copies" min="0" value="<?= $book['available_copies'] ?>" required>
      <button type="submit">💾 Save Changes</button>
    </form>

    <div class="message">
      <?= $message ?>
    </div>

    <div class="back">
      <a href="view_books.php">← Back to All Books</a>
    </div>
  </div>

</body>
</html>

