<?php
session_start();
require_once '../config/connection.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: ../auth/login.php");
  exit;
}

// Data dari session
$userId = $_SESSION['user_id'];
$fullname = $_SESSION['full_name'];

// Data event current user
$query = "SELECT *
          FROM events
          WHERE user_id = '$userId'
          ORDER BY event_date DESC";

$result = mysqli_query($conn, $query);
?>

<?php
$page = "Dashboard";
$path = "../";
include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container-full dashboard">

  <div class="event-grid">

    <?php if (mysqli_num_rows($result) > 0): ?>

      <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="event-card">
          <div class="event-header">
            <h3 class="event-title">
              <?= htmlspecialchars($row['title']) ?>
            </h3>
            <span class="badge">Aktif</span>
          </div>

          <div class="event-date">
            📅 <?= date('d M Y', strtotime($row['event_date'])) ?>
          </div>

          <p class="event-link">
            🔗 undangin.com/invitation.php?url=<?= $row['url'] ?>
          </p>

          <div class="card-actions">
            <div>
              <a
                href="../invitation.php?url=<?= $row['url'] ?>"
                target="_blank" class="btn btn-secondary event-btn-card">
                Lihat
              </a>
            </div>
            <div>
            <a
              href="form_event.php?id=<?= $row['id'] ?>"
              class="btn btn-primary event-btn-card">
              Edit
            </a>
            </div>
            <div>
            <a
              href="delete_event.php?id=<?= $row['id'] ?>"
              onclick="return confirm('Yakin hapus acara ini?')"
              class="btn btn-danger-outline event-btn-card">
              Hapus
            </a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>

    <?php else: ?>

      <div class="empty-card">
        <h3 class="empty-card-title">Belum ada acara</h3>
        <p>Kamu belum membuat undangan apapun.</p>
        <a href="form_event.php" class="btn btn-primary">Buat Undangan Pertama</a>
      </div>

    <?php endif; ?>

  </div>
</div>

<?php include '../includes/footer.php'; ?>