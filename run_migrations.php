<?php
require_once __DIR__ . "/config.php";

// إنشاء جدول المستخدمين إذا لم يكن موجودًا
$sql = <<<SQL
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
SQL;

pg_query($conn, $sql);

echo "✅ جدول users جاهز!";
