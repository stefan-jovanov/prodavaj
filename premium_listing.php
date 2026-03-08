<?php
session_start();
require_once "config.php";
if (!isset($_SESSION['user_id'])) die("Login required");

$id = (int)($_GET['id'] ?? 0);
if (!$id) die("Invalid ID");

/* LOAD LISTING */
$l = $pdo->prepare("SELECT * FROM listings WHERE id=? AND user_id=?");
$l->execute([$id, $_SESSION['user_id']]);
$listing = $l->fetch();

if (!$listing) die("Not allowed");

/* USER */
$u = $pdo->prepare("SELECT balance FROM users WHERE id=?");
$u->execute([$_SESSION['user_id']]);
$user = $u->fetch();
?>
<!DOCTYPE html>
<html lang="mk">
<head>
<meta charset="UTF-8">
<title>Premium оглас</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<h2>⭐ Направи Premium оглас</h2>

<p><b>Оглас:</b> <?= htmlspecialchars($listing['title']) ?></p>
<p><b>Баланс:</b> <?= $user['balance'] ?> MKD</p>

<form action="activate_premium.php" method="post" class="form">
<input type="hidden" name="id" value="<?= $listing['id'] ?>">

<label>Избери пакет</label>

<select name="package" required>
    <option value="">Одбери</option>
    <option value="7">7 дена – 300 MKD</option>
    <option value="14">14 дена – 500 MKD</option>
    <option value="30">30 дена – 900 MKD</option>
</select>

<button class="btn">⭐ Активирај Premium</button>
<a href="my_listings.php" class="btn" style="background:#6b7280;">Назад</a>
</form>

</body>
</html>
