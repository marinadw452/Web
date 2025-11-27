<?php
// signup.php
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>إنشاء حساب</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h2>إنشاء حساب</h2>
    <form method="post" action="signup.php">
      <label>اسم المستخدم</label>
      <input type="text" name="username" placeholder="أدخل اسم المستخدم" required>

      <label>البريد الإلكتروني</label>
      <input type="email" name="email" placeholder="example@email.com" required>

      <label>نوع الحساب</label>
      <select name="account_type">
        <option value="client">عميل</option>
        <option value="seller">بائع</option>
      </select>

      <label>كلمة المرور</label>
      <input type="password" name="password" placeholder="أدخل كلمة المرور" required>

      <label>تأكيد كلمة المرور</label>
      <input type="password" name="confirm_password" placeholder="أدخل كلمة المرور مرة أخرى" required>

      <button type="submit">سجل الآن</button>
    </form>
    <div class="login-link">
      لديك حساب؟ <a href="login.php">سجل الدخول</a>
    </div>
  </div>
</body>
</html>
