<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = (float)($_POST['amount'] ?? 0);

    if ($amount > 0) {
        $stmt = $pdo->prepare("
            UPDATE users SET balance = balance + ?
            WHERE id = ?
        ");
        $stmt->execute([$amount, $_SESSION['user_id']]);

        $msg = "Балансот е успешно надополнет (+{$amount} MKD)";
    } else {
        $msg = "Невалиден износ";
    }
}

/* GET USER */
$u = $pdo->prepare("SELECT balance FROM users WHERE id=?");
$u->execute([$_SESSION['user_id']]);
$user = $u->fetch();
?>
<!DOCTYPE html>
<html lang="mk">
<head>
<meta charset="UTF-8">
<title>Надополни баланс</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<header class="header">
    <div class="logo">Prodavaj<span>.mk</span></div>
    <a href="index.php">⬅ Назад</a>
</header>

<div class="container">
    <h2>💳 Надополни баланс (DEMO)</h2>

    <p>Тековен баланс:  
        <strong><?= number_format($user['balance'],2) ?> MKD</strong>
    </p>

    <?php if($msg): ?>
        <p style="color:green; font-weight:600;"><?= $msg ?></p>
    <?php endif; ?>

    <form method="post" class="form" style="max-width:400px;">
        <label>Износ (MKD)</label>
        <input type="number" name="amount" required min="100" step="100">

        <button class="btn">Надополни</button>
    </form>

    <p style="margin-top:15px; font-size:13px; color:#777;">
        ⚠️ Ова е demo – нема вистинско плаќање
    </p>
</div>

</body>
</html>
