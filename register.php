<?php
session_start();
require_once "config.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $password2 = $_POST["password2"];

    // Check password match
    if ($password !== $password2) {
        $error = "Лозинките не се совпаѓаат.";
    } else {
        // Check if username exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);

        if ($stmt->rowCount() > 0) {
            $error = "Корисничкото име е користено.";
        } else {
            // Register new user
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO users (username, password, balance) VALUES (?, ?, 0)");
            $stmt->execute([$username, $hash]);

            $success = "Успешна регистрација! Сега можете да се најавите.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="mk">
<head>
    <meta charset="UTF-8">
    <title>Регистрација</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="login-wrapper">

    <div class="login-header">
        <div class="logo">Prodavaj<span>.mk</span></div>
        <p>Креирај нов профил 🚀</p>
    </div>

    <?php if ($error): ?>
        <div class="error-box"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="success-box"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" class="login-form" onsubmit="showLoading()">

        <label>Корисничко име</label>
        <input type="text" name="username" required>

        <label>Лозинка</label>
        <div class="password-wrapper">
            <input type="password" name="password" id="password" required oninput="checkStrength(this.value)">
            <span class="toggle-pass" onclick="togglePassword('password')">👁</span>
        </div>

        <div class="strength-bar">
            <div id="strengthFill"></div>
        </div>

        <label>Повтори лозинка</label>
        <div class="password-wrapper">
            <input type="password" name="password2" id="password2" required>
            <span class="toggle-pass" onclick="togglePassword('password2')">👁</span>
        </div>

        <button type="submit" class="btn-login" id="registerBtn">
            Регистрирај се
        </button>

    </form>

    <p>Веќе имаш профил? <a href="login.php">Најави се</a></p>

</div>
<script>
function togglePassword(id){
    const field = document.getElementById(id);
    field.type = field.type === "password" ? "text" : "password";
}

function showLoading(){
    const btn = document.getElementById("registerBtn");
    btn.innerText = "Се креира профил...";
    btn.disabled = true;
}

/* PASSWORD STRENGTH */
function checkStrength(password){
    const fill = document.getElementById("strengthFill");
    let strength = 0;

    if(password.length > 5) strength += 25;
    if(password.length > 8) strength += 25;
    if(/[A-Z]/.test(password)) strength += 25;
    if(/[0-9]/.test(password)) strength += 25;

    fill.style.width = strength + "%";

    if(strength <= 25) fill.style.background = "red";
    else if(strength <= 50) fill.style.background = "orange";
    else if(strength <= 75) fill.style.background = "yellow";
    else fill.style.background = "limegreen";
}
</script>
</body>
</html>
<style>
 *{
    box-sizing:border-box;
    font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial;
}

body{
    margin:0;
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    background: linear-gradient(135deg,#0f172a,#1e3a8a);
}

/* GLASS CARD */
.login-wrapper{
    width:100%;
    max-width:420px;
    padding:35px;
    border-radius:20px;
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(20px);
    box-shadow:0 25px 60px rgba(0,0,0,0.4);
    color:white;
}

/* HEADER */
.login-header{
    text-align:center;
    margin-bottom:25px;
}

.logo{
    font-size:24px;
    font-weight:700;
}

.logo span{
    color:#60a5fa;
}

.login-header p{
    margin-top:6px;
    font-size:14px;
    opacity:0.8;
}

/* FORM */
.login-form label{
    font-size:13px;
    margin-bottom:6px;
    display:block;
}

.login-form input{
    width:100%;
    padding:12px;
    margin-bottom:18px;
    border-radius:10px;
    border:none;
    font-size:14px;
}

/* PASSWORD */
.password-wrapper{
    position:relative;
}

.password-wrapper input{
    padding-right:40px;
}

.toggle-pass{
    position:absolute;
    right:12px;
    top:50%;
    transform:translateY(-50%);
    cursor:pointer;
}

/* PASSWORD STRENGTH */
.strength-bar{
    height:6px;
    background:rgba(255,255,255,0.2);
    border-radius:10px;
    margin-top:-10px;
    margin-bottom:18px;
    overflow:hidden;
}

#strengthFill{
    height:100%;
    width:0%;
    transition:0.3s;
}

/* BUTTON */
.btn-login{
    width:100%;
    padding:13px;
    border:none;
    border-radius:12px;
    background:#3b82f6;
    color:white;
    font-weight:600;
    cursor:pointer;
    transition:0.2s;
}

.btn-login:hover{
    background:#2563eb;
    transform:translateY(-2px);
}

/* ERROR / SUCCESS */
.error-box{
    background:rgba(255,0,0,0.25);
    padding:10px;
    border-radius:8px;
    margin-bottom:15px;
    text-align:center;
}

.success-box{
    background:rgba(0,255,0,0.25);
    padding:10px;
    border-radius:8px;
    margin-bottom:15px;
    text-align:center;
}

.login-wrapper p{
    text-align:center;
    margin-top:18px;
    font-size:14px;
}

.login-wrapper a{
    color:#93c5fd;
    text-decoration:none;
    font-weight:600;
}

@media(max-width:450px){
    .login-wrapper{
        margin:20px;
        padding:25px;
    }
}
    @media (max-width: 768px) {
     
    .login-wrapper {
        position: relative;
        transform: scale(1);
    }
    }

</style>