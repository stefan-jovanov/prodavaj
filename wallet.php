<?php
require 'config.php';
require 'auth.php'; // makes sure user is logged in

$user_id = $_SESSION['user_id'];

if (isset($_POST['add_demo'])) {
    $pdo->prepare("UPDATE users SET balance = balance + 100 WHERE id = ?")
        ->execute([$user_id]);

    header("Location: wallet.php?added=1");
    exit;
}

$stmt = $pdo->prepare("SELECT balance FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$balance = $stmt->fetchColumn();
?>
<!DOCTYPE html>
<html>
<head>
<title>Wallet</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
<h2>💼 Паричник</h2>

<p><b>Баланс:</b> <?= number_format($balance,2) ?> ден.</p>

<form method="post">
    <button name="add_demo" type="submit" class="btn">+100 DEMO баланс</button>
</form>

<?php if (isset($_GET['added'])): ?>
<div class="notice">Додадени 100 демонстрациски поени!</div>
<?php endif; ?>

</div>

</body>
</html>
