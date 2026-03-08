<?php
session_start();
require_once "config.php";

function e($s){ return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Невалиден оглас");
}

$stmt = $pdo->prepare("SELECT * FROM listings WHERE id = ?");
$stmt->execute([$_GET['id']]);
$l = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$l) {
    die("Огласот не постои");
}

$images = json_decode($l['images'], true) ?? [];
?>
<!DOCTYPE html>
<html lang="mk">
<head>
<meta charset="UTF-8">
<title><?= e($l['title']) ?> | Prodavaj.mk</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<header class="header">
    <div class="logo" onclick="location.href='index.php'">
        Prodavaj<span>.mk</span>
    </div>
</header>

<main class="listing-container">

<!-- LEFT COLUMN -->
<div class="listing-main">

    <!-- GALLERY -->
<div class="gallery-card">

    <div class="gallery-wrapper">
        <img id="mainImage" src="uploads/<?= e($images[0] ?? 'no-image.png') ?>" class="main-img" onclick="openModal(this.src)">
    </div>

    <div class="thumb-row">
        <?php foreach ($images as $img): ?>
            <?php if (file_exists("uploads/".$img)): ?>
                <img src="uploads/<?= e($img) ?>" onclick="changeImage(this.src)">
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

</div>

<!-- FULLSCREEN MODAL -->
<div id="imgModal" class="img-modal" onclick="closeModal()">
    <img id="modalImg">
</div>
    <!-- DESCRIPTION -->
    <div class="card">
        <h2>Опис</h2>
        <p><?= nl2br(e($l['description'])) ?></p>
    </div>

</div>


<!-- RIGHT COLUMN -->
<aside class="listing-sidebar">

    <div class="price-card">
        <h1><?= e($l['title']) ?></h1>

        <div class="price-big">
            <?= number_format($l['price'], 0) ?> <?= e($l['currency']) ?>
        </div>

        <div class="meta">
            📍 <?= e($l['city']) ?> · 🗓 <?= e($l['year']) ?>
        </div>
    </div>

    <!-- SPECS -->
    <div class="card specs-card">
        <h3>Спецификации</h3>

        <div class="specs-grid">
            <div><span>Марка</span><b><?= e($l['brand']) ?></b></div>
            <div><span>Модел</span><b><?= e($l['model']) ?></b></div>
            <div><span>Километри</span><b><?= e($l['mileage']) ?></b></div>
            <div><span>Гориво</span><b><?= e($l['fuel']) ?></b></div>

            <?php if($l['transmission']): ?>
            <div><span>Менувач</span><b><?= e($l['transmission']) ?></b></div>
            <?php endif; ?>

            <?php if($l['power']): ?>
            <div><span>Моќност</span><b><?= e($l['power']) ?> kW</b></div>
            <?php endif; ?>

            <?php if($l['body_type']): ?>
            <div><span>Каросерија</span><b><?= e($l['body_type']) ?></b></div>
            <?php endif; ?>

            <?php if($l['color']): ?>
            <div><span>Боја</span><b><?= e($l['color']) ?></b></div>
            <?php endif; ?>
        </div>
    </div>

    <!-- CONTACT -->
    <?php 
$phone = trim($l['contact_phone'] ?? '');
if($phone !== ''): 
?>

<div class="contact-card">

    <div class="seller">
        <div class="avatar">👤</div>
        <div>
            <b>Продавач</b>
            <div class="muted"><?= e($l['city']) ?></div>
        </div>
    </div>

    <a href="tel:<?= e($phone) ?>" class="call-btn-big">
        📞 Повикај <?= e($phone) ?>
    </a>

<button onclick="copyNumber('<?= e($phone) ?>')" class="copy-btn">
    📋 Копирај број
</button>

</div>

<?php endif; ?>
</aside>

</main>
<script>
function copyNumber(phone){

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(phone).then(() => {
            alert("Бројот е копиран!");
        });
    } else {
        // Fallback за HTTP / XAMPP
        const textArea = document.createElement("textarea");
        textArea.value = phone;
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            document.execCommand('copy');
            alert("Бројот е копиран!");
        } catch (err) {
            alert("Не може да се копира.");
        }

        document.body.removeChild(textArea);
    }
}
</script>
<a href="index.php" class="floating-back">←</a>
<script>
function changeImage(src){
    document.getElementById('mainImage').src = src;
}

function openModal(src){
    document.getElementById('imgModal').style.display = 'flex';
    document.getElementById('modalImg').src = src;
}

function closeModal(){
    document.getElementById('imgModal').style.display = 'none';
}

/* SIMPLE SWIPE */
let startX = 0;
const mainImage = document.getElementById("mainImage");

mainImage.addEventListener("touchstart", e => {
    startX = e.touches[0].clientX;
});

mainImage.addEventListener("touchend", e => {
    let endX = e.changedTouches[0].clientX;
    if(startX - endX > 50) nextImage();
    if(endX - startX > 50) prevImage();
});

