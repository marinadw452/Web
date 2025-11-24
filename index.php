<?php
session_start();
require_once "config.php";

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $res = pg_query_params($conn, "SELECT * FROM users WHERE username=$1", [$username]);
    $user = pg_fetch_assoc($res);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $message = "تم تسجيل الدخول بنجاح!";
    } else {
        $message = "اسم المستخدم أو كلمة المرور غير صحيحة.";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>تسجيل الدخول</title>
</head>
<body>
<h2>تسجيل الدخول</h2>
<form method="post">
    <input type="text" name="username" placeholder="اسم المستخدم" required><br><br>
    <input type="password" name="password" placeholder="كلمة المرور" required><br><br>
    <button type="submit">تسجيل الدخول</button>
</form>
<p><?php echo $message; ?></p>
</body>
</html>
