<?php
session_start();
require_once '../config/connection.php';

// Cek Login
if (!isset($_SESSION['user_id'])) {
  header("Location: ../auth/login.php");
  exit;
}

$userId = $_SESSION['user_id'];
$error = "";

$isEdit = false;
$id = "";
$title = "";
$url = "";
$event_date = "";
$location = "";
$map_embed = "";
$description = "";
$oldImage = "";

// Edit mode
if (isset($_GET['id'])) {
  $id = mysqli_real_escape_string($conn, $_GET['id']);

  // Cek, pastikan punya user yang login, kalau bukan = redirect
  $query = "SELECT * FROM events WHERE id = '$id' AND user_id = '$userId'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    $data = mysqli_fetch_assoc($result);
    $isEdit = true;

    $title = $data['title'];
    $url = $data['url'];
    $event_date = $data['event_date'];
    $location = $data['location'];
    $map_embed = $data['map_embed'];
    $description = $data['description'];
    $oldImage = $data['event_image'];
  } else {
    header("Location: index.php");
    exit;
  }
}

if (isset($_POST['submit_event'])) {
  // cek kode map
  $rawMap = $_POST['map_embed'];
  $cleanMap = strip_tags($rawMap, '<iframe>');
  if (!empty($cleanMap)) {
    if (strpos($cleanMap, 'google.com/maps') === false && strpos($cleanMap, 'maps.google.com') === false) {
      $error = "Kode Map tidak valid! Harus berasal dari Google Maps.";
    }
  }

  $titleInput = mysqli_real_escape_string($conn, $_POST['title']);
  $urlInput = mysqli_real_escape_string($conn, $_POST['url']);
  $dateInput = mysqli_real_escape_string($conn, $_POST['event_date']);
  $locInput = mysqli_real_escape_string($conn, $_POST['location']);
  $mapInput = mysqli_real_escape_string($conn, $cleanMap);
  $descInput = mysqli_real_escape_string($conn, $_POST['description']);

  // Teknik Sticky Form (Supaya kalau error, text-nya tidak hilang.)
  $title = $titleInput;
  $url = $urlInput;
  $event_date = $dateInput;
  $location = $locInput;
  $map_embed = $mapInput;
  $description = $descInput;

  // Logic cek Custom URL
  $cekUrlQuery = "SELECT id FROM events WHERE url = '$urlInput'";
  if ($isEdit) {
    $cekUrlQuery .= " AND id != '$id'";
  }

  $check = mysqli_query($conn, $cekUrlQuery);

  // Validasi Karakter URL
  if (!preg_match('/^[a-z0-9-]+$/', $urlInput)) {
    $error = "URL hanya boleh huruf kecil, angka, dan tanda hubung (-). Contoh: romeo-juliet";
  } elseif (mysqli_num_rows($check) > 0) {
    $error = "URL '$urlInput' sudah digunakan orang lain";
  } else {
    // Logic Upload Gambar
    $imageName = $oldImage;  // kalau edit, pake gambar lama

    if (!empty($_FILES['event_image']['name'])) {
      $namaFile = $_FILES['event_image']['name'];
      $tmpName = $_FILES['event_image']['tmp_name'];
      $ekstensi = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

      if (in_array($ekstensi, ['jpg', 'jpeg', 'png']) && $_FILES['event_image']['size'] <= 2000000) {
        $newName = uniqid() . '.' . $ekstensi;

        // Logika hapus foto lama kalau ganti foto baru.
        if ($isEdit && !empty($oldImage)) {
          $pathFotoLama = '../uploads/' . $oldImage;

          // Hapus hanya kalau ada
          if (file_exists($pathFotoLama)) {
            unlink($pathFotoLama);
          }
        }

        move_uploaded_file($tmpName, '../uploads/' . $newName);
        $imageName = $newName;
      } else {
        $error = "Format gambar salah atau terlalu besar (Max 2MB)";
      }
    }

    // Lanjut Save kalau ga ada error
    if ($error == "") {
      if ($isEdit) {
        // Mode Edit
        $query = "UPDATE events SET 
                          title = '$titleInput',
                          url = '$urlInput',
                          event_date = '$dateInput',
                          location = '$locInput',
                          map_embed = '$mapInput',
                          description = '$descInput',
                          event_image = '$imageName'
                          WHERE id = '$id' AND user_id = '$userId'";
      } else {
        // Mode Create New
        $query = "INSERT INTO events (user_id, url, title, event_date, location, map_embed, description, event_image) 
                  VALUES ('$userId', '$urlInput', '$titleInput', '$dateInput', '$locInput', '$mapInput', '$descInput', '$imageName')";
      }

      if (mysqli_query($conn, $query)) {
        header("Location: index.php");
        exit;
      } else {
        $error = "Database Error: " . mysqli_error($conn);
      }
    }
  }
}
?>

