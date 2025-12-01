<?php require 'config.php'; 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user'] = $user['name'];
        echo "<h3 style='color:green;text-align:center'>مرحباً " . $user['name'] . "!</h3>";
    } else {
        echo "<h3 style='color:red;text-align:center'>بيانات خاطئة</h3>";
    }
}
?>
<form method="POST" style="text-align:center;margin-top:50px;">
    <input type="email" name="email" placeholder="الإيميل" required><br><br>
    <input type="password" name="password" placeholder="كلمة المرور" required><br><br>
    <button type="submit">دخول</button>
</form>
<p><a href="index.php">إنشاء حساب جديد</a></p>
