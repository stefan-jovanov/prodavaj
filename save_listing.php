<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['user_id'])) {
    die("Login required");
}

/* FIXED CATEGORY */
$category = "Моторни возила";
$subcategory = "Автомобили";

/* REQUIRED FIELDS */
$required = [
    'title','description','brand','model','year','fuel',
    'mileage','registration','price','currency','city'
];

foreach ($required as $field) {
    if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
        die("Недостасуваат податоци");
    }
}

if (empty($_FILES['images']['name'][0])) {
    die("Мора да прикачите барем една слика");
}

/* SAFE DATA */
$user_id      = $_SESSION['user_id'];
$title        = trim($_POST['title']);
$description  = trim($_POST['description']);
$brand        = $_POST['brand'];
$model        = $_POST['model'];
$year         = (int)$_POST['year'];
$mileage      = $_POST['mileage'];
$power        = $_POST['power'] !== '' ? (int)$_POST['power'] : null;
$fuel         = $_POST['fuel'];
$transmission = $_POST['transmission'] ?? null;
$registration = $_POST['registration'];

$reg_until = (!empty($_POST['reg_month']) && !empty($_POST['reg_year']))
    ? $_POST['reg_month'] . '/' . $_POST['reg_year']
    : null;

$emission  = $_POST['emission_class'] ?? null;
$body_type = $_POST['body_type'] ?? null;
$color     = $_POST['color'] ?? null;
$city      = $_POST['city'];
$price     = (float)$_POST['price'];
$currency  = $_POST['currency'];
$contact_phone = trim($_POST['contact_phone'] ?? '');

/* IMAGE UPLOAD */
$images = [];
if (!is_dir("uploads")) mkdir("uploads", 0777, true);

foreach ($_FILES['images']['tmp_name'] as $i => $tmp) {
    if ($_FILES['images']['error'][$i] === 0) {
        $ext = strtolower(pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg','png','webp'])) continue;

        $name = time() . "_" . rand(1000,9999) . "." . $ext;
        if (move_uploaded_file($tmp, "uploads/" . $name)) {
            $images[] = $name;
        }
    }
}

if (!$images) die("Грешка при прикачување слики");

/* INSERT */
$stmt = $pdo->prepare("
INSERT INTO listings (
    user_id,
    title,
    description,
    category,
    subcategory,
    brand,
    model,
    year,
    mileage,
    power,
    fuel,
    transmission,
    registration,
    registration_until,
    emission_class,
    body_type,
    color,
    city,
    price,
    currency,
    images,
    contact_phone
) VALUES (
    :user_id,
    :title,
    :description,
    :category,
    :subcategory,
    :brand,
    :model,
    :year,
    :mileage,
    :power,
    :fuel,
    :transmission,
    :registration,
    :reg_until,
    :emission,
    :body_type,
    :color,
    :city,
    :price,
    :currency,
    :images,
    :phone
)
");

$stmt->execute([
    ':user_id'   => $user_id,
    ':title'     => $title,
    ':description'=> $description,
    ':category'  => $category,
    ':subcategory'=> $subcategory,
    ':brand'     => $brand,
    ':model'     => $model,
    ':year'      => $year,
    ':mileage'   => $mileage,
    ':power'     => $power,
    ':fuel'      => $fuel,
    ':transmission'=> $transmission,
    ':registration'=> $registration,
    ':reg_until' => $reg_until,
    ':emission'  => $emission,
    ':body_type' => $body_type,
    ':color'     => $color,
    ':city'      => $city,
    ':price'     => $price,
    ':currency'  => $currency,
    ':images'    => json_encode($images, JSON_UNESCAPED_UNICODE),
    ':phone'     => $contact_phone
]);


header("Location: index.php");
exit;
