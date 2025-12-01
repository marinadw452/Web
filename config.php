<?php
// config.php ← شغال 100% مع الوضع الجديد في Railway 2025

$public_url = getenv("DATABASE_PUBLIC_URL");

if (!$public_url) {
    die("DATABASE_PUBLIC_URL مش موجود! روح Variables وتأكد إنه موجود");
}

$url = parse_url($public_url);

$pdo = new PDO(
    "pgsql:host=" . $url["host"] .
    ";port=" . $url["port"] .
    ";dbname=" . ltrim($url["path"], "/") .
    ";sslmode=require",
    $url["user"],
    $url["pass"],
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]
);

// اختبار الاتصال + إنشاء الجدول
$pdo->query("SELECT 1");

$pdo->exec("
    CREATE TABLE IF NOT EXISTS users (
        id SERIAL PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
");

echo "<h3 style='color:green;text-align:center'>تم الربط بنجاح باستخدام DATABASE_PUBLIC_URL</h3>";

session_start();
?>
