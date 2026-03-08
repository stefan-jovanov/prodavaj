<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['user_id'])) die("Login required");

$id = (int)($_GET['id'] ?? 0);
if (!$id) die("Invalid ID");

/* LOAD LISTING */
$stmt = $pdo->prepare("
    SELECT * FROM listings
    WHERE id=? AND user_id=?
");
$stmt->execute([$id, $_SESSION['user_id']]);
$l = $stmt->fetch();

if (!$l) die("Not allowed");

$imgs = json_decode($l['images'], true) ?: [];
?>
<!DOCTYPE html>
<html lang="mk">
<head>
<meta charset="UTF-8">
<title>Уреди оглас</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<h2>✏️ Уреди оглас</h2>

<form action="update_listing.php" method="post" enctype="multipart/form-data" class="form">
<input type="hidden" name="id" value="<?= $l['id'] ?>">

<label>Наслов</label>
<input type="text" name="title" value="<?= htmlspecialchars($l['title']) ?>" required>

<label>Опис</label>
<textarea name="description" required><?= htmlspecialchars($l['description']) ?></textarea>

<label>Цена</label>
<div class="row">
    <input type="number" name="price" value="<?= $l['price'] ?>" required>
    <select name="currency">
        <option <?= $l['currency']=='EUR'?'selected':'' ?>>EUR</option>
        <option <?= $l['currency']=='MKD'?'selected':'' ?>>MKD</option>
    </select>
</div>

<label>Град</label>
<select name="city" required>
<?php foreach(["Скопје","Битола","Тетово","Охрид","Прилеп"] as $c): ?>
    <option <?= $l['city']==$c?'selected':'' ?>><?= $c ?></option>
<?php endforeach; ?>
</select>

<label>Постоечки слики</label>
<div class="row">
<?php foreach($imgs as $img): ?>
    <img src="uploads/<?= $img ?>" style="width:120px;border-radius:8px;">
<?php endforeach; ?>
</div>

<label>Додај нови слики (опционално)</label>
<input type="file" name="images[]" multiple>

<button class="btn">💾 Зачувај промени</button>
<a href="my_listings.php" class="btn" style="background:#6b7280;">Откажи</a>
</form>

</body>
</html>
