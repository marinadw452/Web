<?php
require_once 'config.php';

$page_title = 'متجر البائع';
include 'includes/header.php';

$conn = db();
if (!$conn) {
    die('خطأ في الاتصال بقاعدة البيانات');
}

$hasSellersTable = $conn->query("SHOW TABLES LIKE 'sellers'");
$sellersAvailable = ($hasSellersTable && $hasSellersTable->num_rows === 1);

if (!$sellersAvailable) {
    echo '<div class="container" style="margin-top:110px;">صفحة البائع غير متاحة حالياً لأن جدول sellers غير موجود.</div>';
    include 'includes/footer.php';
    exit;
}

$seller_id = (int)($_GET['id'] ?? 0);
if ($seller_id <= 0) {
    echo '<div class="container" style="margin-top:110px;">معرّف متجر غير صالح.</div>';
    include 'includes/footer.php';
    exit;
}

// Seller info
$stmt = $conn->prepare('SELECT s.id, s.store_name, s.store_description, s.city, s.seller_type, s.is_handmade, s.background_url, s.logo_url, s.verification_status, u.name as owner_name FROM sellers s JOIN users u ON u.id = s.id WHERE s.id = ? LIMIT 1');
if (!$stmt) {
    echo '<div class="container" style="margin-top:110px;">تعذر تحميل المتجر.</div>';
    include 'includes/footer.php';
    exit;
}
$stmt->bind_param('i', $seller_id);
$stmt->execute();
$stmt->bind_result($sid, $store_name, $store_description, $city, $seller_type, $is_handmade, $background_url, $logo_url, $verification_status, $owner_name);
if (!$stmt->fetch()) {
    $stmt->close();
    echo '<div class="container" style="margin-top:110px;">المتجر غير موجود.</div>';
    include 'includes/footer.php';
    exit;
}
$stmt->close();

$bgStyle = '';
if (!empty($background_url)) {
    $bg = htmlspecialchars($background_url, ENT_QUOTES, 'UTF-8');
    $bgStyle = "background:linear-gradient(rgba(0,0,0,0.45),rgba(0,0,0,0.55)),url('{$bg}');background-size:cover;background-position:center;";
} else {
    $bgStyle = "background:linear-gradient(135deg, #3e2723, #ba7d37);";
}

