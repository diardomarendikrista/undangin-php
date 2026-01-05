<?php
$pageTitle = isset($page) ? $page : 'Home';
$basePath  = isset($path) ? $path : '';
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" href="<?= $basePath ?>assets/images/undangin-w.png">
  <title>UndangIn - <?= $pageTitle ?></title>
  <link rel="stylesheet" href="<?= $basePath ?>assets/css/style.css">
</head>

<body>