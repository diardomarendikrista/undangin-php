<?php
session_start();
require_once '../config/connection.php';

if (isset($_SESSION['user_id'])) {
  header("Location: ../dashboard/index.php");
  exit;
}

$error = "";

// handle login
if (isset($_POST['login'])) {
  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);

  $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");

  if (mysqli_num_rows($result) === 1) {
    // https://www.w3schools.com/php/func_mysqli_fetch_assoc.asp
    $row = mysqli_fetch_assoc($result);

    if (password_verify($password, $row['password'])) {
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $row['username'];
      $_SESSION['full_name'] = $row['full_name'];

      header("Location: ../dashboard/index.php");
      exit;
    }
  }

  $error = "Username atau Password salah!";
}
?>

<?php
$page = "Login";
$path = "../";

include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container">
  <div class="auth-card">
    <h2 class="login-title">Masuk</h2>

    <?php if ($error): ?>
      <div class="login-error-ribbon">
        <?= $error; ?>
      </div>
    <?php endif; ?>

    <form action="" method="POST">
      <div class="form-group">
        <label class="form-label">Username</label>
        <input
          type="text"
          name="username"
          class="form-control"
          required>
      </div>

      <div class="form-group">
        <label class="form-label">Password</label>
        <input
          type="password"
          name="password"
          class="form-control"
          required>
      </div>

      <button
        type="submit"
        name="login"
        class="btn btn-primary login-btn">
        Masuk
      </button>
    </form>

    <p class="login-register-link">
      Belum punya akun? <a href="register.php">Daftar</a>
    </p>
  </div>
</div>

<?php include '../includes/footer.php'; ?>