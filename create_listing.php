<?php
session_start();
require_once "config.php";
if (!isset($_SESSION['user_id'])) die("Login required");
?>

<!DOCTYPE html>
<html lang="mk">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Објави возило</title>
</head>
<body>

<h2>🚗 Објави возило</h2>

<form action="save_listing.php" method="post" enctype="multipart/form-data" class="form">

<!-- BRAND -->
<label>Марка</label>
<select id="brand" name="brand" required>
    <option value="">Одберете</option>
    <?php foreach ($CAR_DATA as $b => $m): ?>
        <option value="<?= $b ?>"><?= $b ?></option>
    <?php endforeach; ?>
</select>

<!-- MODEL + VERSION -->
<label>Модел</label>
<select id="model" name="model" required disabled>
    <option value="">Одберете модел</option>
</select>

<!-- YEAR -->
<label>Година</label>
<select name="year" required>
    <option value="">Одберете</option>
    <?php for ($y = date("Y"); $y >= 1980; $y--): ?>
        <option value="<?= $y ?>"><?= $y ?></option>
    <?php endfor; ?>
</select>

<!-- FUEL -->
<label>Гориво</label>
<select name="fuel" required>
    <option value="Petrol">Petrol</option>
    <option value="Diesel">Diesel</option>
    <option value="Hybrid">Hybrid</option>
    <option value="Electric">Electric</option>
</select>

<!-- MILEAGE -->
<label>Километри</label>
<select name="mileage" required>
    <option value="">Одберете</option>
    <option value="0-50000">0 – 50.000</option>
    <option value="50000-100000">50.000 – 100.000</option>
    <option value="100000-150000">100.000 – 150.000</option>
    <option value="150000-200000">150.000 – 200.000</option>
    <option value="200000-250000">200.000 – 250.000</option>
    <option value="250000-300000">250.000 – 300.000</option>
    <option value="300000-350000">300.000 – 350.000</option>
    <option value="350000-400000">350.000 – 400.000</option>
    <option value="400000+">400.000+</option>
</select>

<!-- TRANSMISSION -->
<label>Менувач</label>
<select name="transmission">
    <option value="Manual">Manual</option>
    <option value="Automatic">Automatic</option>
</select>

<!-- REGISTRATION -->
<label>Регистрација</label>
<select name="registration" required>
    <option value="">Одберете</option>
    <option value="MK">Македонска</option>
    <option value="FOREIGN">Странска</option>
</select>

<!-- REGISTRATION UNTIL -->
<label>Регистрација до</label>
<div class="row">
    <select name="reg_month">
        <?php for ($m = 1; $m <= 12; $m++): ?>
            <option value="<?= $m ?>"><?= $m ?></option>
        <?php endfor; ?>
    </select>

    <select name="reg_year">
        <?php for ($y = date("Y"); $y <= date("Y") + 5; $y++): ?>
            <option value="<?= $y ?>"><?= $y ?></option>
        <?php endfor; ?>
    </select>
</div>

<!-- POWER -->
<label>Сила на мотор (KW)</label>
<input type="number" name="power">

<!-- EMISSION -->
<label>Класа на емисија</label>
<select name="emission_class">
    <option value="">Одберете</option>
    <option>Euro 3</option>
    <option>Euro 4</option>
    <option>Euro 5</option>
    <option>Euro 6</option>
</select>

<!-- BODY TYPE -->
<label>Каросерија</label>
<select name="body_type" required>
    <option value="">Одберете</option>
    <option value="Мал градски">Мал градски</option>
    <option value="Хеџбек">Хеџбек</option>
    <option value="Седан">Седан</option>
    <option value="Караван">Караван</option>
    <option value="Купе">Купе</option>
    <option value="Кабриолет">Кабриолет</option>
    <option value="SUV / Џип">SUV / Џип</option>
    <option value="Кросовер">Кросовер</option>
    <option value="Пикап">Пикап</option>
    <option value="Комбе">Комбе</option>
    <option value="Миниван">Миниван</option>
</select>

