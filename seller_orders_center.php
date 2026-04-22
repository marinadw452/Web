<?php
require_once __DIR__ . '/config.php';

if (!isLoggedIn() || (string)($_SESSION['role'] ?? '') !== 'seller') {
    redirect('login.php?redirect=' . urlencode($_SERVER['REQUEST_URI'] ?? 'seller_orders_center.php'));
}

$conn = db();
$page_title = 'طلبات المتجر';
include __DIR__ . '/includes/header.php';

$seller_id = (int)($_SESSION['user_id'] ?? 0);
$focus_id = (int)($_GET['order_id'] ?? 0);

function e($s){
  return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

function status_label(string $st): string {
  switch ($st) {
    case 'pending': return 'قيد الانتظار';
    case 'preparing': return 'قيد التجهيز';
    case 'shipped': return 'تم الشحن';
    case 'delivered': return 'تم التسليم';
    case 'cancelled': return 'ملغي';
    default: return $st;
  }
}

$orders = [];
if ($conn) {
  if ($focus_id > 0) {
    $stmt = $conn->prepare('SELECT id, order_group, order_number, user_id, subtotal_amount, delivery_fee, total_amount, status, customer_name, customer_phone, customer_city, customer_address, notes, created_at FROM orders WHERE id = ? AND seller_id = ? LIMIT 1');
    if ($stmt) {
      $stmt->bind_param('ii', $focus_id, $seller_id);
      $stmt->execute();
      $res = $stmt->get_result();
      if ($row = $res->fetch_assoc()) $orders[] = $row;
      $stmt->close();
    }
  } else {
    $stmt = $conn->prepare('SELECT id, order_group, order_number, user_id, subtotal_amount, delivery_fee, total_amount, status, customer_name, customer_phone, customer_city, created_at FROM orders WHERE seller_id = ? ORDER BY created_at DESC, id DESC LIMIT 200');
    if ($stmt) {
      $stmt->bind_param('i', $seller_id);
      $stmt->execute();
      $res = $stmt->get_result();
      while ($row = $res->fetch_assoc()) $orders[] = $row;
      $stmt->close();
    }
  }
}

$itemsByOrder = [];
if ($conn && $orders) {
  foreach ($orders as $o) {
    $oid = (int)$o['id'];
    $itemsByOrder[$oid] = [];
    $st = $conn->prepare('SELECT product_name, quantity, size, subtotal FROM order_items WHERE order_id = ? ORDER BY id ASC');
    if ($st) {
      $st->bind_param('i', $oid);
      $st->execute();
      $r = $st->get_result();
      while ($it = $r->fetch_assoc()) $itemsByOrder[$oid][] = $it;
      $st->close();
    }
  }
}
?>

<style>
  .so-shell{max-width:1100px;margin:110px auto 60px;padding:0 16px;}
  .so-card{background:#fff;border:1px solid rgba(62,39,35,0.08);border-radius:22px;box-shadow:0 18px 45px rgba(62,39,35,0.08);overflow:hidden;}
  .so-head{padding:18px 18px 14px;background:linear-gradient(135deg, rgba(186,125,55,0.16), rgba(255,183,77,0.10));border-bottom:1px solid rgba(62,39,35,0.08);display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;}
  .so-head h1{margin:0;color:#3e2723;font-size:24px;font-weight:1000;}
  .so-body{padding:14px 18px 18px;}
  .so-empty{padding:26px;text-align:center;color:#8d6e63;font-weight:900;}
  .so-order{border:1px solid rgba(62,39,35,0.08);border-radius:18px;padding:12px 14px;margin:10px 0;background:#fff;}
  .so-top{display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;}
  .so-num{font-weight:1000;color:#3e2723;}
  .so-status{font-weight:1000;}
  .so-meta{margin-top:8px;color:#8d6e63;font-weight:800;font-size:13px;display:flex;gap:10px;flex-wrap:wrap;}
  .so-items{margin-top:10px;border-top:1px dashed rgba(62,39,35,0.18);padding-top:10px;}
  .so-item{display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;margin:6px 0;color:#6d4c41;font-weight:800;}
  .so-actions{margin-top:12px;display:flex;gap:10px;flex-wrap:wrap;align-items:center;}
  .so-btn{border:none;border-radius:999px;padding:10px 14px;font-weight:1000;cursor:pointer;}
  .so-btn.primary{background:linear-gradient(135deg,#ffb74d,#ff9800);color:#3e2723;}
  .so-btn.ghost{background:#fff;border:1px solid rgba(62,39,35,0.14);color:#3e2723;}
  .so-select,.so-input{padding:10px 12px;border:1px solid rgba(62,39,35,0.14);border-radius:14px;font-family:'Tajawal',sans-serif;font-weight:800;}
</style>

<div class="so-shell">
  <div class="so-card">
    <div class="so-head">
      <h1>طلبات المتجر</h1>
      <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <a href="seller/dashboard.php" class="so-btn ghost" style="text-decoration:none;display:inline-flex;align-items:center;justify-content:center;">لوحة البائع</a>
      </div>
    </div>
    <div class="so-body">
      <?php if (!$orders): ?>
        <div class="so-empty">لا توجد طلبات حالياً.</div>
      <?php else: ?>
        <?php foreach ($orders as $o):
          $oid = (int)$o['id'];
          $status = (string)$o['status'];
        ?>
          <div class="so-order">
            <div class="so-top">
              <div class="so-num">طلب: <?= e($o['order_number']) ?></div>
              <div class="so-status" style="color:<?= $status==='cancelled'?'#b00020':($status==='delivered'?'#2e7d32':'#ba7d37') ?>;">
                <?= e(status_label($status)) ?>
              </div>
            </div>
            <div class="so-meta">
              <span>العميل: <?= e($o['customer_name'] ?? '') ?></span>
              <span>الجوال: <?= e($o['customer_phone'] ?? '') ?></span>
              <span>المدينة: <?= e($o['customer_city'] ?? '') ?></span>
              <span>الإجمالي: <?= number_format((float)$o['total_amount'], 2) ?> ريال</span>
              <span>تاريخ: <?= e($o['created_at']) ?></span>
            </div>

            <div class="so-items">
              <?php foreach (($itemsByOrder[$oid] ?? []) as $it):
                $size = trim((string)($it['size'] ?? ''));
              ?>
                <div class="so-item">
                  <span><?= e($it['product_name']) ?><?= $size!=='' ? (' — المقاس: ' . e($size)) : '' ?></span>
                  <span>× <?= (int)$it['quantity'] ?> — <?= number_format((float)$it['subtotal'], 2) ?> ريال</span>
                </div>
              <?php endforeach; ?>
            </div>

            <div class="so-actions">
              <select class="so-select" data-status="1" data-id="<?= $oid ?>">
                <option value="pending" <?= $status==='pending'?'selected':'' ?>>قيد الانتظار</option>
                <option value="preparing" <?= $status==='preparing'?'selected':'' ?>>قيد التجهيز</option>
                <option value="shipped" <?= $status==='shipped'?'selected':'' ?>>تم الشحن</option>
                <option value="delivered" <?= $status==='delivered'?'selected':'' ?>>تم التسليم</option>
                <option value="cancelled" <?= $status==='cancelled'?'selected':'' ?>>ملغي</option>
              </select>
              <input class="so-input" data-notes="1" data-id="<?= $oid ?>" type="text" placeholder="ملاحظة (اختياري)">
              <button class="so-btn primary" type="button" data-save="1" data-id="<?= $oid ?>">حفظ الحالة</button>
              <a class="so-btn ghost" href="seller_orders_center.php?order_id=<?= $oid ?>" style="text-decoration:none;">عرض</a>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>

<script>
(function(){
  const base = (window.__basePath || '');

  async function api(action, payload){
    const res = await fetch(base + 'orders_api.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept':'application/json' },
      body: JSON.stringify({ action, ...(payload||{}) })
    });
    return res.json();
  }

  document.body.addEventListener('click', async (ev) => {
    const btn = ev.target.closest('[data-save="1"]');
    if (!btn) return;
    const id = Number(btn.getAttribute('data-id') || 0);
    if (!id) return;

    const sel = document.querySelector('[data-status="1"][data-id="' + id + '"]');
    const inp = document.querySelector('[data-notes="1"][data-id="' + id + '"]');
    const status = sel ? sel.value : 'pending';
    const notes = inp ? inp.value : '';

    btn.disabled = true;
    const data = await api('seller_update_status', {order_id: id, status, notes});
    if (!data.ok){
      alert(data.error || 'فشل التحديث');
      btn.disabled = false;
      return;
    }
    location.reload();
  });
})();
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
