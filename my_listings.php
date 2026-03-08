<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['user_id'])) {
    die("Login required");
}

$stmt = $pdo->prepare("SELECT * FROM listings WHERE user_id=? ORDER BY id DESC");
$stmt->execute([$_SESSION['user_id']]);
$listings = $stmt->fetchAll();

function e($s){ return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
?>

<!DOCTYPE html>
<html lang="mk">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Мои огласи</title>
<link rel="stylesheet" href="style.css">
<style>

/* GRID */
/* PAGE */
body{
    margin:0;
    background:#f4f6f9;
    font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial;
}

/* HEADER */
.dashboard-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:20px 30px;
    background:white;
    box-shadow:0 4px 12px rgba(0,0,0,0.05);
    flex-wrap:wrap;
    gap:10px;
}

.dashboard-header h2{
    margin:0;
}

.add-btn{
    background:#2563eb;
    color:white;
    padding:10px 16px;
    border-radius:8px;
    text-decoration:none;
    font-weight:600;
}

/* GRID */
.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
    gap:25px;
    justify-items: center;
        justify-content: center;
    padding:30px;
}

/* CARD */
.card{
    position:relative;
    background:#fff;
    border-radius:16px;
    overflow:hidden;
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
    transition:0.2s;
    display:flex;
    flex-direction:column;
}

.card:hover{
    transform:translateY(-5px);
}

/* IMAGE */
.card img{
    width:100%;
    height:200px;
    object-fit:cover;
}

/* CONTENT */
.card-content{
    padding:15px;
    flex:1;
    display:flex;
    flex-direction:column;
}

.meta{
    font-size:13px;
    color:#777;
    margin-bottom:6px;
}

.price{
    font-weight:bold;
    font-size:16px;
    margin-top:6px;
}

/* PREMIUM */
.premium-badge{
    position:absolute;
    top:10px;
    left:10px;
    background:gold;
    padding:6px 12px;
    border-radius:20px;
    font-size:12px;
    font-weight:bold;
}

/* ACTIONS */
.actions{
    margin-top:auto;
    display:flex;
    gap:8px;
    flex-wrap:wrap;
}

.btn-small{
    flex:1;
    padding:8px;
    border-radius:8px;
    text-align:center;
    text-decoration:none;
    font-size:13px;
    font-weight:500;
}

.promote{
    background:gold;
    color:black;
}

.delete{
    background:#ef4444;
    color:white;
}

.promoted-info{
    font-size:12px;
    color:green;
    margin-top:6px;
}

/* TABLET */
@media (max-width:900px){
    .grid{
        padding:20px;
        gap:20px;
    }
}

/* MOBILE */
@media (max-width:600px){

    .dashboard-header{
        padding:15px;
    }

    .grid{
        padding:15px;
        gap:15px;
    }

    .card img{
        height:180px;
    }

    .actions{
        flex-direction:column;
    }

    .btn-small{
        width:100%;
    }
}
</style>
</head>
<body>

<div class="dashboard-header">
    <h2>📦 Мои огласи</h2>
    <a href="create_listing.php" class="add-btn">+ Нов оглас</a>
</div>

<main class="grid">

<?php if ($listings): ?>
<?php foreach ($listings as $l): 
    $imgs = json_decode($l['images'], true);
    $img = $imgs[0] ?? null;
?>

<div class="card">

<?php if (!empty($l['is_premium']) && $l['premium_until'] >= date("Y-m-d")): ?>
    <div class="premium-badge">⭐ PREMIUM</div>
<?php endif; ?>

<?php if ($img && file_exists("uploads/".$img)): ?>
    <img src="uploads/<?= e($img) ?>">
<?php else: ?>
    <img src="no-image.png">
<?php endif; ?>

<div class="card-content">
    <div class="meta">
        <?= e($l['brand']) ?> • <?= e($l['city']) ?>
    </div>

    <strong><?= e($l['title']) ?></strong>

    <div class="price">
        <?= number_format($l['price'],2) ?> <?= e($l['currency']) ?>
    </div>

    <div class="actions">

        <?php if (!empty($l['is_premium']) && $l['premium_until'] >= date("Y-m-d")): ?>
            <div class="promoted-info">
                Активен до <?= e($l['premium_until']) ?>
            </div>
        <?php else: ?>
            <a href="promote_listing.php?id=<?= $l['id'] ?>" class="btn-small promote">
                ⭐ Промовирај
            </a>
        <?php endif; ?>

        <a href="delete_listing.php?id=<?= $l['id'] ?>" 
           onclick="return confirm('Сигурно сакате да избришете?')"
           class="btn-small delete">
           🗑 Избриши
        </a>

    </div>

</div>
</div>

<?php endforeach; ?>
<?php else: ?>
<p style="grid-column:1/-1;text-align:center;">Немате огласи.</p>
<?php endif; ?>

</main>

</body>
</html>
