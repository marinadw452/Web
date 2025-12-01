<?php
// هنا حط بيانات الـ Postgres اللي عندك في Railway
$host = "postgres.railway.internal";  // غيّرها
$port = "5432";                                // غيّرها (عادة 5432 أو 6543)
$dbname = "railway";
$username = "postgres";
$password = "TrdjhQJMBQGZQRcotjfrcSOFlURYGxEu";       // غيّرها

$dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo '<!DOCTYPE html>
    <html dir="rtl" lang="ar">
    <head>
        <meta charset="utf-8">
        <title>متصل!</title>
        <style>
            body { font-family: Arial; text-align:center; margin-top:100px; background:#0c0c0c; color:#00ff00; }
            .success { font-size:50px; font-weight:bold; }
            .emoji { font-size:100px; }
        </style>
    </head>
    <body>
        <div class="emoji">🎉</div>
        <div class="success">تمام يا وحش!<br>الداتابيس انربطت 100%</div>
        <p>دلوقتي شيل هذا الملف عشان الأمان</p>
    </body>
    </html>';
} catch (Exception $e) {
    echo '<!DOCTYPE html>
    <html dir="rtl" lang="ar">
    <head>
        <meta charset="utf-8">
        <title>فشل الاتصال</title>
        <style>
            body { font-family: Arial; text-align:center; margin-top:100px; background:#0c0c0c; color:#ff0000; }
            .error { font-size:50px; font-weight:bold; }
            .emoji { font-size:100px; }
        </style>
    </head>
    <body>
        <div class="emoji">💔</div>
        <div class="error">ما انربطت الداتابيس</div>
        <p>الخطأ: ' . $e->getMessage() . '</p>
    </body>
    </html>';
}
?>
