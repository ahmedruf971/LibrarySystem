<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $book_id = intval($_GET['id']);

    // Optional: check if book is on loan before deletion

    $stmt = $conn->prepare("DELETE FROM books WHERE book_id = ?");
    $stmt->bind_param("i", $book_id);

    if ($stmt->execute()) {
        header("Location: view_books.php?deleted=1");
        exit();
    } else {
        echo "❌ Failed to delete book.";
    }
} else {
    echo "❌ Invalid book ID.";
}
?>
