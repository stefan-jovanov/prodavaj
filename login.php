<?php
session_start();
require_once "config.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["password"])) {

        $_SESSION["user_id"] = $user["id"];

        // 🔥 FIX: Redirect to index
        header("Location: index.php");
        exit;
    } else {
        $error = "Погрешна најава.";
    }
}
?>
<!DOCTYPE html>
<html lang="mk">
<head>
    <meta charset="UTF-8">
    <title>Најава</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="login-wrapper">

    <div class="login-header">
        <div class="logo">Prodavaj<span>.mk</span></div>
        <p>Добредојдовте назад 👋</p>
    </div>

    <?php if ($error): ?>
        <div class="error-box"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" class="login-form" onsubmit="showLoading()">

        <label>Корисничко име</label>
        <input type="text" name="username" required>

        <label>Лозинка</label>

        <div class="password-wrapper">
            <input type="password" name="password" id="password" required>
            <span class="toggle-pass" onclick="togglePassword()">👁</span>
        </div>

        <button type="submit" class="btn-login" id="loginBtn">
            Логирај се
        </button>

    </form>

    <p>Немаш профил? <a href="register.php">Регистрирај се</a></p>

</div>
<script>
function togglePassword(){
    const pass = document.getElementById("password");
    pass.type = pass.type === "password" ? "text" : "password";
}

function showLoading(){
    const btn = document.getElementById("loginBtn");
    btn.innerText = "Се најавува...";
    btn.disabled = true;
}
</script>
</body>
</html>
<style>
/* RESET */
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
@media(max-width:768px){
    
.login-wrapper {
    transform: scale(1);
}

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
    font-size:16px;
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

/* ERROR */
.error-box{
    background:rgba(255,0,0,0.2);
    padding:10px;
    border-radius:8px;
    margin-bottom:15px;
    text-align:center;
}

/* LINK */
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

/* MOBILE */
@media(max-width:450px){
    .login-wrapper{
        margin:20px;
        padding:25px;
    }
}

</style>