function nextImage(){
    let thumbs = document.querySelectorAll('.thumb-row img');
    let current = mainImage.src;
    thumbs.forEach((img,i)=>{
        if(img.src === current && thumbs[i+1]){
            mainImage.src = thumbs[i+1].src;
        }
    });
}

function prevImage(){
    let thumbs = document.querySelectorAll('.thumb-row img');
    let current = mainImage.src;
    thumbs.forEach((img,i)=>{
        if(img.src === current && thumbs[i-1]){
            mainImage.src = thumbs[i-1].src;
        }
    });
}
</script>
<script>
function changeImage(src){
    document.getElementById('mainImage').src = src;
}
</script>
<style>
    /* LAYOUT */
/* ===== BASE LAYOUT ===== */
.listing-container {
    max-width: 1200px;
    margin: 30px auto;
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 30px;
    padding: 0 15px;
}
div {
    background-color: transparent;
    box-shadow: none;
}
.specs-card, .card {
    background-color: transparent;
    box-shadow: none;
}
/* ===== TABLET ===== */
@media (max-width: 1024px) {
    .listing-container {
        grid-template-columns: 1fr;
    }

    .listing-sidebar {
        position: static;
    } 
    .card, .gallery-card, .price-card, .contact-card {
  
    border-radius: 14px;
    padding: 18px;
}
}

/* CARDS */


/* GALLERY */
.main-img {
    width: 100%;
    height: 420px;
    object-fit: cover;
    border-radius: 12px;
}

@media (max-width: 768px) {
    .main-img {
        height: 260px;
    }

    .thumb-row {
        overflow-x: auto;
        padding-bottom: 5px;
    }

    .thumb-row img {
        flex: 0 0 auto;
    }
}

.thumb-row {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}

.thumb-row img {
    width: 70px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.2s;
}

.thumb-row img:hover {
    transform: scale(1.05);
}

/* SIDEBAR */
.listing-sidebar {
    position: sticky;
    top: 90px;
    height: fit-content;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* PRICE */
.price-card h1 {
    font-size: 22px;
    margin-bottom: 8px;
}

.price-big {
    font-size: 34px;
    font-weight: 700;
    color: #1a73e8;
    margin-bottom: 6px;
}

.meta {
    color: #666;
    font-size: 14px;
}

/* SPECS */
.specs-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

@media (max-width: 600px) {
    .specs-grid {
        grid-template-columns: 1fr;
    }
}
@media (max-width: 768px) {
    .contact-card {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        border-radius: 0;
        padding: 15px;
        box-shadow: 0 -5px 15px rgba(0,0,0,0.1);
        z-index: 999;
    }

    body {
        padding-bottom: 90px;
    }
}

.specs-grid span {
    color: #777;
    display: block;
}

.specs-grid b {
    font-weight: 600;
}

/* CONTACT */
.seller {
    display: flex;
    gap: 12px;
    align-items: center;
    margin-bottom: 15px;
}

.avatar {
    width: 40px;
    height: 40px;
    background: #eee;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.call-btn-big {
    display: block;
    text-align: center;
    background: #22c55e;
    color: #fff;
    padding: 14px;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    transition: 0.2s;
}

.call-btn-big:hover {
    background: #16a34a;
}

/* MOBILE */
@media (max-width: 900px) {
    .listing-container {
        grid-template-columns: 1fr;
    }

    .main-img {
        height: 280px;
    }
}
/* SWIPE READY */
.gallery-wrapper {
    overflow: hidden;
    border-radius: 12px;
}

.main-img {
    width: 100%;
    height: 420px;
    object-fit: cover;
    cursor: pointer;
}

/* FULLSCREEN MODAL */
.img-modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.95);
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.img-modal img {
    max-width: 95%;
    max-height: 95%;
}
.floating-back{
    position: fixed;
    top: 80px;
    left: 15px;
    background: white;
    padding: 10px 14px;
    border-radius: 50px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    text-decoration: none;
    font-weight: bold;
    z-index: 1000;
}@media (max-width:768px){
    .contact-card{
        position:fixed;
        bottom:0;
        left:0;
        width:100%;
        border-radius:0;
        padding:15px;
        background:white;
        z-index:1000;
    }

    body{
        padding-bottom:100px;
    }
}
.call-btn-big{
    display:block;
    text-align:center;
    background:#22c55e;
    color:#fff;
    padding:14px;
    border-radius:10px;
    font-weight:600;
    text-decoration:none;
    margin-top:15px;
    transition:0.2s;
}

.call-btn-big:hover{
    background:#16a34a;
}

.copy-btn{
    width:100%;
    margin-top:10px;
    padding:12px;
    border-radius:10px;
    border:none;
    color: black;
    background:#f1f1f1;
    cursor:pointer;
    font-weight:500;
}@media (max-width:768px){
    .contact-card{
        position:fixed;
        bottom:0;
        left:0;
        width:100%;
        border-radius:0;
        padding:15px;
        background:white;
        box-shadow:0 -5px 15px rgba(0,0,0,0.1);
        z-index:1000;
    }

    body{
        padding-bottom:120px;
    }
}
</style>

</body>
</html>
