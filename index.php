<?php
// index.php
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>الصفحة الرئيسية</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: 'Cairo', sans-serif;
      background-color: #fff8e1;
      margin: 0;
      padding: 0;
      text-align: center;
    }
    .main-container {
      margin-top: 100px;
    }
    h1 {
      color: #f4b400;
    }
    .btn-login {
      display: inline-block;
      background-color: #f4b400;
      color: white;
      padding: 15px 30px;
      border-radius: 8px;
      text-decoration: none;
      font-size: 18px;
      transition: background-color 0.3s ease;
    }
    .btn-login:hover {
      background-color: #d89c00;
    }
  </style>
</head>
<body>
  <div class="main-container">
    <h1>مرحباً بك في موقع أيدي طيبة</h1>
    <p>منصة للمنتجات اليدوية المميزة</p>
    <a href="login.php" class="btn-login">تسجيل الدخول</a>
  </div>
</body>
</html>
