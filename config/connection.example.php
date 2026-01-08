<?php

$hostname = "localhost";
$username = "root";
$password = "";
$database = "db_undangin";

$conn = mysqli_connect($hostname, $username, $password, $database);

if (!$conn) {
  die("Koneksi gagal: " . mysqli_connect_error());
}

date_default_timezone_set('Asia/Jakarta');
