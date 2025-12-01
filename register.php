<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $password]);
        echo '<h3 style="color:green;text-align:center">تم التسجيل بنجاح! <a href="login.php">اضغط هنا للدخول</a></h3>';
    } catch (Exception $e) {
        echo '<h3 style="color:red;text-align:center">الإيميل مستخدم من قبل</h3>';
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>إنشاء حساب</title>
    <style>
        body{font-family:Arial;background:#f0f2f5;display:flex;justify-content:center;padding-top:50px;}
        .card{background:white;padding:40px;border-radius:15px;box-shadow:0 10px 30px rgba(0,0,0,0.1);width:90%;max-width:400px;text-align:center;}
        input,button{width:100%;padding:14px;margin:10px 0;border-radius:8px;border:1px solid #ddd;font-size:16px;}
        button{background:#1877f2;color:white;cursor:pointer;}
    </style>
</head>
<body>
<div class="card">
    <h2>إنشاء حساب</h2>
    <form method="POST">
        <input type="text" name="name" placeholder="الاسم" required>
        <input type="email" name="email" placeholder="الإيميل" required>
        <input type="password" name="password" placeholder="كلمة المرور" required minlength="6">
        <button type="submit">تسجيل</button>
    </form>
    <p>لديك حساب؟ <a href="login.php">تسجيل الدخول</a></p>
</div>
</body>
</html>
