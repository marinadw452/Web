<?php
require_once __DIR__ . '/_auth.php';
admin_require_login();

$conn = db();

$focus_id = (int)($_GET['id'] ?? 0);

$messages = [];
if ($conn) {
    $stmt = $conn->prepare('SELECT id, user_id, name, email, phone, subject, message, ip_address, user_agent, created_at FROM contact_messages ORDER BY created_at DESC, id DESC LIMIT 300');
    if ($stmt) {
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) $messages[] = $row;
        $stmt->close();
    }
}

$focus = null;
if ($focus_id > 0) {
    foreach ($messages as $m) {
        if ((int)$m['id'] === $focus_id) {
            $focus = $m;
            break;
        }
    }
}

function e($s){
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

$page_title = 'رسائل الاتصال';
include __DIR__ . '/../includes/header.php';
?>

<style>
  .admin-shell{max-width:1200px;margin:110px auto 60px;padding:0 16px;}
  .admin-card{background:#fff;border:1px solid rgba(62,39,35,0.08);border-radius:22px;box-shadow:0 18px 45px rgba(62,39,35,0.08);overflow:hidden;}
  .admin-head{padding:18px 18px 14px;background:linear-gradient(135deg, rgba(186,125,55,0.16), rgba(255,183,77,0.10));border-bottom:1px solid rgba(62,39,35,0.08);display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;}
  .admin-head h1{margin:0;color:#3e2723;font-size:24px;font-weight:1000;}
  .admin-actions{display:flex;gap:10px;flex-wrap:wrap;}
  .admin-btn{border:none;border-radius:999px;padding:10px 14px;font-weight:1000;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:8px;}
  .admin-btn.ghost{background:#fff;border:1px solid rgba(62,39,35,0.14);color:#3e2723;}
  .admin-body{padding:16px 18px 18px;}

  .admin-grid{display:grid;grid-template-columns: 1fr 0.9fr;gap:14px;align-items:start;}
  .admin-table{width:100%;border-collapse:collapse;}
  .admin-table th,.admin-table td{padding:10px 10px;border-bottom:1px solid rgba(62,39,35,0.08);text-align:right;vertical-align:top;}
  .admin-table th{color:#3e2723;font-weight:1000;background:#faf8f6;}
  .admin-table td{color:#6d4c41;font-weight:800;}
  .admin-link{color:#3e2723;font-weight:1000;text-decoration:none;}
  .admin-link:hover{text-decoration:underline;}
  .admin-empty{padding:18px;color:#8d6e63;font-weight:900;}

  .msg-card{border:1px solid rgba(62,39,35,0.08);border-radius:18px;padding:14px;background:#fff;}
  .msg-title{margin:0 0 10px;color:#3e2723;font-size:18px;font-weight:1000;}
  .msg-meta{display:grid;gap:6px;color:#6d4c41;font-weight:900;}
  .msg-body{margin-top:10px;white-space:pre-wrap;background:#fafafa;border:1px solid rgba(62,39,35,0.08);border-radius:16px;padding:12px;color:#3e2723;font-weight:800;}

  @media (max-width: 980px){
    .admin-grid{grid-template-columns: 1fr;}
  }
</style>

<div class="admin-shell">
  <div class="admin-card">
    <div class="admin-head">
      <h1>رسائل الاتصال</h1>
      <div class="admin-actions">
        <a class="admin-btn ghost" href="index.php">الرجوع للوحة</a>
        <a class="admin-btn ghost" href="logout.php">تسجيل الخروج</a>
      </div>
    </div>
    <div class="admin-body">
      <div class="admin-grid">

        <div>
          <?php if (!$messages): ?>
            <div class="admin-empty">لا توجد رسائل حتى الآن.</div>
          <?php else: ?>
            <table class="admin-table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>الاسم</th>
                  <th>البريد</th>
                  <th>الموضوع</th>
                  <th>التاريخ</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($messages as $m): ?>
                  <tr>
                    <td><a class="admin-link" href="contact_messages.php?id=<?= (int)$m['id'] ?>"><?= (int)$m['id'] ?></a></td>
                    <td><?= e($m['name']) ?></td>
                    <td><?= e($m['email']) ?></td>
                    <td><?= e($m['subject']) ?></td>
                    <td><?= e($m['created_at']) ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php endif; ?>
        </div>

        <div>
          <div class="msg-card">
            <?php if (!$focus): ?>
              <div class="msg-title">تفاصيل الرسالة</div>
              <div class="admin-empty">اختر رسالة من القائمة لعرض التفاصيل.</div>
            <?php else: ?>
              <div class="msg-title">الرسالة رقم #<?= (int)$focus['id'] ?></div>
              <div class="msg-meta">
                <div>الاسم: <?= e($focus['name']) ?></div>
                <div>البريد: <?= e($focus['email']) ?></div>
                <?php if (trim((string)$focus['phone']) !== ''): ?>
                  <div>الجوال: <?= e($focus['phone']) ?></div>
                <?php endif; ?>
                <div>الموضوع: <?= e($focus['subject']) ?></div>
                <div>التاريخ: <?= e($focus['created_at']) ?></div>
                <?php if ((int)($focus['user_id'] ?? 0) > 0): ?>
                  <div>المستخدم: #<?= (int)$focus['user_id'] ?></div>
                <?php endif; ?>
              </div>
              <div class="msg-body"><?= e($focus['message']) ?></div>
            <?php endif; ?>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
