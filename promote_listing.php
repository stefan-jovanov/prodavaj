<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['user_id'])) {
    die("Login required");
}

if (!isset($_GET['id'])) {
    die("Invalid listing");
}

$listing_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

/* CHECK LISTING */
$stmt = $pdo->prepare("SELECT * FROM listings WHERE id=? AND user_id=?");
$stmt->execute([$listing_id, $user_id]);
$listing = $stmt->fetch();

if (!$listing) {
    die("Listing not found");
}

/* GET USER BALANCE */
$user = $pdo->prepare("SELECT balance FROM users WHERE id=?");
$user->execute([$user_id]);
$userData = $user->fetch();

$premium_price = 500; // 500 MKD for premium

if ($userData['balance'] < $premium_price) {
    die("Немате доволно средства.");
}

/* DEDUCT BALANCE */
$pdo->prepare("UPDATE users SET balance = balance - ? WHERE id=?")
    ->execute([$premium_price, $user_id]);

/* SET PREMIUM FOR 7 DAYS */
$premium_until = date("Y-m-d", strtotime("+7 days"));

$pdo->prepare("
    UPDATE listings 
    SET is_premium=1, premium_until=? 
    WHERE id=?
")->execute([$premium_until, $listing_id]);

header("Location: my_listings.php");
exit;
