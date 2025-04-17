<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

if (isset($_POST['loan_id']) && is_numeric($_POST['loan_id'])) {
    $loan_id = $_POST['loan_id'];

    // Set return_date to today
    $stmt = $conn->prepare("UPDATE loans SET return_date = NOW() WHERE loan_id = ? AND return_date IS NULL");
    $stmt->bind_param("i", $loan_id);
    $stmt->execute();

    // Get book_id from the loan
    $get_book = $conn->prepare("SELECT book_id FROM loans WHERE loan_id = ?");
    $get_book->bind_param("i", $loan_id);
    $get_book->execute();
    $result = $get_book->get_result();
    $book = $result->fetch_assoc();

    if ($book) {
        $book_id = $book['book_id'];
        // Increase available_copies
        $update_book = $conn->prepare("UPDATE books SET available_copies = available_copies + 1 WHERE book_id = ?");
        $update_book->bind_param("i", $book_id);
        $update_book->execute();
    }
}

header("Location: my_loans.php");
exit();