<!-- COLOR -->
<label>Боја</label>
<select name="color" required>
    <option value="">Одберете боја</option>
    <option value="Црна">Црна</option>
    <option value="Бела">Бела</option>
    <option value="Сива">Сива</option>
    <option value="Сребрена">Сребрена</option>
    <option value="Сина">Сина</option>
    <option value="Црвена">Црвена</option>
    <option value="Зелена">Зелена</option>
    <option value="Жолта">Жолта</option>
    <option value="Кафеава">Кафеава</option>
    <option value="Портокалова">Портокалова</option>
    <option value="Виолетова">Виолетова</option>
    <option value="Друга">Друга</option>
</select>

<!-- CITY -->
<label>Град</label>
<select name="city" required>
    <option value="">Одберете град</option>
    <option value="Скопје">Скопје</option>
    <option value="Битола">Битола</option>
    <option value="Тетово">Тетово</option>
    <option value="Прилеп">Прилеп</option>
    <option value="Охрид">Охрид</option>
    <option value="Велес">Велес</option>
    <option value="Куманово">Куманово</option>
    <option value="Штип">Штип</option>
    <option value="Струмица">Струмица</option>
</select>

<!-- TITLE -->
<label>Наслов на оглас</label>
<input type="text" name="title" required>

<!-- DESCRIPTION -->
<label>Опис на оглас</label>
<textarea name="description" required></textarea>

<!-- PRICE -->
<label>Цена</label>
<div class="row">
    <input type="number" name="price" required>
    <select name="currency" required>
        <option value="EUR">EUR</option>
        <option value="MKD">MKD</option>
    </select>
</div>

<!-- IMAGES -->
<label>Слики</label>
<input type="file" name="images[]" multiple required>

<label>Контакт телефон</label>
<input type="text" name="contact_phone" placeholder="+38970123456">

<button class="btn">Објави оглас</button>
</form>

<script>
const DATA = <?= json_encode($CAR_DATA, JSON_UNESCAPED_UNICODE) ?>;
const brand = document.getElementById("brand");
const model = document.getElementById("model");

brand.onchange = () => {
    model.innerHTML = '<option value="">Одберете модел</option>';
    model.disabled = true;

    if (!DATA[brand.value]) return;

    Object.entries(DATA[brand.value]).forEach(([m, versions]) => {
        versions.forEach(v => {
            const label = `${m} ${v}`;
            model.innerHTML += `<option value="${label}">${label}</option>`;
        });
    });

    model.disabled = false;
};
</script>

</body>
</html>
<style>
    /* ================= GLOBAL ================= */

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial;
    background: linear-gradient(135deg, #f4f6f8, #eef1f5);
    color: #222;
}

/* ================= TITLE ================= */

h2 {
    text-align: center;
    padding: 25px 15px 10px;
    margin: 0;
    font-weight: 600;
}

/* ================= FORM CONTAINER ================= */

.form {
    max-width: 850px;
    margin: 20px auto 60px;
    padding: 30px;
    background: white;
    border-radius: 18px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.08);
    display: flex;
    flex-direction: column;
    gap: 14px;
}

/* ================= LABELS ================= */

.form label {
    font-size: 14px;
    font-weight: 600;
    margin-top: 8px;
}

/* ================= INPUTS ================= */

.form input,
.form select,
.form textarea {
    width: 100%;
    padding: 11px 12px;
    border-radius: 10px;
    border: 1px solid #ddd;
    font-size: 14px;
    transition: 0.2s ease;
    background: #fafafa;
}

.form textarea {
    min-height: 100px;
    resize: vertical;
}

.form input:focus,
.form select:focus,
.form textarea:focus {
    outline: none;
    border-color: #4a90e2;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(74,144,226,0.15);
}

/* ================= ROW (2 COLUMNS) ================= */

.row {
    display: flex;
    gap: 12px;
}

.row select,
.row input {
    flex: 1;
}

/* ================= BUTTON ================= */

.btn {
    margin-top: 15px;
    padding: 13px;
    border-radius: 12px;
    border: none;
    background: linear-gradient(45deg, #4a90e2, #0066ff);
    color: white;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,102,255,0.3);
}

/* ================= TABLET ================= */

@media (max-width: 900px) {
    .form {
        margin: 15px;
        padding: 25px;
    }
}

/* ================= MOBILE ================= */

@media (max-width: 600px) {

    h2 {
        font-size: 20px;
        padding-top: 20px;
    }

    .form {
        margin: 10px;
        padding: 20px;
        border-radius: 14px;
    }

    .row {
        flex-direction: column;
    }

    .btn {
        font-size: 14px;
    }
}
</style>