<?php
// signup.php
include 'db.php'; // ملف الاتصال بقاعدة البيانات

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username       = trim($_POST['username']);
    $email          = trim($_POST['email']);
    $account_type   = $_POST['account_type'];
    $password       = $_POST['password'];
    $confirm_pass   = $_POST['confirm_password'];

    // تحقق من تطابق كلمة المرور
    if ($password !== $confirm_pass) {
        $message = "❌ كلمة المرور غير متطابقة.";
    } else {
        // تشفير كلمة المرور
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, account_type, password) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $email, $account_type, $hashed]);
            $message = "✅ تم إنشاء الحساب بنجاح!";
        } catch (PDOException $e) {
            // إذا فيه خطأ (مثل تكرار اسم المستخدم أو البريد)
            $message = "❌ خطأ: " . $e->getMessage();
        }
    }
}
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

    <!-- عرض رسالة نجاح أو خطأ -->
    <?php if (!empty($message)): ?>
      <p style="color: red; text-align:center;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

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
