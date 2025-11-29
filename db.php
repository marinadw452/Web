<?php
// db.php
$url = getenv("DATABASE_URL"); // Railway يوفر هذا المتغير

try {
    $pdo = new PDO($url);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("خطأ في الاتصال بقاعدة البيانات: " . $e->getMessage());
}
?>
