<?php
session_start();
require_once '../config/connection.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: ../auth/login.php");
  exit;
}

// Cek datam kalau tidak ditemukan atau bukan milik user ini, lempar ke dashboard
if (isset($_GET['id'])) {
  $id = mysqli_real_escape_string($conn, $_GET['id']);
  $userId = $_SESSION['user_id'];

  $querySelect = "SELECT event_image
                  FROM events
                  WHERE id = '$id' AND user_id = '$userId'";

  $result = mysqli_query($conn, $querySelect);

  if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $foto = $row['event_image'];

    // hapus file foto (kalau ada)
    if (!empty($foto)) {
      $pathFoto = '../uploads/' . $foto;
      if (file_exists($pathFoto)) {
        unlink($pathFoto);
      }
    }

    $queryDelete = "DELETE FROM events
                    WHERE id = '$id' AND user_id = '$userId'";

    if (mysqli_query($conn, $queryDelete)) {
      header("Location: index.php");
    } else {
      echo "Gagal menghapus: " . mysqli_error($conn);
    }
  } else {
    header("Location: index.php");
  }
} else {
  header("Location: index.php");
}
