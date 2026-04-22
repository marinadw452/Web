<?php 
require_once 'config.php';
if(session_status() === PHP_SESSION_NONE) session_start();

$page_title = "جميع المنتجات - أيدي طيّبة";
$base = '';
include 'includes/header.php';

$conn = db();
if (!$conn) {
    die('خطأ في الاتصال بقاعدة البيانات');
}

$hasSellersTable = $conn->query("SHOW TABLES LIKE 'sellers'");
$sellersAvailable = ($hasSellersTable && $hasSellersTable->num_rows === 1);

// فلاتر
$category = $_GET['cat'] ?? '';
$search   = trim($_GET['search'] ?? '');
$sort     = $_GET['sort'] ?? 'newest';

// بناء الاستعلام
$sql = "SELECT p.id, p.name, p.price, p.discount_price, p.image_url, p.sizes, c.name as cat_name, p.seller_id";
if ($sellersAvailable) {
    $sql .= ", s.store_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN sellers s ON s.id = p.seller_id
        WHERE p.status = 'PUBLISHED' AND 1=1";
} else {
    $sql .= ", NULL as store_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.status = 'PUBLISHED' AND 1=1";
}

$params = [];
$types  = "";

// فلتر الفئة
if ($category && in_array($category, ['نساء','رجالي','أطفال','أثاث','إكسسوارات'])) {
    $sql .= " AND c.name = ?";
    $params[] = $category;
    $types .= "s";
}

// البحث
if ($search !== '') {
    $sql .= " AND p.name LIKE ?";
    $params[] = "%$search%";
    $types .= "s";
}

// الترتيب
switch($sort) {
    case 'price_low':  $sql .= " ORDER BY COALESCE(p.discount_price, p.price) ASC"; break;
    case 'price_high': $sql .= " ORDER BY COALESCE(p.discount_price, p.price) DESC"; break;
    case 'name':       $sql .= " ORDER BY p.name ASC"; break;
    default:           $sql .= " ORDER BY p.created_at DESC"; break;
}

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die('خطأ في إعداد الاستعلام: ' . $conn->error);
}

if ($types) {
    $bind = [];
    $bind[] = $types;
    foreach ($params as $k => $v) {
        $bind[] = &$params[$k];
    }
    call_user_func_array([$stmt, 'bind_param'], $bind);
}

$stmt->execute();
$stmt->bind_result($pid, $pname, $pprice, $pdiscount, $pimageUrl, $pSizes, $pcatName, $psellerId, $pstoreName);

$products = [];
while ($stmt->fetch()) {
    $products[] = [
        'id' => $pid,
        'name' => $pname,
        'price' => $pprice,
        'discount_price' => $pdiscount,
        'image_url' => $pimageUrl,
        'sizes' => $pSizes,
        'cat_name' => $pcatName,
        'seller_id' => $psellerId,
        'store_name' => $pstoreName,
    ];
}
$stmt->close();
?>

<section class="hero" style="height:45vh; position:relative; margin-top:90px; border-radius:28px; overflow:hidden;">
  <video autoplay muted loop playsinline preload="metadata" poster="images/products-hero.jpg" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center 70%;z-index:0;">
    <source src="images/mm%20.mp4" type="video/mp4">
  </video>
  <div class="overlay"></div>
  <div class="hero-content" style="bottom:20%;">
    <h1 style="font-size:4rem;">جميع المنتجات</h1>
    <p style="font-size:1.4rem;">اكتشفي أجمل القطع اليدوية من أيدي طيّبة</p>
  </div>
</section>

