<?php require 'config.php'; ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>فحص الاتصال</title>
    <style>body{font-family:Arial;background:#f0f2f5;padding:50px;text-align:center;font-size:22px;}</style>
</head>
<body>
<?php
try {
    $pdo->query("SELECT 1");
    $count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    echo "<h1 style='color:green'>نعم، قاعدة البيانات مربوطة 100%</h1>";
    echo "<h2>عدد المستخدمين حالياً: $count</h2>";
    echo "<p><a href='index.php'>اذهب لصفحة التسجيل</a></p>";
} catch (Exception $e) {
    echo "<h1 style='color:red'>لا، ما ربطت قاعدة البيانات</h1>";
    echo "<pre>" . $e->getMessage() . "</pre>";
}
?>
</body>
</html>
