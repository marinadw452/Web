<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $password]);
        
        echo "<h4 style="color:green;text-align:center">تم إنشاء الحساب بنجاح! جاري توجيهك...</h4>";
        echo "<script>setTimeout(() => window.location='login.php', 2000);</script>";
    } catch(PDOException $e) {
        echo "<h4 style='color:red'>الإيميل مستخدم من قبل</h4>";
    }
}
?>
