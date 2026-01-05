<?php
require_once 'config/connection.php';

// Param URL
$url = isset($_GET['url']) ? mysqli_real_escape_string($conn, $_GET['url']) : '';
$guestName = isset($_GET['to']) ? htmlspecialchars($_GET['to']) : 'Tamu Undangan';
$guestNameExist = $guestName && $guestName !== 'Tamu Undangan';

// Cek DB
$query = "SELECT * FROM events WHERE url = '$url'";
$result = mysqli_query($conn, $query);

// Jika not found
if (mysqli_num_rows($result) === 0) {
  echo "<h1 class='not-found-title'>Maaf, Undangan tidak ditemukan :(</h1>";
  exit;
}

$event = mysqli_fetch_assoc($result);

// handle kirim ucapan
if (isset($_POST['kirim_ucapan'])) {
  $nama = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['guest_name']));
  $pesan = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['message']));
  $status = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['attendance']));
  $eventId = $event['id'];

  // validate
  if (!empty($nama) && !empty($pesan)) {
    $insert = mysqli_query(
      $conn,
      "INSERT INTO wishes (event_id, guest_name, message, attendance)
        VALUES ('$eventId', '$nama', '$pesan', '$status')"
    );

    // Refresh supaya update
    if ($insert) {
      header("Location: invitation.php?url=" . $event['url'] . "&to=" . urlencode($nama) . "#wishes-section");

      exit;
    }
  }
}

// data ucapan
$wishesQuery = mysqli_query(
  $conn,
  "SELECT * FROM wishes
    WHERE event_id = '{$event['id']}'
    ORDER BY created_at DESC"
);
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" href="assets/images/undangin-w.png">
  <title>UndangIn: <?= htmlspecialchars($event['title']) ?></title>
  <link rel="stylesheet" href="assets/css/invitation.css">
  <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
  <header
    class="invitation-cover"
    style="background-image: url('uploads/<?= $event['event_image'] ?>');">
    <div class="overlay">
      <p class="greeting">Kepada Yth. Bapak/Ibu/Saudara/i</p>
      <h2 class="guest-name"><?= $guestName ?></h2>
      <p class="greeting-sub">Kami mengundang Anda untuk hadir di acara:</p>

      <h1 class="title"><?= htmlspecialchars($event['title']) ?></h1>

      <div class="date-badge">
        <?= date('d F Y', strtotime($event['event_date'])) ?>
      </div>

      <button
        onclick="handleBukaUndangan()"
        class="btn-open">
        Buka Undangan
      </button>
    </div>
  </header>

  <main class="invitation-content" id="isiUndangan">

    <?php if ($event['description']): ?>
      <section class="section-text">
        <p class="opening-text">Dengan penuh rasa syukur kepada Tuhan Yang Maha Esa</p>
        <p class="desc"><?= nl2br(htmlspecialchars($event['description'])) ?></p>
      </section>
    <?php endif; ?>

    <section class="section-countdown">
      <div
        id="countdown"
        class="countdown-wrapper"
        data-date="<?= $event['event_date'] ?>">
        <div class="cd-box">
          <span id="days">00</span>
          <small>Hari</small>
        </div>
        <div class="cd-box">
          <span id="hours">00</span>
          <small>Jam</small>
        </div>
        <div class="cd-box">
          <span id="minutes">00</span>
          <small>Menit</small>
        </div>
        <div class="cd-box">
          <span id="seconds">00</span>
          <small>Detik</small>
        </div>
      </div>
    </section>

    <section class="section-detail">
      <h3>Waktu & Tempat</h3>
      <p>
        <strong>📅 Tanggal:</strong><br>
        <?= date('l, d F Y', strtotime($event['event_date'])) ?>
      </p>
      <p>
        <strong>⏰ Pukul:</strong><br>
        <?= date('H:i', strtotime($event['event_date'])) ?> WIB
      </p>
      <p>
        <strong>📍 Lokasi:</strong><br>
        <?= nl2br(htmlspecialchars($event['location'])) ?>
      </p>
      <?php if (!empty($event['map_embed'])): ?>
        <div class="map-wrapper">
          <h4 class="map-title">🗺️ Peta Lokasi</h4>
          <div class="map-container">
            <?= strip_tags($event['map_embed'], '<iframe>') ?>
          </div>
        </div>
      <?php endif; ?>

    </section>

    <section class="section-wishes" id="wishes-section">
      <h3>Kirim Ucapan & Doa</h3>

      <div class="wish-form-card">
        <form action="" method="POST">
          <div class="form-group">
            <input type="text"
              name="guest_name"
              class="form-control"
              placeholder="Nama Anda"
              value="<?= $guestNameExist ? htmlspecialchars($guestName) : '' ?>"
              required>
          </div>

          <div class=" form-group">
            <textarea name="message" class="form-control" rows="3"
              placeholder="Tuliskan ucapan & doa restu..." required></textarea>
          </div>

          <div class="form-group">
            <select name="attendance" class="form-control" required>
              <option value="">Konfirmasi Kehadiran</option>
              <option value="Hadir">Saya akan Hadir</option>
              <option value="Tidak Hadir">Maaf, Tidak Bisa Hadir</option>
              <option value="Masih Ragu">Masih Ragu-ragu</option>
            </select>
          </div>

          <button
            type="submit"
            name="kirim_ucapan"
            class="btn-kirim-ucapan">
            Kirim Ucapan
          </button>
        </form>
      </div>

      <div class="wishes-list">
        <h4><?= mysqli_num_rows($wishesQuery) ?> Ucapan Masuk</h4>

        <?php if (mysqli_num_rows($wishesQuery) > 0): ?>
          <div class="wishes-container">
            <?php while ($wish = mysqli_fetch_assoc($wishesQuery)): ?>
              <div class="wish-item">
                <div class="wish-header">
                  <strong><?= htmlspecialchars($wish['guest_name']) ?></strong>

                  <?php
                  $badgeColor = '#999'; // Default
                  if ($wish['attendance'] == 'Hadir') $badgeColor = '#16a34a'; // Hijau
                  elseif ($wish['attendance'] == 'Tidak Hadir') $badgeColor = '#dc2626'; // Merah
                  elseif ($wish['attendance'] == 'Masih Ragu') $badgeColor = '#ca8a04'; // Kuning
                  ?>
                  <span class="attendance-badge" style="background-color: <?= $badgeColor ?>;">
                    <?= $wish['attendance'] ?>
                  </span>
                </div>
                <p><?= nl2br(htmlspecialchars($wish['message'])) ?></p>
                <small class="wish-date"><?= date('d M Y H:i', strtotime($wish['created_at'])) ?></small>
              </div>
            <?php endwhile; ?>
          </div>
        <?php else: ?>
          <p class="no-wishes">Belum ada ucapan. Jadilah yang pertama!</p>
        <?php endif; ?>
      </div>
    </section>

    <footer class="invitation-footer">
      <small>Created with ❤️ by UndangIn</small>
    </footer>
  </main>

  <script src="assets/js/script.js"></script>
</body>

</html>