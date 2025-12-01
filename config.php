<?php

try {
    $pdo = new PDO(
        "pgsql:host=" . getenv('PGHOST') .
        ";port=" . getenv('PGPORT') .
        ";dbname=" . getenv('PGDATABASE'),
        getenv('PGUSER'),
        getenv('PGPASSWORD'),
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

    // إنشاء جدول المستخدمين تلقائيًا
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id SERIAL PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // رسالة اختبار (احذفها بعدين إذا تبي)
    // echo "<small style='color:green'>تم الربط بنجاح باستخدام Shared Variables</small>";

} catch (Exception $e) {
    die("فشل الاتصال: " . $e->getMessage());
}

session_start();
?>