<?php
$page = $isEdit ? "Edit Undangan" : "Buat Undangan";
$path = "../";
include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container-full dashboard">
  <div class="auth-card form-event">

    <div class="form-event-header">
      <h2 class="form-event-title">
        <?= $isEdit ? "Edit Undangan" : "Buat Undangan Baru" ?>
      </h2>
      <a href="index.php" class="btn btn-secondary form-event-back">&larr; Batal</a>
    </div>
    <hr class="form-event-separator">

    <?php if ($error): ?>
      <div class="login-error-ribbon"><?= $error ?></div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">

      <div class="form-group">
        <label class="form-label">Nama Acara<span class="text-danger">*</span></label>
        <input
          type="text"
          name="title"
          placeholder="cth: Ulang tahun fulan / Pernikahan fulan & fulan"
          class="form-control"
          value="<?= htmlspecialchars($title) ?>"
          required>
      </div>

      <div class="form-group">
        <label class="form-label">URL Undangan (Custom)</label>
        <div class="form-flex">
          <span class="form-event-url-prefix">
            undangin.com/invitation.php?url=
          </span>
          <input
            type="text"
            name="url"
            class="form-control form-event-url-input"
            value="<?= htmlspecialchars($url) ?>"
            required placeholder="romeo-juliet">
        </div>
        <small class="text-secondary text-xs">Gunakan huruf kecil dan tanda hubung (-). Jangan pakai spasi.</small>
      </div>

      <div class="form-flex">
        <div class="form-group form-flex-1">
          <label class="form-label">Tanggal & Waktu (WIB)<span class="text-danger">*</span></label>
          <input
            type="datetime-local"
            name="event_date"
            class="form-control"
            value="<?= $event_date ?>"
            required>
        </div>

        <div class="form-group form-flex-1 form-event-upload">
          <label class="form-label">Foto Sampul</label>
          <input
            type="file"
            name="event_image"
            class="form-control"
            accept="image/*">
          <?php if ($isEdit && $oldImage): ?>
            <small class="text-secondary">
              Foto sudah ada. Biarkan kosong jika tidak ingin mengganti.
            </small>
          <?php endif; ?>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Lokasi Acara</label>
        <textarea
          name="location"
          class="form-control"
          rows="3"
          required><?= htmlspecialchars($location) ?></textarea>
      </div>

      <div class="form-group">
        <label class="form-label">
          Google Maps Embed Code <span class="text-secondary">(Opsional)</span>
        </label>
        <textarea
          name="map_embed"
          class="form-control"
          rows="3"
          placeholder='Paste kode <iframe...> dari Google Maps disini'><?= $map_embed ?></textarea>
        <small class="text-secondary text-xs">
          Cara: Buka Google Maps -> Cari lokasi -> Klik "Bagikan" -> Pilih "Sematkan Peta" -> Salin HTML.
        </small>
      </div>

      <div class="form-group">
        <label class="form-label">Deskripsi</label>
        <textarea
          name="description"
          class="form-control"
          rows="4"><?= htmlspecialchars($description) ?></textarea>
      </div>

      <button
        type="submit"
        name="submit_event"
        class="btn btn-primary form-event-btn-save">
        <?= $isEdit ? "Update Perubahan" : "Simpan & Buat Undangan" ?>
      </button>

    </form>
  </div>
</div>

<?php include '../includes/footer.php'; ?>