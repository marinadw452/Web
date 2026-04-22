<?php
require_once __DIR__ . '/_auth.php';
admin_require_login();

$conn = db();
$total_msgs = 0;
$total_courses = 0;
if ($conn) {
    $res = $conn->query('SELECT COUNT(*) AS c FROM contact_messages');
    if ($res && ($row = $res->fetch_assoc())) {
        $total_msgs = (int)$row['c'];
    }

    $res2 = $conn->query('SELECT COUNT(*) AS c FROM courses');
    if ($res2 && ($row2 = $res2->fetch_assoc())) {
        $total_courses = (int)$row2['c'];
    }
}

$page_title = 'لوحة الأدمن';
include __DIR__ . '/../includes/header.php';

function e($s){
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}
?>

<style>
  .admin-shell{max-width:1100px;margin:110px auto 60px;padding:0 16px;}
  .admin-card{background:#fff;border:1px solid rgba(62,39,35,0.08);border-radius:22px;box-shadow:0 18px 45px rgba(62,39,35,0.08);overflow:hidden;}
  .admin-head{padding:18px 18px 14px;background:linear-gradient(135deg, rgba(186,125,55,0.16), rgba(255,183,77,0.10));border-bottom:1px solid rgba(62,39,35,0.08);display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;}
  .admin-head h1{margin:0;color:#3e2723;font-size:24px;font-weight:1000;}
  .admin-actions{display:flex;gap:10px;flex-wrap:wrap;}
  .admin-btn{border:none;border-radius:999px;padding:10px 14px;font-weight:1000;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:8px;}
  .admin-btn.ghost{background:#fff;border:1px solid rgba(62,39,35,0.14);color:#3e2723;}
  .admin-btn.primary{background:linear-gradient(135deg,#ffb74d,#ff9800);color:#3e2723;}
  .admin-body{padding:16px 18px 18px;}
  .admin-grid{display:grid;grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));gap:14px;}
  .admin-tile{border:1px solid rgba(62,39,35,0.08);border-radius:18px;padding:14px;background:#fff;}
  .admin-tile h2{margin:0 0 8px;color:#3e2723;font-size:18px;font-weight:1000;}
  .admin-tile p{margin:0;color:#8d6e63;font-weight:900;}
  .admin-metric{margin-top:10px;font-size:30px;font-weight:1000;color:#3e2723;}
</style>

<div class="admin-shell">
  <div class="admin-card">
    <div class="admin-head">
      <h1>لوحة الأدمن</h1>
      <div class="admin-actions">
        <a class="admin-btn ghost" href="courses.php">إدارة الكورسات</a>
        <a class="admin-btn primary" href="contact_messages.php">رسائل الاتصال</a>
      </div>
    </div>
    <div class="admin-body">
      <div class="admin-grid">
        <div class="admin-tile">
          <h2>الكورسات</h2>
          <p>عدد الكورسات المحفوظة في النظام</p>
          <div class="admin-metric"><?= (int)$total_courses ?></div>
        </div>
        <div class="admin-tile">
          <h2>رسائل التواصل</h2>
          <p>عدد الرسائل المحفوظة في النظام</p>
          <div class="admin-metric"><?= (int)$total_msgs ?></div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
