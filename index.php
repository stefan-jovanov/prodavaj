    <?php
    session_start();
    require_once "config.php";

    function e($s){ return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

    /* ================= FILTERS ================= */
    $where = [];
    $params = [];

    function addFilter($condition, $value) {
        global $where, $params;
        $where[] = $condition;
        $params[] = $value;
    }

    if (!empty($_GET['brand'])) {
        addFilter("brand = ?", $_GET['brand']);
    }

    if (!empty($_GET['model'])) {
        addFilter("model = ?", $_GET['model']);
    }

    if (!empty($_GET['year_from'])) {
        addFilter("year >= ?", $_GET['year_from']);
    }

    if (!empty($_GET['year_to'])) {
        addFilter("year <= ?", $_GET['year_to']);
    }

    if (!empty($_GET['mileage_from'])) {
        addFilter("mileage >= ?", $_GET['mileage_from']);
    }

    if (!empty($_GET['mileage_to'])) {
        addFilter("mileage <= ?", $_GET['mileage_to']);
    }

    if (!empty($_GET['fuel'])) {
        addFilter("fuel = ?", $_GET['fuel']);
    }

    if (!empty($_GET['transmission'])) {
        addFilter("transmission = ?", $_GET['transmission']);
    }

    if (!empty($_GET['registration'])) {
        addFilter("registration = ?", $_GET['registration']);
    }

    if (!empty($_GET['engine_from'])) {
        addFilter("engine_power >= ?", $_GET['engine_from']);
    }

    if (!empty($_GET['engine_to'])) {
        addFilter("engine_power <= ?", $_GET['engine_to']);
    }

    if (!empty($_GET['emission_class'])) {
        addFilter("emission_class = ?", $_GET['emission_class']);
    }

    if (!empty($_GET['body_type'])) {
        addFilter("body_type = ?", $_GET['body_type']);
    }

    if (!empty($_GET['color'])) {
        addFilter("color = ?", $_GET['color']);
    }

    if (!empty($_GET['city'])) {
        addFilter("city = ?", $_GET['city']);
    }

    if (!empty($_GET['price_from'])) {
        addFilter("price >= ?", $_GET['price_from']);
    }

    if (!empty($_GET['price_to'])) {
        addFilter("price <= ?", $_GET['price_to']);
    }

    $sql = "SELECT * FROM listings";

    if ($where) {
        $sql .= " WHERE " . implode(" AND ", $where);
    }

    $sql .= " ORDER BY is_premium DESC, id DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $listings = $stmt->fetchAll();

    /* ================= USER ================= */
    $user = null;
    if (isset($_SESSION['user_id'])) {
        $u = $pdo->prepare("SELECT * FROM users WHERE id=?");
        $u->execute([$_SESSION['user_id']]);
        $user = $u->fetch();
    }
    ?>

    <!DOCTYPE html>
    <html lang="mk">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prodavaj.mk</title>
    <link rel="stylesheet" href="style.css">
    </head>
    <body>

    <!-- ================= HEADER ================= -->
    <header class="header">
        <div class="logo" href="youtube.com" style="color: white;">Prodavaj<span>.mk</span></div>

        <form method="get" class="search-bar">
            <input type="text" name="q" placeholder="Пребарај..." value="<?= e($_GET['q'] ?? '') ?>">
            <button class="btn">
    <span class="material-icons">search</span></button>
    
            <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        </form>

   <nav class="account-nav">

<?php if(!$user): ?>
    <a class="afont" id="desnolog" href="login.php">Најава</a>
    <a class="afont" href="register.php">Регистрација</a>
<?php else: ?>

<div class="account">
    <button class="account-btn" id="accountBtn">
        👤 <?= e($user['username']) ?>
    </button>

    <div class="account-menu" id="accountMenu">
        <div class="account-balance">
            Баланс: <strong><?= number_format($user['balance'],2) ?> MKD</strong>
        </div>

        <a href="my_listings.php">📦 Мои огласи</a>
        <a href="create_listing.php">➕ Објави оглас</a>
        <a href="nadopolni_balance.php">💳 Надополни баланс</a>

        <hr>
        <a href="logout.php" class="logout">🚪 Одјава</a>
    </div>
</div>

<?php endif; ?>
</nav>


    </header>
<button class="filters-btn">≔ Filters</button>
    <!-- ================= FILTERS ================= -->
    <form method="get" class="filters">
    
    <h3 id="filterp">Filters</h3>

    <div class="grid">
    <div class="grid">
        <p>Brand</p>
    <select name="brand" id="brandSelect">
    <option value="">All</option>
    <?php foreach($CAR_DATA as $brand => $models): ?>
    <option value="<?= $brand ?>" <?= ($_GET['brand'] ?? '')==$brand?'selected':'' ?>>
    <?= $brand ?>
    </option>
    <?php endforeach; ?>
    </select>
    </div>
    <div class="grid red">
         <p class="redp">Model</p>
    <select name="model" id="modelSelect">
    <option value="">All</option>
    </select>
    </div>
    </div>
    <div class="grid" id="left">
    <div class="grid">
    
     <p>Year</p>
    <div class="row top1" id="sodve">
    <input class="pola" type="number" name="year_from" placeholder="From" value="<?= $_GET['year_from'] ?? '' ?>">
    <input class="pola" type="number" name="year_to" placeholder="To" value="<?= $_GET['year_to'] ?? '' ?>">
    </div>
    </div>
    <div class="grid red top2">
         <p class="redp">Kilometers</p>
    <div class="row top1">
    <input type="number" name="mileage_from" placeholder="From" value="<?= $_GET['mileage_from'] ?? '' ?>">
    <input type="number" name="mileage_to" placeholder="To" value="<?= $_GET['mileage_to'] ?? '' ?>">
    </div>
    </div>
    </div>
    <div class="grid" id="left1">
    <div class="grid top3">
         <p>Fuel</p>
    <select name="fuel">
    <option value="">All</option>
    <?php foreach(["Petrol","Diesel","Hybrid","Electric"] as $f): ?>
    <option value="<?= $f ?>" <?= ($_GET['fuel'] ?? '')==$f?'selected':'' ?>><?= $f ?></option>
    <?php endforeach; ?>
    </select>
    </div>
    <div class="grid red top3">
  <p clas="pp3" class="redp">Transmission</p>
    <select name="transmission">
    <option value="">All</option>
    <?php foreach(["Manual","Automatic"] as $t): ?>
    <option value="<?= $t ?>" <?= ($_GET['transmission'] ?? '')==$t?'selected':'' ?>><?= $t ?></option>
    <?php endforeach; ?>
    </select>
    </div>
    </div>
    <div class="grid" id="left2">
    <div class="grid top3">
         <p class="pp">Registration</p>
    <select name="registration">
    <option value="">All</option>
    <?php foreach(["Registered","Not Registered"] as $r): ?>
    <option value="<?= $r ?>" <?= ($_GET['registration'] ?? '')==$r?'selected':'' ?>><?= $r ?></option>
    <?php endforeach; ?>
    </select>
    </div>
    <div class="grid red top3">
         <p class="pp2 redp">Power</p>
    <div class="row top1">
    <input type="number" name="engine_from" placeholder="From" value="<?= $_GET['engine_from'] ?? '' ?>">
    <input type="number" name="engine_to" placeholder="To" value="<?= $_GET['engine_to'] ?? '' ?>">
    </div>
    </div>
    </div>
    <div class="grid" id="left3">
    <div class="grid top4">
         <p>Emission Class</p>
    <select name="emission_class">
    <option value="">All</option>
    <?php foreach(["Euro 3","Euro 4","Euro 5","Euro 6"] as $e): ?>
    <option value="<?= $e ?>" <?= ($_GET['emission_class'] ?? '')==$e?'selected':'' ?>><?= $e ?></option>
    <?php endforeach; ?>
    </select>
    </div>
    <div class="grid red top4">
         <p class="redp">Body Type</p>
    <select name="body_type">
    <option value="">All</option>
    <?php foreach(["Sedan","Hatchback","SUV","Coupe","Cabriolet","Wagon"] as $b): ?>
    <option value="<?= $b ?>" <?= ($_GET['body_type'] ?? '')==$b?'selected':'' ?>><?= $b ?></option>
    <?php endforeach; ?>
    </select>
    </div>
    </div>
    <div class="grid" id="left4">
    <div class="grid top4">
         <p>Color</p>
    <select name="color">
    <option value="">All</option>
    <?php foreach(["Black","White","Gray","Blue","Red","Silver"] as $c): ?>
    <option value="<?= $c ?>" <?= ($_GET['color'] ?? '')==$c?'selected':'' ?>><?= $c ?></option>
    <?php endforeach; ?>
    </select>
    </div>
    <div class="grid red top4">
         <p class="redp">City</p>
    <select name="city">
    <option value="">All</option>
    <?php foreach(["Skopje","Bitola","Tetovo","Ohrid"] as $c): ?>
    <option value="<?= $c ?>" <?= ($_GET['city'] ?? '')==$c?'selected':'' ?>><?= $c ?></option>
    <?php endforeach; ?>
    </select>
    </div>
    </div>
    <div class="grid top4" id="left5">
         <p class="pp1">Price</p>
    <div class="row top1">
    <input type="number" name="price_from" placeholder="From" value="<?= $_GET['price_from'] ?? '' ?>">
    <input type="number" name="price_to" placeholder="To" value="<?= $_GET['price_to'] ?? '' ?>">
    </div>
<div class="red">
    <button class="search">Search</button>
    </div>
    </div>
    </div>
    </form>

    <!-- ================= LISTINGS ================= -->
    <main class="gridd" id="listings" style="gap: 38px; margin-top: -1.5rem; ">

    <?php if ($listings): ?>
    <?php foreach ($listings as $l): 
        $imgs = json_decode($l['images'], true);
        $img = $imgs[0] ?? null;
    ?>
    <a href="listing.php?id=<?= $l['id'] ?>" class="card-link">
    <div class="card">
        <?php if (!empty($l['is_premium']) && $l['premium_until'] >= date("Y-m-d")): ?>
        <div class="premium-badge">⭐ PREMIUM</div>
    <?php endif; ?>


    <?php if ($img && file_exists("uploads/".$img)): ?>
        <img src="uploads/<?= e($img) ?>" alt="">
    <?php else: ?>
        <img src="no-image.png" alt="">
    <?php endif; ?>

    <div class="meta">
        Автомобили • <?= e($l['city']) ?>
    </div>

    <h3><?= e($l['title']) ?></h3>
    <p><?= number_format($l['price'],2) ?> <?= e($l['currency']) ?></p>

    </div>
    </a>
    <?php endforeach; ?>
    <?php else: ?>
    <p style="grid-column:1/-1;text-align:center;">Нема огласи.</p>
    <?php endif; ?>

    </main>
</main>
<script>
window.addEventListener("DOMContentLoaded", function(){

const carData = <?= json_encode($CAR_DATA, JSON_UNESCAPED_UNICODE) ?>;

const brandSelect = document.getElementById("brandSelect");
const modelSelect = document.getElementById("modelSelect");

if(!brandSelect || !modelSelect) return;

// preload models if filter active
loadModels(brandSelect.value);

brandSelect.addEventListener("change", function(){
    loadModels(this.value);
});

function loadModels(brand){

    modelSelect.innerHTML = '<option value="">All</option>';
    if(!carData[brand]) return;

    let models = [];
    const data = carData[brand];

    // loop through brand data
    Object.entries(data).forEach(([model, versions]) => {

        // case 1: "Abarth 500" => [""]
        if(Array.isArray(versions) && versions.length === 1 && versions[0] === ""){
            models.push(model);
        }

        // case 2: Mercedes nested models
        else if(Array.isArray(versions)){
            versions.forEach(v => {
                if(v && v.trim() !== ""){
                    models.push(model + " " + v);
                } else {
                    models.push(model);
                }
            });
        }

        // fallback
        else {
            models.push(model);
        }
    });

    // remove duplicates
    models = [...new Set(models)];

    models.forEach(m=>{
        const opt = document.createElement("option");
        opt.value = m;
        opt.textContent = m;
        modelSelect.appendChild(opt);
    });
}
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function(){

const btn = document.getElementById("accountBtn");
const menu = document.getElementById("accountMenu");

if(!btn || !menu) return;

// toggle menu
btn.addEventListener("click", function(e){
    e.stopPropagation();
    menu.classList.toggle("show");
});

// close when clicking outside
document.addEventListener("click", function(){
    menu.classList.remove("show");
});

});
</script>
<script>
document.querySelector('.filters-btn').addEventListener('click', function () {
    document.querySelector('.filters').classList.toggle('active');
});
</script>
    </body>


    </html>

