<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['user_id'])) die("Login required");

$id = (int)($_POST['id'] ?? 0);
if (!$id) die("Invalid ID");

/* LOAD LISTING */
$stmt = $pdo->prepare("
    SELECT images FROM listings
    WHERE id=? AND user_id=?
");
$stmt->execute([$id, $_SESSION['user_id']]);
$listing = $stmt->fetch();

if (!$listing) die("Not allowed");

$title = trim($_POST['title']);
$description = trim($_POST['description']);
$price = (float)$_POST['price'];
$currency = $_POST['currency'];
$city = $_POST['city'];

if (!$title || !$price || !$city) {
    die("Недостасуваат податоци");
}

/* HANDLE IMAGES */
$images = json_decode($listing['images'], true) ?: [];

if (!empty($_FILES['images']['name'][0])) {
    foreach ($_FILES['images']['tmp_name'] as $i=>$tmp) {
        if ($_FILES['images']['error'][$i] === 0) {
            $ext = strtolower(pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION));
            if (!in_array($ext,['jpg','jpeg','png','webp'])) continue;

            $name = time().'_'.rand(1000,9999).'.'.$ext;
            move_uploaded_file($tmp, "uploads/".$name);
            $images[] = $name;
        }
    }
}

/* UPDATE */
$upd = $pdo->prepare("
    UPDATE listings SET
    title=?, description=?, price=?, currency=?, city=?, images=?
    WHERE id=? AND user_id=?
");

$upd->execute([
    $title,
    $description,
    $price,
    $currency,
    $city,
    json_encode($images),
    $id,
    $_SESSION['user_id']
]);

header("Location: my_listings.php");
exit;
