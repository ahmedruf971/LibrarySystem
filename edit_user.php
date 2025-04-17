<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['user_id'])) {
    header("Location: manage_users.php");
    exit();
}

$user_id = $_GET['user_id'];
$result = $conn->query("SELECT * FROM users WHERE user_id = $user_id");

if ($result->num_rows !== 1) {
    echo "User not found.";
    exit();
}

$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET username=?, email=?, role=? WHERE user_id=?");
    $stmt->bind_param("sssi", $username, $email, $role, $user_id);
    $stmt->execute();

    header("Location: manage_users.php");
    exit();
}
?>

<form method="POST" style="padding: 40px; background: #1a1a2e; color: #fff; font-family: sans-serif;">
  <h2>Edit User</h2>
  <label>Username: <input type="text" name="username" value="<?php echo $user['username']; ?>" required></label><br><br>
  <label>Email: <input type="email" name="email" value="<?php echo $user['email']; ?>" required></label><br><br>
  <label>Role:
    <select name="role">
      <option value="user" <?php if ($user['role'] === 'user') echo 'selected'; ?>>User</option>
      <option value="admin" <?php if ($user['role'] === 'admin') echo 'selected'; ?>>Admin</option>
    </select>
  </label><br><br>
  <button type="submit">Update</button>
</form>
