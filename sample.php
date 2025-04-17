<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['loan_id'])) {
    $loan_id = intval($_POST['loan_id']);
    $return_date = date('Y-m-d');

    // Get the book ID from loan
    $stmt = $conn->prepare("SELECT book_id FROM loans WHERE loan_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $loan_id, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $book_id = $row['book_id'];

        // Update return_date in loans
        $update = $conn->prepare("UPDATE loans SET return_date = ? WHERE loan_id = ?");
        $update->bind_param("si", $return_date, $loan_id);
        $update->execute();

        // Increase available copies in books
        $conn->query("UPDATE books SET available_copies = available_copies + 1 WHERE book_id = $book_id");

        header("Location: my_loans.php?returned=1");
        exit();
    } else {
        echo "❌ Invalid loan.";
    }
} else {
    echo "❌ Invalid request.";
}
?>

