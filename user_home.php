<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

include 'header.php';
?>

<style>
.card-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 30px;
  justify-content: center;
  margin-top: 40px;
}

.card-button {
  position: relative;
  width: 200px;
  height: 180px;
  border-radius: 12px;
  overflow: hidden;
  background-color: var(--card-color);
  box-shadow: 0 6px 20px rgba(0,0,0,0.4);
  transition: transform 0.3s ease;
  cursor: pointer;
  text-decoration: none;
}

.card-button:hover {
  transform: scale(1.05);
}

.card-button .bg-image {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-size: cover;
  background-position: center;
  opacity: 0;
  transition: opacity 0.4s ease;
}

.card-button:hover .bg-image {
  opacity: 0.3;
}

.card-button .text {
  position: relative;
  z-index: 2;
  color: var(--text-color);
  font-size: 18px;
  font-weight: bold;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
}
</style>

<div style="padding: 40px 20px; text-align: center;">
  <h1 style="background: var(--header-bg); padding: 20px; color: white; border-radius: 10px;">
    📖 Welcome to Your Library
  </h1>

  <p style="font-size: 20px; margin-top: 30px;">
    Hello!, <strong><?= htmlspecialchars($username); ?></strong> What would you like to do today?
  </p>

  <div class="card-grid">
    <a href="browse_books.php" class="card-button">
      <div class="bg-image" style="background-image: url('images/browsebook.png');"></div>
      <div class="text">🔍 Browse Books</div>
    </a>

    <a href="my_loans.php" class="card-button">
      <div class="bg-image" style="background-image: url('images/loan.png');"></div>
      <div class="text">📄 My Loans</div>
    </a>

    <a href="reserve_book.php" class="card-button">
      <div class="bg-image" style="background-image: url('images/reserve.png');"></div>
      <div class="text">📥 Reserve Book</div>
    </a>

    <a href="my_reservations.php" class="card-button">
      <div class="bg-image" style="background-image: url('images/bookmark.png');"></div>
      <div class="text">📌 My Reservations</div>
    </a>
  </div>

  <div style="margin-top: 50px;">
    <a href="logout.php" style="text-decoration: none; color: #ccc; font-size: 14px;">🔓 Logout</a>
  </div>
</div>

<?php include 'footer.php'; ?>




