<?php
session_start();
require_once '../config/connection.php';

if (isset($_SESSION['user_id'])) {
  header("Location: ../dashboard/index.php");
  exit;
}

$error = "";
$success = "";

// handle register
if (isset($_POST['register'])) {
  // https://www.w3schools.com/php/func_mysqli_real_escape_string.asp
  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);
  $fullname = mysqli_real_escape_string($conn, $_POST['full_name']);

  // Cek cek duplikat username
  $check = mysqli_query($conn, "SELECT username FROM users WHERE username = '$username'");
  if (mysqli_num_rows($check) > 0) {
    $error = "Username sudah terpakai!";
  } elseif ($_POST['password'] !== $_POST['password_confirm']) {
    // cek password
    $error = "Password dan Konfirmasi Password harus sama!";
  } else {
    // Enkripsi PHP
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert ke DB
    $query = "INSERT INTO users (username, password, full_name)
              VALUES ('$username', '$hashed_password', '$fullname')";

    if (mysqli_query($conn, $query)) {
      $success = "Registrasi berhasil! Silakan login.";
    } else {
      $error = "Gagal daftar: " . mysqli_error($conn);
    }
  }
}
?>

<?php
$page = "Register";
$path = "../";

include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container">
  <div class="auth-card">
    <h2 class="login-title">Daftar Akun</h2>

    <?php if ($error): ?>
      <div class="login-error-ribbon">
        <?= $error; ?>
      </div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="login-success-ribbon">
        <?= $success; ?> <a href="login.php">Login disini</a>.
      </div>
    <?php endif; ?>

    <form action="" method="POST">
      <div class="form-group">
        <label class="form-label">Nama Lengkap</label>
        <input
          type="text"
          name="full_name"
          class="form-control"
          required placeholder="Contoh: Diardo Marendi">
      </div>

      <div class="form-group">
        <label class="form-label">Username</label>
        <input
          type="text"
          name="username"
          class="form-control"
          required
          placeholder="Buat login nanti">
      </div>

      <div class="form-group">
        <label class="form-label">Password</label>
        <input
          type="password"
          name="password"
          class="form-control"
          required>
      </div>

      <div class="form-group">
        <label class="form-label">Konfirmasi Password</label>
        <input
          type="password"
          name="password_confirm"
          class="form-control"
          required>
      </div>

      <button
        type="submit"
        name="register"
        class="btn btn-primary login-btn">
        Daftar Sekarang
      </button>
    </form>

    <p class="login-register-link">
      Sudah punya akun? <a href="login.php">Login</a>
    </p>
  </div>
</div>

<?php include '../includes/footer.php'; ?>