<?php
// config.php - أنظف إصدار ممكن بدون أي ENV ولا شيء

// جلب DATABASE_URL من Railway فقط
$database_url = parse_url(getenv("DATABASE_URL"));

if (!$database_url) {
    die("خطأ: DATABASE_URL غير موجود. تأكد إنك مربوط بـ PostgreSQL في Railway");
}

$host     = $database_url["host"];
$port     = $database_url["port"];
$dbname   = ltrim($database_url["path"], "/");
$username = $database_url["user"];
$password = $database_url["pass"];

// الاتصال
try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);

    // تشغيل ملف الجداول مرة واحدة فقط
    require_once __DIR__DIR__ . '/database/create_tables.php';

} catch (PDOException $e) {
    // في الإنتاج نُخفي التفاصيل، لكن للتطوير نعرضها
    http_response_code(500);
    die("فشل الاتصال بقاعدة البيانات");
}
?>
