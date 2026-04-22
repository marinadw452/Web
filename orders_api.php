<?php
require_once __DIR__ . '/config.php';

$page_title = 'الاتصال';
include __DIR__ . '/includes/header.php';

$conn = db();
$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;

$name = trim((string)($_POST['name'] ?? ($_SESSION['name'] ?? '')));
$email = trim((string)($_POST['email'] ?? ($_SESSION['email'] ?? '')));
$phone = trim((string)($_POST['phone'] ?? ($_SESSION['phone'] ?? '')));
$subject = trim((string)($_POST['subject'] ?? ''));
$message = trim((string)($_POST['message'] ?? ''));

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($name === '' || $email === '' || $subject === '' || $message === '') {
        $error = 'فضلاً عبّئ جميع الحقول المطلوبة.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'البريد الإلكتروني غير صحيح.';
    } elseif ($phone !== '' && !phone_has_min_digits($phone, 10)) {
        $error = 'رقم الجوال يجب أن لا يقل عن 10 أرقام.';
    } elseif (!$conn) {
        $error = 'تعذر الاتصال بقاعدة البيانات.';
    } else {
        $ip = (string)($_SERVER['REMOTE_ADDR'] ?? '');
        $ua = (string)($_SERVER['HTTP_USER_AGENT'] ?? '');
        if (mb_strlen($subject) > 255) $subject = mb_substr($subject, 0, 255);
        if (mb_strlen($ua) > 255) $ua = mb_substr($ua, 0, 255);

        $stmt = $conn->prepare('INSERT INTO contact_messages (user_id, name, email, phone, subject, message, ip_address, user_agent, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())');
        if (!$stmt) {
            $error = 'حدث خطأ أثناء إرسال الرسالة.';
        } else {
            $stmt->bind_param('isssssss', $user_id, $name, $email, $phone, $subject, $message, $ip, $ua);
            if ($stmt->execute()) {
                $msg_id = (int)$stmt->insert_id;

                // Notify admins in-app
                $adminIds = [];
                $resAdmins = $conn->query("SELECT id FROM users WHERE LOWER(role) = 'admin'");
                if ($resAdmins) {
                    while ($r = $resAdmins->fetch_assoc()) {
                        $adminIds[] = (int)($r['id'] ?? 0);
                    }
                }

                if ($msg_id > 0 && !empty($adminIds)) {
                    $notifTitle = 'رسالة تواصل جديدة';
                    $notifMsg = 'وصلتك رسالة جديدة من ' . $name . ' بعنوان: ' . $subject;
                    if (mb_strlen($notifMsg) > 240) $notifMsg = mb_substr($notifMsg, 0, 240) . '...';
                    $notifUrl = 'admin/contact_messages.php?id=' . $msg_id;

                    $insNotif = $conn->prepare('INSERT INTO notifications (user_id, order_id, type, title, message, url, is_read, created_at) VALUES (?, NULL, ?, ?, ?, ?, 0, NOW())');
                    if ($insNotif) {
                        foreach ($adminIds as $aid) {
                            if ($aid <= 0) continue;
                            $type = 'contact_message';
                            $insNotif->bind_param('issss', $aid, $type, $notifTitle, $notifMsg, $notifUrl);
                            $insNotif->execute();
                        }
                        $insNotif->close();
                    }
                }

                $success = true;
                $subject = '';
                $message = '';
            } else {
                $error = 'تعذر حفظ الرسالة. حاول مرة أخرى.';
            }
            $stmt->close();
        }
    }
}

function e($s){
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}
?>

