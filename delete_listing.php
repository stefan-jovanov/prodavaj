<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['user_id'])) {
    die("Login required");
}

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    die("Invalid ID");
}

/* CHECK OWNERSHIP */
$stmt = $pdo->prepare("SELECT images FROM listings WHERE id=? AND user_id=?");
$stmt->execute([$id, $_SESSION['user_id']]);
$listing = $stmt->fetch();

if (!$listing) {
    die("Not allowed");
}

/* DELETE IMAGES */
$imgs = json_decode($listing['images'], true);
if ($imgs) {
    foreach ($imgs as $img) {
        $path = "uploads/".$img;
        if (file_exists($path)) {
            unlink($path);
        }
    }
}

/* DELETE LISTING */
$del = $pdo->prepare("DELETE FROM listings WHERE id=? AND user_id=?");
$del->execute([$id, $_SESSION['user_id']]);

header("Location: my_listings.php");
exit;
