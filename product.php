<?php
require_once __DIR__ . '/config.php';

$page_title = 'الكورسات التعليمية';
include __DIR__ . '/includes/header.php';

$conn = db();

$lang = strtolower((string)($_GET['lang'] ?? 'all'));
if (!in_array($lang, ['all', 'ar', 'en'], true)) {
    $lang = 'all';
}

function e($s){
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

$courses = [];
$error = '';

if (!$conn) {
    $error = 'تعذر الاتصال بقاعدة البيانات.';
} else {
    if ($lang === 'all') {
        $stmt = $conn->prepare("SELECT id, title, description, language, youtube_url, youtube_id, thumbnail_url, sort_order, created_at FROM courses WHERE is_published = 1 ORDER BY sort_order ASC, id DESC LIMIT 500");
    } else {
        $stmt = $conn->prepare("SELECT id, title, description, language, youtube_url, youtube_id, thumbnail_url, sort_order, created_at FROM courses WHERE is_published = 1 AND language = ? ORDER BY sort_order ASC, id DESC LIMIT 500");
        if ($stmt) {
            $stmt->bind_param('s', $lang);
        }
    }

    if ($stmt) {
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            if (empty($row['thumbnail_url']) && !empty($row['youtube_id'])) {
                $row['thumbnail_url'] = 'https://img.youtube.com/vi/' . $row['youtube_id'] . '/hqdefault.jpg';
            }
            $courses[] = $row;
        }
        $stmt->close();
    }
}
?>

<style>
  .courses-shell{max-width:1200px;margin:110px auto 60px;padding:0 16px;}
  .courses-card{background:#fff;border:1px solid rgba(62,39,35,0.08);border-radius:22px;box-shadow:0 18px 45px rgba(62,39,35,0.08);overflow:hidden;}
  .courses-head{padding:18px 18px 14px;background:linear-gradient(135deg, rgba(186,125,55,0.16), rgba(255,183,77,0.10));border-bottom:1px solid rgba(62,39,35,0.08);display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;}
  .courses-head h1{margin:0;color:#3e2723;font-size:24px;font-weight:1000;}
  .courses-sub{margin:0;color:#8d6e63;font-weight:900;}
  .courses-body{padding:16px 18px 18px;}

  .courses-alert{border-radius:16px;padding:12px 14px;margin:0 0 14px;font-weight:900;}
  .courses-alert.error{background:#ffe8ea;color:#b00020;border:1px solid rgba(176,0,32,0.18);}

  .courses-filters{display:flex;gap:10px;flex-wrap:wrap;}
  .courses-chip{display:inline-flex;align-items:center;gap:8px;padding:10px 14px;border-radius:999px;text-decoration:none;font-weight:1000;cursor:pointer;border:1px solid rgba(62,39,35,0.14);color:#3e2723;background:#fff;}
  .courses-chip.active{background:linear-gradient(135deg,#ffb74d,#ff9800);border-color:transparent;}

  .courses-grid{display:grid;grid-template-columns:repeat(auto-fit, minmax(260px, 1fr));gap:14px;margin-top:14px;}
  .course{border:1px solid rgba(62,39,35,0.08);border-radius:18px;background:#fff;overflow:hidden;box-shadow:0 10px 25px rgba(62,39,35,0.06);transition:transform 180ms ease, box-shadow 180ms ease, border-color 180ms ease;}
  .course:hover{transform:translateY(-3px);box-shadow:0 16px 38px rgba(62,39,35,0.10);border-color:rgba(255,183,77,0.55);}

  .course-thumb{position:relative;display:block;text-decoration:none;}
  .course-thumb img{width:100%;height:160px;object-fit:cover;display:block;background:#f6f2ef;}
  .course-play{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;}
  .course-play span{width:54px;height:54px;border-radius:18px;background:rgba(255,255,255,0.9);border:1px solid rgba(62,39,35,0.10);display:flex;align-items:center;justify-content:center;box-shadow:0 18px 40px rgba(62,39,35,0.16);transition:transform 180ms ease;}
  .course:hover .course-play span{transform:scale(1.06) rotate(-4deg);}

  .course-body{padding:12px 12px 14px;}
  .course-title{margin:0 0 8px;color:#3e2723;font-size:18px;font-weight:1000;line-height:1.35;}
  .course-desc{margin:0;color:#8d6e63;font-weight:900;line-height:1.6;min-height:48px;}

  .course-meta{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-top:10px;}
  .course-lang{display:inline-flex;align-items:center;gap:8px;padding:6px 10px;border-radius:999px;background:rgba(255,183,77,0.20);border:1px solid rgba(62,39,35,0.10);color:#3e2723;font-weight:1000;font-size:12px;}
  .course-open{display:inline-flex;align-items:center;gap:8px;text-decoration:none;font-weight:1000;color:#5d4037;}
  .course-open:hover{color:#ff9800;}

  @media (max-width: 520px){
    .course-thumb img{height:150px;}
  }
</style>

<div class="courses-shell">
  <div class="courses-card">
    <div class="courses-head">
      <div>
        <h1>الكورسات التعليمية</h1>
        <p class="courses-sub">اختر اللغة واستعرض الكورسات — بالضغط على الكرت تنتقل مباشرة لليوتيوب.</p>
      </div>

      <div class="courses-filters" role="tablist" aria-label="فلتر اللغة">
        <a class="courses-chip <?= $lang === 'all' ? 'active' : '' ?>" href="courses.php?lang=all">الكل</a>
        <a class="courses-chip <?= $lang === 'ar' ? 'active' : '' ?>" href="courses.php?lang=ar">عربي</a>
        <a class="courses-chip <?= $lang === 'en' ? 'active' : '' ?>" href="courses.php?lang=en">English</a>
      </div>
    </div>

    <div class="courses-body">
      <?php if ($error !== ''): ?>
        <div class="courses-alert error"><?= e($error) ?></div>
      <?php endif; ?>

      <?php if (empty($courses)): ?>
        <div style="color:#8d6e63;font-weight:1000;">لا توجد كورسات حالياً.</div>
      <?php else: ?>
        <div class="courses-grid">
          <?php foreach ($courses as $c): ?>
            <?php
              $title = (string)($c['title'] ?? '');
              $desc = (string)($c['description'] ?? '');
              $yt = (string)($c['youtube_url'] ?? '');
              $thumb = (string)($c['thumbnail_url'] ?? '');
              $langLabel = ((string)($c['language'] ?? 'ar')) === 'en' ? 'English' : 'عربي';
              if (mb_strlen($desc) > 160) $desc = mb_substr($desc, 0, 160) . '...';
            ?>

            <div class="course">
              <a class="course-thumb" href="<?= e($yt) ?>" target="_blank" rel="noopener noreferrer">
                <img src="<?= e($thumb) ?>" alt="<?= e($title) ?>">
                <div class="course-play" aria-hidden="true">
                  <span>
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#3e2723" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M8 5v14l11-7z" fill="#3e2723"></path>
                    </svg>
                  </span>
                </div>
              </a>

              <div class="course-body">
                <h2 class="course-title"><?= e($title) ?></h2>
                <p class="course-desc"><?= e($desc) ?></p>

                <div class="course-meta">
                  <div class="course-lang"><?= e($langLabel) ?></div>
                  <a class="course-open" href="<?= e($yt) ?>" target="_blank" rel="noopener noreferrer">
                    مشاهدة
                    <span aria-hidden="true">↗</span>
                  </a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

    </div>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
