<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $password]);
        echo "<h3 style='color:green;text-align:center'>تم التسجيل بنجاح! <a href='login.php'>سجل الدخول</a></h3>";
    } catch (Exception $e) {
        echo "<h3 style='color:red;text-align:center'>الإيميل مستخدم من قبل</h3>";
    }
}
?>
