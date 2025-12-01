<?php
// config.php ← أضمن كود للربط مع PostgreSQL في Railway 2025

try {
    $pdo = new PDO(
        "pgsql:host=" . getenv("PGHOST") .
        ";port=" . getenv("PGPORT") .
        ";dbname=" . getenv("PGDATABASE"),
        getenv("PGUSER"),
        getenv("PGPASSWORD"),
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // إنشاء جدول المستخدمين تلقائيًا
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id SERIAL PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

} catch (Exception $e) {
    die("فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
}

session_start();
?>
