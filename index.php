<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>إنشاء حساب</title>
    <style>
        body {font-family: Arial; background: #f0f2f5; display:flex; justify-content:center; padding-top:50px;}
        .card {background:white; padding:30px; border-radius:15px; box-shadow:0 10px 30px rgba(0,0,0,0.1); width:100%; max-width:400px;}
        input, button {width:100%; padding:12px; margin:10px 0; border-radius:8px; border:1px solid #ddd;}
        button {background:#1877f2; color:white; font-size:18px; cursor:pointer;}
    </style>
</head>
<body>
<div class="card">
    <h2>إنشاء حساب جديد</h2>
    <form action="register.php" method="POST">
        <input type="text" name="name" placeholder="الاسم الكامل" required>
        <input type="email" name="email" placeholder="البريد الإلكتروني" required>
        <input type="password" name="password" placeholder="كلمة المرور" required minlength="6">
        <button type="submit">إنشاء الحساب</button>
    </form>
    <p><a href="login.php">عندك حساب؟ سجل دخول</a></p>
</div>
</body>
</html>