<section class="products-page" style="padding:60px 20px;background:#fdf9f3;">
  <div class="container" style="max-width:1400px;margin:auto;">

    <!-- شريط الفلاتر -->
    <div style="margin-bottom:40px;background:white;padding:20px;border-radius:20px;box-shadow:0 10px 30px rgba(0,0,0,0.08);display:flex;flex-wrap:wrap;gap:15px;align-items:center;justify-content:space-between;">
      
      <!-- البحث -->
      <form method="GET" style="flex:1;min-width:250px;">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
               placeholder="ابحثي عن منتج..." 
               style="width:100%;padding:14px 20px;border-radius:50px;border:2px solid #eee;font-size:16px;">
        <?php if($category): ?><input type="hidden" name="cat" value="<?= $category ?>"><?php endif; ?>
      </form>

      <!-- الفئات -->
      <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <a href="products.php" class="<?= !$category?'active':'' ?>" style="padding:10px 20px;border-radius:50px;background:#fff;border:2px solid #ffb74d;color:#d81b60;font-weight:bold;text-decoration:none;">الكل</a>
        <?php
        $cats = ['نساء','رجالي','أطفال','أثاث','إكسسوارات'];
        foreach($cats as $c): ?>
          <a href="products.php?cat=<?= urlencode($c) ?>" 
             class="<?= $category==$c?'active':'' ?>"
             style="padding:10px 20px;border-radius:50px;background:#fff;border:2px solid #eee;color:#5d4037;text-decoration:none;transition:.3s;">
            <?= $c ?>
          </a>
        <?php endforeach; ?>
      </div>

      <!-- الترتيب -->
      <select onchange="location='products.php?'+this.value" style="padding:12px 20px;border-radius:50px;border:2px solid #eee;background:white;font-size:15px;">
        <option value="" <?= $sort=='newest'?'selected':'' ?>>الأحدث</option>
        <option value="sort=price_low" <?= $sort=='price_low'?'selected':'' ?>>السعر: من الأقل</option>
        <option value="sort=price_high" <?= $sort=='price_high'?'selected':'' ?>>السعر: من الأعلى</option>
        <option value="sort=name" <?= $sort=='name'?'selected':'' ?>>الاسم أ-ي</option>
      </select>
    </div>

    <!-- المنتجات -->
    <?php if($products): ?>
      <div class="products-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:30px;">
        <?php foreach($products as $p):
          $final_price = $p['discount_price'] ?? $p['price'];
          $has_discount = $p['discount_price'] && $p['discount_price'] < $p['price'];

          $img = trim((string)($p['image_url'] ?? ''));
          if ($img === '') {
              $imgSrc = 'images/products/placeholder.jpg';
          } elseif (preg_match('#^(https?://|uploads/)#i', $img)) {
              $imgSrc = $img;
          } else {
              $imgSrc = 'images/products/' . $img;
          }
        ?>
          <div class="product-card" style="background:white;border-radius:20px;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,0.1);transition:.4s;" onmouseover="this.style.transform='translateY(-12px)'" onmouseout="this.style.transform=''">
            <a href="product.php?id=<?= (int)$p['id'] ?>" style="text-decoration:none;">
              <div class="card-image" style="position:relative;">
                <img src="<?= htmlspecialchars($imgSrc) ?>" 
                     alt="<?= htmlspecialchars($p['name']) ?>" style="width:100%;height:320px;object-fit:cover;">
                <div class="badge" style="position:absolute;top:15px;right:15px;background:#ff9800;color:white;padding:6px 14px;border-radius:30px;font-size:13px;font-weight:bold;">جديد</div>
              </div>
            </a>
            <div class="card-content" style="padding:20px;">
              <span style="font-size:14px;color:#ba7d37;margin-bottom:8px;display:block;"><?= htmlspecialchars($p['cat_name']) ?></span>
              <?php if (!empty($p['store_name']) && (int)$p['seller_id'] > 0): ?>
                <a href="store.php?id=<?= (int)$p['seller_id'] ?>" style="display:block;margin-top:-2px;margin-bottom:6px;color:#5d4037;font-weight:700;text-decoration:none;">
                  البائع: <span style="color:#ba7d37;text-decoration:underline;"><?= htmlspecialchars($p['store_name']) ?></span>
                </a>
              <?php endif; ?>
              <a href="product.php?id=<?= (int)$p['id'] ?>" style="text-decoration:none;">
                <h3 style="margin:8px 0;font-size:1.3rem;color:#3e2723;"><?= htmlspecialchars($p['name']) ?></h3>
              </a>
              
              <div style="margin:15px 0;font-size:1.4rem;font-weight:bold;color:#d81b60;">
                <?php if($has_discount): ?>
                  <span style="text-decoration:line-through;color:#999;font-size:1rem;"><?= number_format($p['price']) ?></span>
                  <span><?= number_format($final_price) ?> ريال</span>
                <?php else: ?>
                  <?= number_format($final_price) ?> ريال
                <?php endif; ?>
              </div>

              <div style="display:flex;gap:10px;align-items:center;">
                <button type="button" class="add-to-cart" data-add-to-cart="1" data-id="<?= (int)$p['id'] ?>"
                        data-name="<?= htmlspecialchars($p['name'], ENT_QUOTES, 'UTF-8') ?>"
                        data-image="<?= htmlspecialchars($imgSrc, ENT_QUOTES, 'UTF-8') ?>"
                        data-price="<?= htmlspecialchars((string)$final_price, ENT_QUOTES, 'UTF-8') ?>"
                        data-sizes="<?= htmlspecialchars((string)($p['sizes'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                        style="flex:1;background:linear-gradient(135deg,#ffb74d,#ff9800);color:white;border:none;padding:14px;border-radius:50px;font-weight:bold;cursor:pointer;transition:.3s;"
                        onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform=''">
                  أضف للسلة
                </button>

                <button type="button" class="fav-btn" data-fav="1" data-id="<?= (int)$p['id'] ?>"
                        data-name="<?= htmlspecialchars($p['name'], ENT_QUOTES, 'UTF-8') ?>"
                        data-image="<?= htmlspecialchars($imgSrc, ENT_QUOTES, 'UTF-8') ?>"
                        data-price="<?= htmlspecialchars((string)$final_price, ENT_QUOTES, 'UTF-8') ?>"
                        aria-pressed="false" title="إضافة للمفضلة">
                  ♥
                </button>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div style="text-align:center;padding:60px;color:#8d6e63;">
        <h3>لا توجد منتجات حاليًا في هذا القسم</h3>
        <p>تابعينا قريبًا، بنضيف كل يوميًا!</p>
      </div>
    <?php endif; ?>

  </div>
</section>

<script>
// تحديث روابط الفلتر مع الحفاظ على البحث
document.querySelector('select').addEventListener('change', function() {
  let url = 'products.php?';
  if('<?= $category ?>') url += 'cat=<?= $category ?>&';
  if('<?= $search ?>') url += 'search=<?= urlencode($search) ?>&';
  location = url + this.value;
});
</script>

<?php include 'includes/footer.php'; ?>