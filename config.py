<?php
$DATABASE_URL = getenv("DATABASE_URL");

if (!$DATABASE_URL) {
    die("❌ DATABASE_URL غير موجود.");
}

$conn = pg_connect($DATABASE_URL) or die("❌ فشل الاتصال: " . pg_last_error());
