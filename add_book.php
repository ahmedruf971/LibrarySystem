<?php
session_start();
include 'db_connect.php';

// Restrict access to admins only
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $isbn = trim($_POST['isbn']);
    $genre = trim($_POST['genre']);
    $available_copies = intval($_POST['available_copies']);

    $stmt = $conn->prepare("INSERT INTO books (title, author, isbn, genre, available_copies) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $title, $author, $isbn, $genre, $available_copies);

    if ($stmt->execute()) {
        $success = "✅ Book added successfully!";
    } else {
        $error = "❌ Error adding book. ISBN might already exist.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Book</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      background: linear-gradient(to right, #1c1c1c, #3a0057);
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      color: #fff;
    }

    .form-container {
      background-color: #2a0035;
      padding: 40px;
      border-radius: 12px;
      width: 400px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.6);
    }

    h2 {
      text-align: center;
      color: #e0d8ff;
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
    }

    .success {
      color: #aaffaa;
    }

    .error {
      color: #ff9999;
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>Add a New Book</h2>
    <form method="POST" action="">
      <input type="text" name="title" placeholder="Book Title" required>
      <input type="text" name="author" placeholder="Author" required>
      <input type="text" name="isbn" placeholder="ISBN" required>
      <input type="text" name="genre" placeholder="Genre">
      <input type="number" name="available_copies" placeholder="Available Copies" min="1" value="1" required>
      <button type="submit">Add Book</button>
    </form>

    <div class="message">
      <?php if ($success) echo "<div class='success'>$success</div>"; ?>
      <?php if ($error) echo "<div class='error'>$error</div>"; ?>
    </div>
  </div>
</body>
</html>

