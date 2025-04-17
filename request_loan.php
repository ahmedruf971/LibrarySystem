<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id'])) {
    $book_id = intval($_POST['book_id']);
    $user_id = $_SESSION['user_id'];
    $loan_date = date('Y-m-d');
    $due_date = date('Y-m-d', strtotime('+14 days'));

    // Check if the book is available
    $check = $conn->prepare("SELECT available_copies FROM books WHERE book_id = ?");
    $check->bind_param("i", $book_id);
    $check->execute();
    $check_result = $check->get_result();
    $book = $check_result->fetch_assoc();

    if ($book && $book['available_copies'] > 0) {
        // Insert loan
        $stmt = $conn->prepare("INSERT INTO loans (user_id, book_id, loan_date, due_date) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $user_id, $book_id, $loan_date, $due_date);

        if ($stmt->execute()) {
            // Reduce book count
            $conn->query("UPDATE books SET available_copies = available_copies - 1 WHERE book_id = $book_id");
            header("Location: my_loans.php?success=1");
            exit();
        } else {
            echo "❌ Failed to borrow book.";
        }
    } else {
        echo "❌ Book not available.";
    }
} else {
    echo "❌ Invalid request.";
}
?>

