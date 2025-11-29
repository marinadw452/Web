<?php
// db.php
$url = getenv("DATABASE_URL");

if ($url) {
    $parts = parse_url($url);
    $host = $parts['host'];
    $port = $parts['port'];
    $user = $parts['user'];
    $pass = $parts['pass'];
    $dbname = ltrim($parts['path'], '/');

    try {
        $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$pass");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "✅ الاتصال ناجح!";
    } catch (PDOException $e) {
        die("❌ خطأ في الاتصال: " . $e->getMessage());
    }
} else {
    die("❌ متغير DATABASE_URL غير موجود.");
}
?>
