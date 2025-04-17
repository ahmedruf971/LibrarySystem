<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $user_id = intval($_GET['id']);

    // Prevent deleting yourself
    if ($user_id == $_SESSION['user_id']) {
        exit("❌ You cannot delete yourself.");
    }

    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    header("Location: manage_users.php?deleted=1");
    exit();
} else {
    echo "❌ Invalid user ID.";
}
?>
