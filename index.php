<?php require 'config.php'; ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إنشاء حساب</title>
    <style>
        body{background:#f0f2f5;display:flex;justify-content:center;padding-top:50px;font-family:Arial;}
        .box{background:white;padding:40px;border-radius:15px;box-shadow:0 10px 30px rgba(0,0,0,0.1);width:90%;max-width:400px;text-align:center;}
        input,button{width:100%;padding:14px;margin:10px 0;border-radius:8px;border:1px solid #ddd;font-size:16px;}
        button{background:#1877f2;color:white;cursor:pointer;}
    </style>
</head>
<body>
<div class="box">
    <h2>إنشاء حساب جديد</h2>
    <form action="register.php" method="POST">
        <input type="text" name="name" placeholder="الاسم" required>
        <input type="email" name="email" placeholder="الإيميل" required>
        <input type="password" name="password" placeholder="كلمة المرور" required minlength="6">
        <button type="submit">تسجيل</button>
    </form>
    <p>لديك حساب؟ <a href="login.php">تسجيل الدخول</a></p>
    <hr>
    <p><a href="check.php">فحص اتصال قاعدة البيانات</a></p>
</div>
</body>
</html>
