<?php
session_start();
require_once '../config/connection.php';

// Cek Login
if (!isset($_SESSION['user_id'])) {
  header("Location: ../auth/login.php");
  exit;
}

$userId = $_SESSION['user_id'];
$success = "";
$error = "";

$queryUser = mysqli_query($conn, "SELECT * FROM users WHERE id = '$userId'");
$userData = mysqli_fetch_assoc($queryUser);

// handle submit
if (isset($_POST['update_profile'])) {
  $fullName = mysqli_real_escape_string($conn, $_POST['full_name']);

  $passLama = $_POST['password_lama'];
  $passBaru = $_POST['password_baru'];
  $passConfirm = $_POST['konfirmasi_password'];


  if (empty($fullName)) {
    // Validasi Nama
    $error = "Nama lengkap tidak boleh kosong.";
  } else {
    // Logic ganti password (sekalian nama)
    if (!empty($passLama) || !empty($passBaru)) {
      // validasi Password Lama
      if (!password_verify($passLama, $userData['password'])) {
        $error = "Password lama salah!";
      }

      // validasi Password Baru
      elseif ($passBaru !== $passConfirm) {
        $error = "Konfirmasi password baru tidak cocok!";
      } elseif (strlen($passBaru) < 6) {
        $error = "Password baru minimal 6 karakter.";
      } else {
        $newHash = password_hash($passBaru, PASSWORD_DEFAULT);

        // Update Nama DAN Password
        $updateQuery = "UPDATE users
                        SET full_name = '$fullName',
                            password = '$newHash'
                        WHERE id = '$userId'";
      }
    } else {
      // Logic hanya ganti nama
      $updateQuery = "UPDATE users
                      SET full_name = '$fullName'
                      WHERE id = '$userId'";
    }

    // Eksekusi Query jika tidak ada error sebelumnya
    if ($error == "") {
      if (mysqli_query($conn, $updateQuery)) {
        $success = "Profil berhasil diperbarui!";

        // refresh
        $queryUser = mysqli_query($conn, "SELECT * FROM users WHERE id = '$userId'");
        $userData = mysqli_fetch_assoc($queryUser);
      } else {
        $error = "Gagal update: " . mysqli_error($conn);
      }
    }
  }
}
?>

<?php
$page = "Profil Saya";
$path = "../";
include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container">
  <div class="auth-card profile-card">

    <h2 class="login-title profile-title">Profil Saya</h2>
    <p class="profile-subtitle">Kelola informasi akun Anda</p>

    <hr class="profile-divider">

    <?php if ($error): ?>
      <div class="login-error-ribbon"><?= $error ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="login-success-ribbon"><?= $success ?></div>
    <?php endif; ?>

    <form action="" method="POST">

      <div class="form-group">
        <label class="form-label">Username</label>
        <input
          type="text"
          class="form-control input-readonly"
          value="<?= $userData['username'] ?>"
          disabled>
        <small class="helper-text">
          Username tidak dapat diubah.
        </small>
      </div>

      <div class="form-group">
        <label class="form-label">Nama Lengkap</label>
        <input
          type="text"
          name="full_name"
          class="form-control"
          value="<?= htmlspecialchars($userData['full_name']) ?>"
          required>
      </div>

      <hr class="profile-divider dashed">

      <h4 class="section-title">
        Ganti Password (Opsional)
      </h4>
      <p class="section-desc">
        Biarkan kosong jika tidak ingin mengganti password.
      </p>

      <div class="form-group">
        <label class="form-label">Password Lama</label>
        <input
          type="password"
          name="password_lama"
          class="form-control"
          placeholder="Masukkan password saat ini">
      </div>

      <div class="form-grid-2">
        <div class="form-group">
          <label class="form-label">Password Baru</label>
          <input
            type="password"
            name="password_baru"
            class="form-control"
            placeholder="Password baru">
        </div>
        <div class="form-group">
          <label class="form-label">Ulangi Password Baru</label>
          <input
            type="password"
            name="konfirmasi_password"
            class="form-control"
            placeholder="Ketik ulang">
        </div>
      </div>

      <button
        type="submit"
        name="update_profile"
        class="btn btn-primary btn-full">
        Simpan Perubahan
      </button>

    </form>
  </div>
</div>

<?php include '../includes/footer.php'; ?>