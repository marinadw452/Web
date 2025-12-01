<?php
// config.php – اختبار نهائي 100%

header('Content-Type: text/html; charset=utf-8');

$db_url = getenv("DATABASE_PUBLIC_URL");

if (!$db_url) {
    die("<h1 style='color:red'>DATABASE_PUBLIC_URL مش موجود في الـ Variables</h1>");
}

$url = parse_url($db_url);

try {
    $pdo = new PDO(
        "pgsql:host={$url['host']};port={$url['port']};dbname=" . ltrim($url['path'], '/'),
        $url['user'],
        $url['pass'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // اختبار الاتصال
    $pdo->query("SELECT 1");

    // إنشاء الجدول لو ما كان موجود
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id SERIAL PRIMARY KEY,
        name VARCHAR(100),
        email VARCHAR(100) UNIQUE,
        password VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // جلب عدد المستخدمين
    $count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

    echo "<h1 style='color:green; text-align:center'>تم الربط بنجاح 100%</h1>";
    echo "<h2 style='text-align:center'>عدد المستخدمين حالياً: $count</h2>";
    echo "<p style='text-align:center'>قاعدة البيانات شغالة وربطت تمام</p>";

} catch (Exception $e) {
    echo "<h1 style='color:red'>فشل الاتصال</h1>";
    echo "<pre>" . $e->getMessage() . "</pre>";
}

die(); // نوقف التنفيذ هنا عشان نشوف النتيجة بس
?>
