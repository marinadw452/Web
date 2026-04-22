<?php
require_once __DIR__ . '/_auth.php';
admin_require_login();

$conn = db();

function e($s){
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

function parse_youtube_id(string $url): string {
    $url = trim($url);
    if ($url === '') return '';

    // If user pasted a raw ID (common), accept it
    if (preg_match('/^[a-zA-Z0-9_-]{8,20}$/', $url)) {
        return $url;
    }

    $parts = @parse_url($url);
    if (!is_array($parts)) return '';

    $host = strtolower((string)($parts['host'] ?? ''));
    $path = (string)($parts['path'] ?? '');
    $query = (string)($parts['query'] ?? '');

    // youtu.be/<id>
    if (strpos($host, 'youtu.be') !== false) {
        $id = trim($path, '/');
        if (preg_match('/^[a-zA-Z0-9_-]{8,20}$/', $id)) return $id;
    }

    // youtube.com/watch?v=<id>
    if (strpos($host, 'youtube.com') !== false || strpos($host, 'www.youtube.com') !== false || strpos($host, 'm.youtube.com') !== false) {
        parse_str($query, $q);
        $id = (string)($q['v'] ?? '');
        if (preg_match('/^[a-zA-Z0-9_-]{8,20}$/', $id)) return $id;

        // youtube.com/embed/<id>
        if (preg_match('#^/embed/([a-zA-Z0-9_-]{8,20})#', $path, $m)) return $m[1];
        // youtube.com/shorts/<id>
        if (preg_match('#^/shorts/([a-zA-Z0-9_-]{8,20})#', $path, $m)) return $m[1];
    }

    return '';
}

$success = '';
$error = '';

$title = trim((string)($_POST['title'] ?? ''));
$description = trim((string)($_POST['description'] ?? ''));
$language = strtolower((string)($_POST['language'] ?? 'ar'));
$youtube_url = trim((string)($_POST['youtube_url'] ?? ''));
$sort_order = (int)($_POST['sort_order'] ?? 0);
$is_published = isset($_POST['is_published']) ? 1 : 0;

$action = (string)($_POST['action'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$conn) {
        $error = 'تعذر الاتصال بقاعدة البيانات.';
    } elseif ($action === 'create') {
        if ($title === '' || $youtube_url === '') {
            $error = 'فضلاً أدخل عنوان الكورس ورابط اليوتيوب.';
        } elseif (!in_array($language, ['ar', 'en'], true)) {
            $error = 'لغة الكورس غير صحيحة.';
        } else {
            $youtube_id = parse_youtube_id($youtube_url);
            if ($youtube_id === '') {
                $error = 'رابط اليوتيوب غير صحيح. (جرّب لصق رابط watch أو shorts أو youtu.be)';
            } else {
                $thumb = 'https://img.youtube.com/vi/' . $youtube_id . '/hqdefault.jpg';
                if (mb_strlen($title) > 255) $title = mb_substr($title, 0, 255);

                $stmt = $conn->prepare('INSERT INTO courses (title, description, language, youtube_url, youtube_id, thumbnail_url, is_published, sort_order, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())');
                if (!$stmt) {
                    $err = trim((string)($conn->error ?? ''));
                    $error = $err !== '' ? ('تعذر إضافة الكورس: ' . $err) : 'تعذر إضافة الكورس.';
                } else {
                    $stmt->bind_param('ssssssii', $title, $description, $language, $youtube_url, $youtube_id, $thumb, $is_published, $sort_order);
                    if ($stmt->execute()) {
                        $success = 'تمت إضافة الكورس بنجاح.';
                        $title = '';
                        $description = '';
                        $language = 'ar';
                        $youtube_url = '';
                        $sort_order = 0;
                        $is_published = 1;
                    } else {
                        $err = trim((string)($stmt->error ?? ''));
                        $error = $err !== '' ? ('تعذر حفظ الكورس: ' . $err) : 'تعذر حفظ الكورس. حاول مرة أخرى.';
                    }
                    $stmt->close();
                }
            }
        }
    } elseif ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            $error = 'معرّف غير صحيح.';
        } else {
            $stmt = $conn->prepare('DELETE FROM courses WHERE id = ? LIMIT 1');
            if ($stmt) {
                $stmt->bind_param('i', $id);
                $stmt->execute();
                $stmt->close();
                $success = 'تم حذف الكورس.';
            } else {
                $error = 'تعذر حذف الكورس.';
            }
        }
    } elseif ($action === 'toggle') {
        $id = (int)($_POST['id'] ?? 0);
        $next = (int)($_POST['next'] ?? 0);
        if ($id <= 0 || !in_array($next, [0,1], true)) {
            $error = 'طلب غير صحيح.';
        } else {
            $stmt = $conn->prepare('UPDATE courses SET is_published = ?, updated_at = NOW() WHERE id = ?');
            if ($stmt) {
                $stmt->bind_param('ii', $next, $id);
                $stmt->execute();
                $stmt->close();
                $success = 'تم تحديث حالة النشر.';
            } else {
                $error = 'تعذر تحديث حالة النشر.';
            }
        }
    }
}

