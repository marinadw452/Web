<?php
// database/create_tables.php - كل الجداول هنا

declare(strict_types=1);

if (!isset($pdo)) exit;

// جدول المستخدمين النهائي والمحترف
$pdo->exec("
    CREATE TABLE IF NOT EXISTS users (
        id               SERIAL PRIMARY KEY,
        name             VARCHAR(100)   NOT NULL,
        email            VARCHAR(100)   UNIQUE NOT NULL,
        password         VARCHAR(255)   NOT NULL,
        phone            VARCHAR(20)    UNIQUE,
        avatar           VARCHAR(255),
        role             VARCHAR(20)    DEFAULT 'user' CHECK (role IN ('user', 'admin')),
        is_active        BOOLEAN        DEFAULT true,
        email_verified_at  TIMESTAMP,
        created_at       TIMESTAMP      DEFAULT CURRENT_TIMESTAMP,
        updated_at       TIMESTAMP      DEFAULT CURRENT_TIMESTAMP,
        deleted_at       TIMESTAMP
    )
");

// تحديث تلقائي للأعمدة الجديدة (لو سويت تحديث للموقع بعدين)
$columns = $pdo->query("SELECT column_name FROM information_schema.columns WHERE table_name='users'")
               ->fetchAll(PDO::FETCH_COLUMN);

$updates = [
    "ALTER TABLE users ADD COLUMN IF NOT EXISTS phone VARCHAR(20) UNIQUE",
    "ALTER TABLE users ADD COLUMN IF NOT EXISTS avatar VARCHAR(255)",
    "ALTER TABLE users ADD COLUMN IF NOT EXISTS role VARCHAR(20) DEFAULT 'user'",
    "ALTER TABLE users ADD COLUMN IF NOT EXISTS is_active BOOLEAN DEFAULT true",
    "ALTER TABLE users ADD COLUMN IF NOT EXISTS deleted_at TIMESTAMP"
];

foreach ($updates as $sql) {
    try { $pdo->exec($sql); } catch (Exception $e) { /* تجاهل إذا العمود موجود */ }
}
