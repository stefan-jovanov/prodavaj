<?php
session_start();
require 'config.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION["user_id"];

$stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$id]);
$user = $stmt->fetch();

$myListings = $pdo->prepare("SELECT * FROM listings WHERE user_id=? ORDER BY id DESC");
$myListings->execute([$id]);
$myListings = $myListings->fetchAll();
?>
<h2>Добредојде, <?= htmlspecialchars($user["username"]) ?></h2>
<p>Твој баланс: <b><?= $user["balance"] ?> MKD</b></p>

<a href="create_listing.php">+ Објави нов оглас</a>

<h3>Твои огласи:</h3>
<?php foreach ($myListings as $l): ?>
    <div><?= htmlspecialchars($l["title"]) ?></div>
<?php endforeach; ?>