$courses = [];
$total_courses = 0;
if ($conn) {
    $res = $conn->query('SELECT COUNT(*) AS c FROM courses');
    if ($res && ($row = $res->fetch_assoc())) {
        $total_courses = (int)$row['c'];
    }

    $stmt = $conn->prepare('SELECT id, title, description, language, youtube_url, youtube_id, thumbnail_url, is_published, sort_order, created_at FROM courses ORDER BY sort_order ASC, id DESC LIMIT 500');
    if ($stmt) {
        $stmt->execute();
        $r = $stmt->get_result();
        while ($row = $r->fetch_assoc()) {
            if (empty($row['thumbnail_url']) && !empty($row['youtube_id'])) {
                $row['thumbnail_url'] = 'https://img.youtube.com/vi/' . $row['youtube_id'] . '/hqdefault.jpg';
            }
            $courses[] = $row;
        }
        $stmt->close();
    }
}

$page_title = 'إدارة الكورسات';
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
  .admin-btn.primary{background:linear-gradient(135deg,#ffb74d,#ff9800);color:#3e2723;}
  .admin-body{padding:16px 18px 18px;}

  .admin-alert{border-radius:16px;padding:12px 14px;margin:0 0 14px;font-weight:900;}
  .admin-alert.error{background:#ffe8ea;color:#b00020;border:1px solid rgba(176,0,32,0.18);}
  .admin-alert.success{background:#e8f5e9;color:#1b5e20;border:1px solid rgba(27,94,32,0.18);}

  .admin-grid{display:grid;grid-template-columns: 1fr 1.1fr;gap:14px;align-items:start;}
  .admin-form{display:grid;gap:12px;}
  .admin-field label{display:block;margin:0 0 6px;color:#3e2723;font-weight:900;}
  .admin-field input,.admin-field textarea,.admin-field select{width:100%;border-radius:14px;border:1px solid rgba(62,39,35,0.14);padding:12px 12px;font-family:inherit;font-weight:800;outline:none;background:#fff;}
  .admin-field textarea{min-height:120px;resize:vertical;}
  .admin-field input:focus,.admin-field textarea:focus,.admin-field select:focus{border-color:#ffb74d;box-shadow:0 0 0 4px rgba(255,183,77,0.25);}
  .admin-row{display:grid;grid-template-columns:1fr 1fr;gap:10px;}
  .admin-actions-row{display:flex;gap:10px;flex-wrap:wrap;margin-top:6px;}

  .admin-table{width:100%;border-collapse:collapse;}
  .admin-table th,.admin-table td{padding:10px 10px;border-bottom:1px solid rgba(62,39,35,0.08);text-align:right;vertical-align:top;}
  .admin-table th{color:#3e2723;font-weight:1000;background:rgba(255,183,77,0.12);}
  .admin-badge{display:inline-flex;align-items:center;gap:8px;padding:6px 10px;border-radius:999px;background:rgba(255,183,77,0.20);border:1px solid rgba(62,39,35,0.10);color:#3e2723;font-weight:1000;font-size:12px;}
  .admin-badge.off{background:rgba(176,0,32,0.08);border-color:rgba(176,0,32,0.18);color:#b00020;}

  .course-thumb{width:96px;height:54px;object-fit:cover;border-radius:12px;border:1px solid rgba(62,39,35,0.10);background:#f6f2ef;display:block;}

  @media (max-width: 980px){
    .admin-grid{grid-template-columns:1fr;}
    .admin-row{grid-template-columns:1fr;}
  }
</style>

<div class="admin-shell">
  <div class="admin-card">
    <div class="admin-head">
      <h1>إدارة الكورسات</h1>
      <div class="admin-actions">
        <a class="admin-btn primary" href="index.php">لوحة الأدمن</a>
        <a class="admin-btn ghost" href="logout.php">تسجيل الخروج</a>
      </div>
    </div>

    <div class="admin-body">
      <?php if ($error !== ''): ?>
        <div class="admin-alert error"><?= e($error) ?></div>
      <?php elseif ($success !== ''): ?>
        <div class="admin-alert success"><?= e($success) ?></div>
      <?php endif; ?>

      <div class="admin-grid">
        <div>
          <div style="color:#8d6e63;font-weight:1000;margin:0 0 10px;">إجمالي الكورسات: <?= (int)$total_courses ?></div>

          <table class="admin-table">
            <thead>
              <tr>
                <th>الفيديو</th>
                <th>العنوان</th>
                <th>اللغة</th>
                <th>النشر</th>
                <th>ترتيب</th>
                <th>إجراء</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($courses)): ?>
                <tr><td colspan="6" style="color:#8d6e63;font-weight:900;">لا توجد كورسات بعد.</td></tr>
              <?php else: ?>
                <?php foreach ($courses as $c): ?>
                  <?php
                    $langLabel = ((string)($c['language'] ?? 'ar')) === 'en' ? 'English' : 'عربي';
                    $pub = (int)($c['is_published'] ?? 1);
                    $thumb = (string)($c['thumbnail_url'] ?? '');
                    $yt = (string)($c['youtube_url'] ?? '');
                  ?>
                  <tr>
                    <td>
                      <a href="<?= e($yt) ?>" target="_blank" rel="noopener noreferrer">
                        <img class="course-thumb" src="<?= e($thumb) ?>" alt="<?= e($c['title'] ?? '') ?>">
                      </a>
                    </td>
                    <td style="font-weight:1000;color:#3e2723;max-width:360px;">
                      <?= e($c['title'] ?? '') ?>
                    </td>
                    <td><span class="admin-badge"><?= e($langLabel) ?></span></td>
                    <td>
                      <?php if ($pub === 1): ?>
                        <span class="admin-badge">منشور</span>
                      <?php else: ?>
                        <span class="admin-badge off">مخفي</span>
                      <?php endif; ?>
                    </td>
                    <td><?= (int)($c['sort_order'] ?? 0) ?></td>
                    <td>
                      <div style="display:flex;gap:8px;flex-wrap:wrap;">
                        <form method="post" action="courses.php" style="margin:0;">
                          <input type="hidden" name="action" value="toggle">
                          <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
                          <input type="hidden" name="next" value="<?= $pub === 1 ? 0 : 1 ?>">
                          <button class="admin-btn ghost" type="submit"><?= $pub === 1 ? 'إخفاء' : 'نشر' ?></button>
                        </form>

                        <form method="post" action="courses.php" style="margin:0;" onsubmit="return confirm('حذف الكورس؟');">
                          <input type="hidden" name="action" value="delete">
                          <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
                          <button class="admin-btn ghost" type="submit" style="border-color:rgba(176,0,32,0.25);color:#b00020;">حذف</button>
                        </form>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <div>
          <div style="font-weight:1000;color:#3e2723;margin:0 0 10px;font-size:18px;">إضافة كورس جديد</div>
          <form class="admin-form" method="post" action="courses.php" novalidate>
            <input type="hidden" name="action" value="create">

            <div class="admin-field">
              <label>العنوان *</label>
              <input type="text" name="title" value="<?= e($title) ?>" required>
            </div>

            <div class="admin-field">
              <label>الوصف</label>
              <textarea name="description"><?= e($description) ?></textarea>
            </div>

            <div class="admin-row">
              <div class="admin-field">
                <label>اللغة *</label>
                <select name="language" required>
                  <option value="ar" <?= $language === 'ar' ? 'selected' : '' ?>>عربي</option>
                  <option value="en" <?= $language === 'en' ? 'selected' : '' ?>>English</option>
                </select>
              </div>

              <div class="admin-field">
                <label>الترتيب</label>
                <input type="number" name="sort_order" value="<?= (int)$sort_order ?>" step="1">
              </div>
            </div>

            <div class="admin-field">
              <label>رابط اليوتيوب *</label>
              <input type="text" name="youtube_url" value="<?= e($youtube_url) ?>" placeholder="https://www.youtube.com/watch?v=..." required>
              <div style="margin-top:6px;color:#8d6e63;font-weight:900;">تقدر تلصق رابط watch أو shorts أو youtu.be أو حتى ID فقط.</div>
            </div>

            <label style="display:flex;align-items:center;gap:10px;font-weight:1000;color:#3e2723;">
              <input type="checkbox" name="is_published" value="1" <?= $is_published ? 'checked' : '' ?>>
              نشر الكورس مباشرة
            </label>

            <div class="admin-actions-row">
              <button type="submit" class="admin-btn primary">إضافة</button>
              <a class="admin-btn ghost" href="<?= e('../courses.php') ?>" target="_blank" rel="noopener noreferrer">عرض صفحة الكورسات</a>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