<style>
  .contact-shell{max-width:1100px;margin:110px auto 60px;padding:0 16px;}
  .contact-grid{display:grid;grid-template-columns: 1.1fr 0.9fr;gap:18px;align-items:start;}
  .contact-card{background:#fff;border:1px solid rgba(62,39,35,0.08);border-radius:22px;box-shadow:0 18px 45px rgba(62,39,35,0.08);overflow:hidden;}
  .contact-head{padding:18px 18px 14px;background:linear-gradient(135deg, rgba(186,125,55,0.16), rgba(255,183,77,0.10));border-bottom:1px solid rgba(62,39,35,0.08);}
  .contact-head h1{margin:0;color:#3e2723;font-size:24px;font-weight:1000;}
  .contact-body{padding:16px 18px 18px;}
  .contact-note{color:#8d6e63;font-weight:800;margin:0 0 14px;}
  .contact-alert{border-radius:16px;padding:12px 14px;margin:0 0 14px;font-weight:900;}
  .contact-alert.error{background:#ffe8ea;color:#b00020;border:1px solid rgba(176,0,32,0.18);}
  .contact-alert.success{background:#e8f5e9;color:#1b5e20;border:1px solid rgba(27,94,32,0.18);}
  .contact-form{display:grid;gap:12px;}
  .contact-row{display:grid;grid-template-columns:1fr 1fr;gap:10px;}
  .contact-field label{display:block;margin:0 0 6px;color:#3e2723;font-weight:900;}
  .contact-field .req{color:#d50000;}
  .contact-field input,.contact-field textarea{width:100%;border-radius:14px;border:1px solid rgba(62,39,35,0.14);padding:12px 12px;font-family:inherit;font-weight:800;outline:none;background:#fff;}
  .contact-field textarea{min-height:140px;resize:vertical;}
  .contact-field input:focus,.contact-field textarea:focus{border-color:#ffb74d;box-shadow:0 0 0 4px rgba(255,183,77,0.25);}
  .contact-actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:6px;}
  .contact-btn{border:none;border-radius:999px;padding:11px 16px;font-weight:1000;cursor:pointer;}
  .contact-btn.primary{background:linear-gradient(135deg,#ffb74d,#ff9800);color:#3e2723;}
  .contact-btn.primary:hover{filter:brightness(0.98);}

  .contact-info{padding:16px 18px 18px;}
  .contact-info h2{margin:0 0 10px;color:#3e2723;font-size:20px;font-weight:1000;}
  .contact-line{display:flex;gap:10px;align-items:flex-start;color:#6d4c41;font-weight:900;margin:10px 0;}
  .contact-badge{width:34px;height:34px;border-radius:12px;display:flex;align-items:center;justify-content:center;background:rgba(255,183,77,0.22);border:1px solid rgba(62,39,35,0.10);flex-shrink:0;}
  .contact-muted{color:#8d6e63;font-weight:800;margin-top:12px;}

  @media (max-width: 900px){
    .contact-grid{grid-template-columns: 1fr;}
    .contact-row{grid-template-columns:1fr;}
  }
</style>

<div class="contact-shell">
  <div class="contact-grid">

    <div class="contact-card">
      <div class="contact-head">
        <h1>تواصل معنا</h1>
      </div>
      <div class="contact-body">
        <p class="contact-note">يسعدنا استقبال استفساراتك وملاحظاتك. سيتم الرد عليك في أقرب وقت.</p>

        <?php if ($error !== ''): ?>
          <div class="contact-alert error"><?= e($error) ?></div>
        <?php elseif ($success): ?>
          <div class="contact-alert success">تم إرسال رسالتك بنجاح. شكرًا لتواصلك معنا.</div>
        <?php endif; ?>

        <form class="contact-form" method="post" action="contact.php" novalidate>
          <div class="contact-row">
            <div class="contact-field">
              <label>الاسم <span class="req">*</span></label>
              <input type="text" name="name" value="<?= e($name) ?>" required>
            </div>
            <div class="contact-field">
              <label>البريد الإلكتروني <span class="req">*</span></label>
              <input type="email" name="email" value="<?= e($email) ?>" required>
            </div>
          </div>

          <div class="contact-row">
            <div class="contact-field">
              <label>رقم الجوال</label>
              <input type="text" name="phone" value="<?= e($phone) ?>" placeholder="اختياري" minlength="10" inputmode="numeric">
            </div>
            <div class="contact-field">
              <label>الموضوع <span class="req">*</span></label>
              <input type="text" name="subject" value="<?= e($subject) ?>" required>
            </div>
          </div>

          <div class="contact-field">
            <label>الرسالة <span class="req">*</span></label>
            <textarea name="message" required><?= e($message) ?></textarea>
          </div>

          <div class="contact-actions">
            <button type="submit" class="contact-btn primary">إرسال الرسالة</button>
          </div>
        </form>
      </div>
    </div>

    <div class="contact-card">
      <div class="contact-head">
        <h1>معلومات التواصل</h1>
      </div>
      <div class="contact-info">
        <h2>أيدي طيّبة</h2>

        <div class="contact-line">
          <div class="contact-badge">@</div>
          <div>
            <div>info@ayditayyiba.sa</div>
            <div class="contact-muted">للاستفسارات العامة</div>
          </div>
        </div>

        <div class="contact-line">
          <div class="contact-badge">☎</div>
          <div>
            <div>+966 50 123 4567</div>
            <div class="contact-muted">الدعم وخدمة العملاء</div>
          </div>
        </div>

        <div class="contact-line">
          <div class="contact-badge">⌂</div>
          <div>
            <div>المدينة المنورة، المملكة العربية السعودية</div>
            <div class="contact-muted">مقر المنصة</div>
          </div>
        </div>

        <div class="contact-muted">ساعات العمل: 9 صباحًا - 6 مساءً</div>
      </div>
    </div>

  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
