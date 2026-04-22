<?php
require_once __DIR__ . '/config.php';

$page_title = '🎁 منتج عشوائي';
include __DIR__ . '/includes/header.php';

$conn = db();

function e($s){
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

function product_image_src(string $img): string {
    $img = trim($img);
    if ($img === '') return 'images/products/placeholder.jpg';
    if (preg_match('#^(https?://|uploads/)#i', $img)) return $img;
    return 'images/products/' . $img;
}

$product = null;
$error = '';

if (!$conn) {
    $error = 'تعذر الاتصال بقاعدة البيانات.';
} else {
    $sql = "SELECT p.id, p.name, p.price, p.discount_price, p.image_url, p.sizes, p.seller_id, c.name AS cat_name, s.store_name
            FROM products p
            LEFT JOIN categories c ON c.id = p.category_id
            LEFT JOIN sellers s ON s.id = p.seller_id
            WHERE p.status = 'PUBLISHED'
            ORDER BY RAND()
            LIMIT 1";
    $res = $conn->query($sql);
    if ($res && ($row = $res->fetch_assoc())) {
        $product = $row;
    }
}
?>

<style>
  .rp-shell{max-width:1100px;margin:110px auto 60px;padding:0 16px;}
  .rp-card{background:#fff;border:1px solid rgba(62,39,35,0.08);border-radius:22px;box-shadow:0 18px 45px rgba(62,39,35,0.08);overflow:hidden;}
  .rp-head{padding:18px 18px 14px;background:linear-gradient(135deg, rgba(186,125,55,0.16), rgba(255,183,77,0.10));border-bottom:1px solid rgba(62,39,35,0.08);}
  .rp-head h1{margin:0;color:#3e2723;font-size:24px;font-weight:1000;}
  .rp-sub{margin:8px 0 0;color:#8d6e63;font-weight:900;}
  .rp-body{padding:16px 18px 18px;}

  .rp-grid{display:grid;grid-template-columns: 1fr 1fr;gap:14px;align-items:start;}
  .rp-actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:10px;}
  .rp-btn{border:none;border-radius:999px;padding:11px 16px;font-weight:1000;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;justify-content:center;}
  .rp-btn.primary{background:linear-gradient(135deg,#ffb74d,#ff9800);color:#3e2723;}
  .rp-btn.ghost{background:#fff;border:1px solid rgba(62,39,35,0.14);color:#3e2723;}

  .rp-alert{border-radius:16px;padding:12px 14px;margin:0 0 14px;font-weight:900;}
  .rp-alert.error{background:#ffe8ea;color:#b00020;border:1px solid rgba(176,0,32,0.18);}

  @media (max-width: 980px){
    .rp-grid{grid-template-columns:1fr;}
  }
</style>

<div class="rp-shell">
  <div class="rp-card">
    <div class="rp-head">
      <h1>🎁 منتج عشوائي — خلّيني أفاجئك</h1>
      <p class="rp-sub">مو عارف وش تختار؟ اضغط الزر وأنا أختار لك قطعة هاند ميد عشوائية.</p>
    </div>

    <div class="rp-body">
      <?php if ($error !== ''): ?>
        <div class="rp-alert error"><?= e($error) ?></div>
      <?php endif; ?>

      <div class="rp-actions" style="margin-top:0;">
        <a class="rp-btn primary" href="random_product.php?t=<?= time() ?>">اختيار الآن</a>
        <a class="rp-btn ghost" href="gift_assistant.php">مساعد اختيار الهدية</a>
      </div>

      <div style="margin-top:10px;color:#6d4c41;font-weight:900;">الدفع: تقدر تطلب وتدفع عند الاستلام.</div>

      <div style="margin-top:14px;">
        <?php if ($product): ?>
          <div style="color:#3e2723;font-weight:1000;margin:0 0 10px;">تم اختيار منتج لك!</div>

          <?php
            $final_price = $product['discount_price'] ?? $product['price'];
            $has_discount = $product['discount_price'] && $product['discount_price'] < $product['price'];
            $imgSrc = product_image_src((string)($product['image_url'] ?? ''));
          ?>

          <div class="product-card" style="max-width:420px;background:white;border-radius:20px;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,0.1);transition:.4s;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform=''">
            <a href="product.php?id=<?= (int)$product['id'] ?>" style="text-decoration:none;">
              <div class="card-image" style="position:relative;">
                <img src="<?= e($imgSrc) ?>" alt="<?= e($product['name'] ?? '') ?>" style="width:100%;height:300px;object-fit:cover;" onerror="this.src='images/products/placeholder.jpg'">
                <div class="badge" style="position:absolute;top:15px;right:15px;background:#ff9800;color:white;padding:6px 14px;border-radius:30px;font-size:13px;font-weight:bold;">مفاجأة</div>
              </div>
            </a>

            <div class="card-content" style="padding:18px;">
              <span style="font-size:14px;color:#ba7d37;margin-bottom:8px;display:block;"><?= e($product['cat_name'] ?? '') ?></span>
              <?php if (!empty($product['store_name']) && (int)($product['seller_id'] ?? 0) > 0): ?>
                <a href="store.php?id=<?= (int)$product['seller_id'] ?>" style="display:block;margin-top:-2px;margin-bottom:6px;color:#5d4037;font-weight:700;text-decoration:none;">
                  البائع: <span style="color:#ba7d37;text-decoration:underline;"><?= e($product['store_name']) ?></span>
                </a>
              <?php endif; ?>

              <a href="product.php?id=<?= (int)$product['id'] ?>" style="text-decoration:none;">
                <h3 style="margin:8px 0;font-size:1.2rem;color:#3e2723;"><?= e($product['name'] ?? '') ?></h3>
              </a>

              <div style="margin:12px 0;font-size:1.25rem;font-weight:bold;color:#d81b60;">
                <?php if ($has_discount): ?>
                  <span style="text-decoration:line-through;color:#999;font-size:0.95rem;"><?= number_format((float)$product['price'], 0) ?></span>
                  <span><?= number_format((float)$final_price, 0) ?> ريال</span>
                <?php else: ?>
                  <?= number_format((float)$final_price, 0) ?> ريال
                <?php endif; ?>
              </div>

              <div style="display:flex;gap:10px;align-items:center;">
                <button type="button" class="add-to-cart" data-add-to-cart="1" data-id="<?= (int)$product['id'] ?>"
                        data-name="<?= e($product['name'] ?? '') ?>"
                        data-image="<?= e($imgSrc) ?>"
                        data-price="<?= e((string)$final_price) ?>"
                        data-sizes="<?= e((string)($product['sizes'] ?? '')) ?>"
                        style="flex:1;background:linear-gradient(135deg,#ffb74d,#ff9800);color:white;border:none;padding:12px;border-radius:50px;font-weight:bold;cursor:pointer;transition:.3s;"
                        onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform=''">
                  أضف للسلة
                </button>

                <a href="random_product.php?t=<?= time() ?>" class="rp-btn ghost" style="padding:12px 14px;">اختيار جديد</a>
              </div>
            </div>
          </div>
        <?php else: ?>
          <div style="margin-top:12px;color:#8d6e63;font-weight:1000;">لا توجد منتجات متاحة حالياً.</div>
        <?php endif; ?>
      </div>

    </div>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
