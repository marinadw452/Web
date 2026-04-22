<?php
require_once 'config.php';

$page_title = "إنشاء حساب جديد - أيدي طيّبة";

$error = $success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name     = trim($_POST['name'] ?? '');

    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $phone    = trim($_POST['phone'] ?? '');
    $role     = ($_POST['role'] ?? '') === 'seller' ? 'seller' : 'user';

    // التحقق من البيانات
    if (strlen($name) < 3) {
        $error = 'اسم المستخدم يجب أن يكون 3 أحرف على الأقل';
    } elseif (strlen($password) < 6) {
        $error = 'كلمة المرور يجب أن تكون 6 أحرف على الأقل';
    } elseif ($phone !== '' && !phone_has_min_digits($phone, 10)) {
        $error = 'رقم الجوال يجب أن لا يقل عن 10 أرقام';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'البريد الإلكتروني غير صالح';
    } else {
        // التحقق من عدم تكرار الاسم أو البريد
        if (db() !== null) {
            $check = db()->prepare("SELECT id FROM users WHERE name = ? OR email = ?");
            $check->bind_param("ss", $name, $email);
            $check->execute();
            
            if ($check->get_result()->num_rows > 0) {
                $error = 'اسم المستخدم أو البريد الإلكتروني مستخدم من قبل';
            } else {
                // تشفير كلمة المرور
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                
                // إدخال المستخدم الجديد
                $stmt = db()->prepare("INSERT INTO users (name, email, password, phone, role, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
                $stmt->bind_param("sssss", $name, $email, $hashed, $phone, $role);
                
                if ($stmt->execute()) {
                    $user_id = $stmt->insert_id;
                    
                    // إذا كان بائع، إنشاء سجل في جدول sellers (اختياري إذا كان الجدول موجود)
                    if ($role === 'seller') {
                        $hasSellers = db()->query("SHOW TABLES LIKE 'sellers'");
                        if ($hasSellers && $hasSellers->num_rows === 1) {
                            $seller_stmt = db()->prepare("INSERT INTO sellers (id, store_name, verification_status, created_at) VALUES (?, ?, 'pending', NOW())");
                            if ($seller_stmt) {
                                $store_name = "متجر " . $name;
                                $seller_stmt->bind_param("is", $user_id, $store_name);
                                $seller_stmt->execute();
                            }
                        }
                    }
                    
                    // تسجيل الدخول تلقائياً
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['name'] = $name;
                    $_SESSION['email'] = $email;
                    $_SESSION['role'] = $role;
                    
                    // إعادة التوجيه للصفحة الرئيسية
                    header("Location: index.php");
                    exit;
                } else {
                    $error = 'حدث خطأ أثناء إنشاء الحساب، يرجى المحاولة مرة أخرى';
                }
            }
        } else {
            $error = 'خطأ في الاتصال بقاعدة البيانات';
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<style>
  .signup-page {
    position: relative;
    min-height: calc(100vh - 80px);
    padding-top: 110px;
    padding-bottom: 60px;
  }

  .signup-bg {
    position: fixed;
    inset: 0;
    z-index: 0;
    overflow: hidden;
    background: radial-gradient(900px 450px at 20% 10%, rgba(215,185,142,0.45), transparent 60%),
                radial-gradient(700px 420px at 80% 25%, rgba(186,125,55,0.18), transparent 65%),
                radial-gradient(900px 500px at 40% 90%, rgba(255,183,77,0.16), transparent 70%),
                linear-gradient(180deg, rgba(253,249,243,1), rgba(253,249,243,1));
  }

  .signup-bg::after {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(253,249,243,0.85), rgba(253,249,243,0.92));
  }

  .auth-wrap {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 1120px;
    margin: 0 auto;
    padding: 0 18px;
    display: grid;
    grid-template-columns: 1.1fr 0.9fr;
    gap: 22px;
    align-items: stretch;
  }

  .auth-hero {
    border-radius: 26px;
    overflow: hidden;
    background: linear-gradient(135deg, rgba(255,183,77,0.22), rgba(186,125,55,0.10));
    border: 1px solid rgba(186,125,55,0.18);
    box-shadow: 0 24px 80px rgba(62,39,35,0.12);
    padding: 34px 32px;
    position: relative;
  }

  .auth-hero::before {
    content: "";
    position: absolute;
    inset: -60px;
    background: repeating-linear-gradient(
      135deg,
      rgba(186, 125, 55, 0.10) 0px,
      rgba(186, 125, 55, 0.10) 10px,
      rgba(215, 185, 142, 0.06) 10px,
      rgba(215, 185, 142, 0.06) 22px
    );
    opacity: 0.25;
    transform: rotate(6deg);
    pointer-events: none;
  }

  .auth-hero-inner {
    position: relative;
    z-index: 1;
  }

  .auth-hero .brand {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
  }

  .auth-hero .brand img {
    height: 58px;
    border-radius: 14px;
    background: rgba(255,255,255,0.7);
    padding: 6px;
    box-shadow: 0 10px 22px rgba(62,39,35,0.10);
  }

  .auth-hero h2 {
    margin: 18px 0 10px;
    color: #3e2723;
    font-size: 2.6rem;
    line-height: 1.25;
  }

  .auth-hero p {
    margin: 0;
    color: #5d4037;
    font-weight: 600;
    line-height: 1.9;
    font-size: 1.05rem;
  }

  .auth-card {
    border-radius: 26px;
    overflow: hidden;
    background: rgba(255, 255, 255, 0.72);
    border: 1px solid rgba(186,125,55,0.18);
    box-shadow: 0 24px 80px rgba(62,39,35,0.14);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    padding: 28px 26px;
  }

  .auth-header h1 {
    margin: 0 0 6px;
    color: #3e2723;
    font-size: 1.8rem;
  }

  .auth-header p {
    margin: 0 0 16px;
    color: #6d4c41;
    font-weight: 700;
  }

  .auth-form .field { display:block; margin: 14px 0; }
  .auth-form .field > span { display:block; margin-bottom: 8px; color:#3e2723; font-weight:800; }

  .auth-form input {
    width: 100%;
    padding: 14px 14px;
    border-radius: 14px;
    border: 1px solid rgba(62,39,35,0.14);
    background: rgba(255,255,255,0.9);
    font-size: 16px;
    outline: none;
    transition: box-shadow 0.2s ease, border-color 0.2s ease, transform 0.2s ease;
  }

  .auth-form input:focus {
    border-color: rgba(255,152,0,0.55);
    box-shadow: 0 0 0 0.25rem rgba(255, 152, 0, 0.18);
    transform: translateY(-1px);
  }

  .role-selection {
    margin-top: 10px;
    border-radius: 16px;
    background: rgba(253,249,243,0.75);
    border: 1px solid rgba(62,39,35,0.10);
    padding: 12px 12px 4px;
  }

  .role-selection label {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 12px;
    border-radius: 14px;
    cursor: pointer;
    font-weight: 800;
    color: #3e2723;
    transition: background 0.2s ease;
  }

  .role-selection label:hover {
    background: rgba(255, 183, 77, 0.18);
  }

  .role-selection input[type="radio"] {
    accent-color: #ff9800;
  }

  .auth-btn {
    width: 100%;
    margin-top: 12px;
    padding: 14px 16px;
    border: none;
    border-radius: 16px;
    font-weight: 1000;
    font-size: 1.05rem;
    cursor: pointer;
    color: #3e2723;
    background: linear-gradient(135deg, #ffcc80, #ff9800);
    box-shadow: 0 18px 45px rgba(186,125,55,0.25);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }

  .auth-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 24px 55px rgba(186,125,55,0.30);
  }

  .auth-footer {
    margin-top: 14px;
    text-align: center;
    color: #6d4c41;
    font-weight: 800;
  }

  .auth-footer .link { color: #ba7d37; text-decoration:none; }
  .auth-footer .link:hover { text-decoration: underline; }
  .auth-footer .muted { color: #6d4c41; }

  @media (max-width: 920px) {
    .auth-wrap { grid-template-columns: 1fr; }
    .auth-hero { display: none; }
    .signup-page { padding-top: 100px; }
  }
</style>

<div class="signup-page">
  <div class="signup-bg" aria-hidden="true"></div>

  <main class="auth-wrap">
    <section class="auth-hero" aria-hidden="true">
      <div class="auth-hero-inner">
        <div class="brand">
          <img src="images/LOGO.png" alt="">
          <div style="font-weight:1000;color:#3e2723;">أيدي طيّبة</div>
        </div>
        <h2>ابدأ رحلتك معنا</h2>
        <p>أنشئ حسابًا كعميل أو كبائع وابدأ في التصفح والبيع بسهولة.</p>
        <div class="heritage-divider" style="padding:22px 0 0;background:transparent;">
          <img src="images/heritage-divider.png" alt="" class="divider-img" style="height:28px;">
        </div>
      </div>
    </section>

    <section class="auth-card">
      <div class="auth-header">
        <h1>إنشاء حساب جديد</h1>
        <p>سجّل بياناتك وخلّنا نبدأ</p>
      </div>

      <?php if($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form class="auth-form" method="POST" autocomplete="on">
        <label class="field">
          <span>اسم المستخدم</span>
          <input type="text" name="name" placeholder="اسم المستخدم (3 أحرف على الأقل)" required minlength="3" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
        </label>

        <label class="field">
          <span>البريد الإلكتروني</span>
          <input type="email" name="email" placeholder="البريد الإلكتروني" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </label>

        <label class="field">
          <span>كلمة المرور</span>
          <input type="password" name="password" placeholder="كلمة المرور (6 أحرف على الأقل)" required minlength="6">
        </label>

        <label class="field">
          <span>رقم الجوال (اختياري)</span>
          <input type="tel" name="phone" placeholder="رقم الجوال" minlength="10" inputmode="numeric" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
        </label>

        <div class="role-selection">
          <label>
            <input type="radio" name="role" value="user" <?= (($_POST['role'] ?? 'user') !== 'seller') ? 'checked' : '' ?>>
            أتسوق وأشتري فقط (عميلة)
          </label>
          <label>
            <input type="radio" name="role" value="seller" <?= (($_POST['role'] ?? '') === 'seller') ? 'checked' : '' ?>>
            أبيع منتجاتي اليدوية (بائعة)
          </label>
        </div>

        <button class="auth-btn" type="submit">إنشاء الحساب</button>

        <div class="auth-footer">
          <a href="login.php" class="link">لديك حساب؟ تسجيل الدخول</a>
          <span class="dot">•</span>
          <a href="index.php" class="link muted">العودة للرئيسية</a>
        </div>
      </form>
    </section>
  </main>
</div>

<?php include 'includes/footer.php'; ?>