// Products
$products = [];
$stmt = $conn->prepare("SELECT p.id, p.name, p.description, p.materials, p.sizes, p.price, p.discount_price, p.stock, p.image_url, c.name as category_name
    FROM products p
    LEFT JOIN categories c ON c.id = p.category_id
    WHERE p.seller_id = ? AND p.status = 'PUBLISHED'
    ORDER BY p.sort_order ASC, p.created_at DESC");
if ($stmt) {
    $stmt->bind_param('i', $seller_id);
    $stmt->execute();
    $stmt->bind_result($pid, $pname, $pdesc, $pmaterials, $psizes, $pprice, $pdiscount, $pstock, $pimg, $pcat);
    while ($stmt->fetch()) {
        $products[] = [
            'id' => $pid,
            'name' => $pname,
            'description' => $pdesc,
            'materials' => $pmaterials,
            'sizes' => $psizes,
            'price' => $pprice,
            'discount_price' => $pdiscount,
            'stock' => $pstock,
            'image_url' => $pimg,
            'category_name' => $pcat,
        ];
    }
    $stmt->close();
}

function e($s){
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}
?>

<section style="margin-top:90px;">
  <div style="<?= $bgStyle ?>padding:46px 20px;">
    <div class="container" style="max-width:1200px;">
      <div style="display:flex;flex-wrap:wrap;gap:18px;align-items:flex-end;justify-content:space-between;">
        <div>
          <h1 style="margin:0;color:#fff;font-size:34px;"><?= e($store_name) ?></h1>
          <div style="margin-top:10px;color:rgba(255,255,255,0.92);font-weight:700;display:flex;gap:14px;flex-wrap:wrap;">
            <span>المدينة: <?= e($city ?: 'غير محدد') ?></span>
            <span>النوع: <?= $seller_type === 'company' ? 'شركة' : 'فرد' ?></span>
            <span>هاند ميد: <?= (int)$is_handmade ? 'نعم' : 'لا' ?></span>
          </div>
          <?php if (!empty($store_description)): ?>
            <p style="margin:14px 0 0;color:rgba(255,255,255,0.9);max-width:740px;line-height:1.9;font-weight:600;">
              <?= nl2br(e($store_description)) ?>
            </p>
          <?php endif; ?>
        </div>

        <div style="color:rgba(255,255,255,0.9);font-weight:800;">
          <div>صاحب المتجر: <?= e($owner_name) ?></div>
        </div>
      </div>
    </div>
  </div>
</section>

<section style="padding:36px 0;background:#fdf9f3;">
  <div class="container" style="max-width:1200px;">
    <h2 style="margin:0 0 18px;color:#3e2723;">منتجات المتجر</h2>

    <?php if (empty($products)): ?>
      <div style="background:#fff;border:1px solid rgba(62,39,35,0.08);border-radius:18px;padding:18px;color:#8d6e63;font-weight:800;">
        لا توجد منتجات منشورة حالياً.
      </div>
    <?php else: ?>
      <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:18px;">
        <?php foreach($products as $p):
          $final = $p['discount_price'] !== null && $p['discount_price'] !== '' ? $p['discount_price'] : $p['price'];
          $has_discount = $p['discount_price'] !== null && $p['discount_price'] !== '' && (float)$p['discount_price'] < (float)$p['price'];
          $img = $p['image_url'] ? e($p['image_url']) : 'images/products/placeholder.jpg';
        ?>
          <div style="background:#fff;border:1px solid rgba(62,39,35,0.08);border-radius:18px;overflow:hidden;box-shadow:0 10px 25px rgba(0,0,0,0.06);">
            <a href="product.php?id=<?= (int)$p['id'] ?>" style="display:block;text-decoration:none;">
              <div style="position:relative;aspect-ratio:4/3;background:#f3f4f6;">
                <img src="<?= $img ?>" alt="<?= e($p['name']) ?>" style="width:100%;height:100%;object-fit:cover;display:block;" onerror="this.src='images/products/placeholder.jpg'">
                <?php if (!empty($p['category_name'])): ?>
                  <div style="position:absolute;top:12px;right:12px;background:rgba(17,24,39,0.9);color:#fff;padding:8px 10px;border-radius:999px;font-weight:900;font-size:12px;">
                    <?= e($p['category_name']) ?>
                  </div>
                <?php endif; ?>
              </div>
            </a>
            <div style="padding:14px;">
              <a href="product.php?id=<?= (int)$p['id'] ?>" style="text-decoration:none;color:inherit;display:block;">
                <div style="font-weight:900;color:#3e2723;font-size:16px;line-height:1.7;min-height:54px;"><?= e($p['name']) ?></div>
              </a>
              <div style="margin-top:8px;color:#6d4c41;font-weight:700;font-size:13px;">الخامات: <?= e($p['materials'] ?: '-') ?></div>
              <?php if (!empty($p['sizes'])): ?>
                <div style="margin-top:6px;color:#8d6e63;font-weight:700;font-size:13px;">الأحجام: <?= e($p['sizes']) ?></div>
              <?php endif; ?>

              <div style="margin-top:10px;display:flex;align-items:center;justify-content:space-between;gap:10px;">
                <div style="font-weight:1000;color:#3e2723;font-size:18px;">
                  <?php if ($has_discount): ?>
                    <span style="text-decoration:line-through;color:#999;font-size:13px;"><?= number_format((float)$p['price'], 0) ?></span>
                    <span><?= number_format((float)$final, 0) ?> ريال</span>
                  <?php else: ?>
                    <?= number_format((float)$final, 0) ?> ريال
                  <?php endif; ?>
                </div>
                <div style="color:#8d6e63;font-weight:800;font-size:13px;">المخزون: <?= (int)$p['stock'] ?></div>
              </div>

              <div style="margin-top:12px;display:flex;gap:10px;align-items:center;">
                <button type="button" data-add-to-cart="1"
                        data-id="<?= (int)$p['id'] ?>"
                        data-name="<?= e($p['name']) ?>"
                        data-image="<?= $img ?>"
                        data-price="<?= e((string)$final) ?>"
                        data-sizes="<?= e((string)($p['sizes'] ?? '')) ?>"
                        style="flex:1;background:linear-gradient(135deg,#ffb74d,#ff9800);color:#3e2723;border:none;padding:12px 14px;border-radius:999px;font-weight:900;cursor:pointer;">
                  أضف للسلة
                </button>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
