<?php
session_start();
require_once 'config.php';

$identifier = trim((string)($_POST['identifier'] ?? $_POST['email'] ?? ''));
$password = (string)($_POST['password'] ?? '');
$redirect_to = (string)($_POST['redirect_to'] ?? 'index.php');

// تنظيف عنوان الانتقال
$redirect_to = filter_var(urldecode($redirect_to), FILTER_SANITIZE_URL);
if (!preg_match('/^(index|products|about|contact|profile|favorites|cart)\.php/', basename($redirect_to))) {
    $redirect_to = 'index.php';
}

$conn = db();
if (!$conn) {
    $_SESSION['login_error'] = 'فشل الاتصال بقاعدة البيانات.';
    header("Location: login.php?redirect=" . urlencode($redirect_to));
    exit;
}

// البحث عن المستخدم باستخدام البريد الإلكتروني أو اسم المستخدم
$stmt = $conn->prepare("SELECT id, name, email, password as password_hash, role FROM users WHERE email = ? OR name = ? LIMIT 1");
if (!$stmt) {
    $_SESSION['login_error'] = 'حدث خطأ أثناء تسجيل الدخول. حاول مرة أخرى.';
    header("Location: login.php?redirect=" . urlencode($redirect_to));
    exit;
}

$stmt->bind_param("ss", $identifier, $identifier);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    
    // التحقق من كلمة المرور
    if (password_verify($password, $user['password_hash'])) {
        // تم إزالة فحص الحساب غير المفعل لأنه غير مدعوم حالياً
        
        // حفظ بيانات المستخدم في الجلسة
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = strtolower($user['role'] ?? 'user');
        
        // التوجيه بناءً على صلاحية المستخدم
        if ($_SESSION['role'] === 'seller') {
            $redirect_to = 'seller/dashboard.php';
        }
        
        header("Location: " . $redirect_to);
        exit;
    }
}

// إذا فشل تسجيل الدخول
$_SESSION['login_error'] = "البريد الإلكتروني/اسم المستخدم أو كلمة المرور غير صحيحة";
header("Location: login.php?redirect=" . urlencode($redirect_to));
exit;