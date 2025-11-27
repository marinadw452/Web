<?php
// login.php
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>تسجيل الدخول</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h2>تسجيل الدخول</h2>
    <form method="post" action="login.php">
      <label>اسم المستخدم</label>
      <input type="text" name="username" placeholder="أدخل اسم المستخدم" required>

      <label>كلمة المرور</label>
      <input type="password" name="password" placeholder="أدخل كلمة المرور" required>

      <button type="submit">دخول</button>
    </form>
    <div class="register-link">
      ليس لديك حساب؟ <a href="signup.php">سجل الآن</a>
    </div>
  </div>
</body>
</html>
