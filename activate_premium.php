<?php
session_start();
require_once "config.php";
if (!isset($_SESSION['user_id'])) die("Login required");

$id = (int)($_POST['id'] ?? 0);
$days = (int)($_POST['package'] ?? 0);

$prices = [
    7 => 300,
    14 => 500,
    30 => 900
];

if (!$id || !isset($prices[$days])) {
    die("Invalid data");
}

$price = $prices[$days];

/* CHECK USER + LISTING */
$check = $pdo->prepare("
SELECT l.id, u.balance
FROM listings l
JOIN users u ON u.id = l.user_id
WHERE l.id=? AND l.user_id=?
");
$check->execute([$id, $_SESSION['user_id']]);
$data = $check->fetch();

if (!$data) die("Not allowed");

if ($data['balance'] < $price) {
    die("Недоволен баланс");
}

/* CALCULATE DATE */
$until = date("Y-m-d", strtotime("+$days days"));

$pdo->beginTransaction();

/* DEDUCT BALANCE */
$pdo->prepare("
UPDATE users SET balance = balance - ?
WHERE id=?
")->execute([$price, $_SESSION['user_id']]);

/* UPDATE LISTING */
$pdo->prepare("
UPDATE listings SET
is_premium=1,
premium_until=?
WHERE id=?
")->execute([$until, $id]);

$pdo->commit();

header("Location: my_listings.php");
exit;
