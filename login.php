<?php
// login.php
include 'db.php'; // ملف الاتصال بقاعدة البيانات

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $message = "🎉 مرحباً " . htmlspecialchars($user['username']);
        } else {
            $message = "❌ اسم المستخدم أو كلمة المرور غير صحيحة.";
        }
    } catch (PDOException $e) {
        $message = "❌ خطأ في قاعدة البيانات: " . $e->getMessage();
    }
}
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

    <!-- عرض رسالة نجاح أو خطأ -->
    <?php if (!empty($message)): ?>
      <p style="color: red; text-align:center;"><?php echo $message; ?></p>
    <?php endif; ?>

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
