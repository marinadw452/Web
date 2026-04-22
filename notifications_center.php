<?php
require_once 'includes/header.php';
require_once 'config.php';

// التحقق من تسجيل دخول العميل
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header('Location: login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit();
}

// التحقق من وجود منتجات في السلة
$cart_items = json_decode($_COOKIE['cart'] ?? '[]', true);
if (empty($cart_items)) {
    header('Location: cart.php');
    exit();
}

// جلب تفاصيل المنتجات من قاعدة البيانات
$product_ids = array_keys($cart_items);
$products = [];
$total_amount = 0;

if (!empty($product_ids)) {
    $placeholders = str_repeat('?,', count($product_ids) - 1) . '?';
    $stmt = $conn->prepare("SELECT * FROM products WHERE id IN ($placeholders) AND published = 1");
    $stmt->bind_param(str_repeat('i', count($product_ids)), ...$product_ids);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($product = $result->fetch_assoc()) {
        $product_id = $product['id'];
        $quantity = $cart_items[$product_id]['quantity'] ?? 1;
        $size = $cart_items[$product_id]['size'] ?? '';
        $color = $cart_items[$product_id]['color'] ?? '';
        
        $subtotal = $product['price'] * $quantity;
        $total_amount += $subtotal;
        
        $products[] = [
            'id' => $product_id,
            'name' => $product['name'],
            'price' => $product['price'],
            'image' => $product['image'],
            'quantity' => $quantity,
            'size' => $size,
            'color' => $color,
            'subtotal' => $subtotal,
            'seller_id' => $product['seller_id']
        ];
    }
}

// معالجة تقديم الطلب
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name = trim($_POST['customer_name']);
    $customer_phone = trim($_POST['customer_phone']);
    $customer_address = trim($_POST['customer_address']);
    $customer_city = trim($_POST['customer_city']);
    $notes = trim($_POST['notes'] ?? '');
    
    // التحقق من البيانات
    if (empty($customer_name) || empty($customer_phone) || empty($customer_address) || empty($customer_city)) {
        $error = 'يرجى ملء جميع الحقول المطلوبة';
    } elseif (!phone_has_min_digits($customer_phone, 10)) {
        $error = 'رقم الهاتف يجب أن لا يقل عن 10 أرقام';
    } else {
        // إنشاء رقم طلب فريد
        $order_number = 'ORD' . date('Ymd') . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        // حساب تاريخ التسليم التقريبي (15 يوم من الآن)
        $estimated_delivery_date = date('Y-m-d', strtotime('+15 days'));
        
        // تجميع المنتجات حسب البائع
        $orders_by_seller = [];
        foreach ($products as $product) {
            $seller_id = $product['seller_id'];
            if (!isset($orders_by_seller[$seller_id])) {
                $orders_by_seller[$seller_id] = [
                    'products' => [],
                    'total' => 0
                ];
            }
            $orders_by_seller[$seller_id]['products'][] = $product;
            $orders_by_seller[$seller_id]['total'] += $product['subtotal'];
        }
        
        // إنشاء طلب لكل بائع
        $conn->begin_transaction();
        try {
            foreach ($orders_by_seller as $seller_id => $seller_order) {
                // إنشاء الطلب الرئيسي
                $stmt = $conn->prepare("INSERT INTO orders (order_number, user_id, seller_id, total_amount, customer_name, customer_phone, customer_address, customer_city, notes, estimated_delivery_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param('siidssssss', $order_number, $_SESSION['user_id'], $seller_id, $seller_order['total'], $customer_name, $customer_phone, $customer_address, $customer_city, $notes, $estimated_delivery_date);
                $stmt->execute();
                $order_id = $stmt->insert_id;
                
                // إضافة المنتجات للطلب
                foreach ($seller_order['products'] as $product) {
                    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, product_price, quantity, size, color, subtotal) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param('iisidssd', $order_id, $product['id'], $product['name'], $product['price'], $product['quantity'], $product['size'], $product['color'], $product['subtotal']);
                    $stmt->execute();
                }
                
                // إضافة سجل تتبع أولي
                $stmt = $conn->prepare("INSERT INTO order_tracking (order_id, status, description, created_by) VALUES (?, 'pending', 'تم استلام الطلب وجاري تجهيزه', ?)");
                $stmt->bind_param('ii', $order_id, $seller_id);
                $stmt->execute();
                
                // إرسال إشعار للبائع
                $notification_title = 'طلب جديد';
                $notification_message = "لديك طلب جديد (#$order_number) من العميل $customer_name بقيمة " . number_format($seller_order['total'], 2) . " ريال";
                $stmt = $conn->prepare("INSERT INTO seller_notifications (seller_id, order_id, type, title, message) VALUES (?, ?, 'new_order', ?, ?)");
                $stmt->bind_param('iiss', $seller_id, $order_id, $notification_title, $notification_message);
                $stmt->execute();
            }
            
            $conn->commit();
            
            // تفريغ السلة
            setcookie('cart', '', time() - 3600, '/');
            
            // توجيه لصفحة نجاح
            header('Location: order_success.php?order=' . $order_number);
            exit();
            
        } catch (Exception $e) {
            $conn->rollback();
            $error = 'حدث خطأ أثناء إنشاء الطلب. يرجى المحاولة مرة أخرى.';
        }
    }
}

$page_title = 'إتمام الطلب';
?>

<div class="checkout-container">
    <div class="checkout-header">
        <h1>إتمام الطلب</h1>
        <p>يرجى تأكيد بياناتك لإتمام عملية الطلب</p>
    </div>

    <?php if (isset($error)): ?>
        <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="checkout-content">
        <div class="order-summary">
            <h2>ملخص الطلب</h2>
            <div class="cart-items">
                <?php foreach ($products as $product): ?>
                    <div class="cart-item">
                        <img src="images/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                        <div class="item-details">
                            <h3><?= htmlspecialchars($product['name']) ?></h3>
                            <p>السعر: <?= number_format($product['price'], 2) ?> ريال</p>
                            <p>الكمية: <?= $product['quantity'] ?></p>
                            <?php if ($product['size']): ?>
                                <p>المقاس: <?= htmlspecialchars($product['size']) ?></p>
                            <?php endif; ?>
                            <?php if ($product['color']): ?>
                                <p>اللون: <?= htmlspecialchars($product['color']) ?></p>
                            <?php endif; ?>
                            <p class="subtotal">الإجمالي: <?= number_format($product['subtotal'], 2) ?> ريال</p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="total-section">
                <h3>الإجمالي الكلي: <?= number_format($total_amount, 2) ?> ريال</h3>
                <p>طريقة الدفع: الدفع عند الاستلام</p>
                <p>تاريخ التسليم المتوقع: <?= date('Y-m-d', strtotime('+15 days')) ?></p>
            </div>
        </div>

        <div class="customer-info">
            <h2>معلومات العميل</h2>
            <form method="POST" class="checkout-form">
                <div class="form-group">
                    <label for="customer_name">الاسم الكامل *</label>
                    <input type="text" id="customer_name" name="customer_name" required
                           value="<?= htmlspecialchars($_POST['customer_name'] ?? $_SESSION['name'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="customer_phone">رقم الهاتف *</label>
                    <input type="tel" id="customer_phone" name="customer_phone" required minlength="10" inputmode="numeric"
                           value="<?= htmlspecialchars($_POST['customer_phone'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="customer_city">المدينة *</label>
                    <input type="text" id="customer_city" name="customer_city" required
                           value="<?= htmlspecialchars($_POST['customer_city'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="customer_address">العنوان بالتفصيل *</label>
                    <textarea id="customer_address" name="customer_address" required rows="3"><?= htmlspecialchars($_POST['customer_address'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label for="notes">ملاحظات إضافية (اختياري)</label>
                    <textarea id="notes" name="notes" rows="3"><?= htmlspecialchars($_POST['notes'] ?? '') ?></textarea>
                </div>

                <button type="submit" class="submit-order-btn">تأكيد الطلب</button>
            </form>
        </div>
    </div>
</div>

<style>
.checkout-container {
    max-width: 1200px;
    margin: 120px auto 40px;
    padding: 0 20px;
}

.checkout-header {
    text-align: center;
    margin-bottom: 40px;
}

.checkout-header h1 {
    color: #3e2723;
    font-size: 2.5rem;
    margin-bottom: 10px;
}

.checkout-header p {
    color: #5d4037;
    font-size: 1.1rem;
}

.error-message {
    background: #ffebee;
    color: #c62828;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 30px;
    text-align: center;
}

.checkout-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
}

.order-summary, .customer-info {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.order-summary h2, .customer-info h2 {
    color: #3e2723;
    margin-bottom: 20px;
    font-size: 1.5rem;
}

.cart-item {
    display: flex;
    gap: 15px;
    padding: 15px 0;
    border-bottom: 1px solid #f0f0f0;
}

.cart-item img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
}

.item-details h3 {
    color: #3e2723;
    margin-bottom: 5px;
}

.item-details p {
    color: #5d4037;
    margin: 3px 0;
    font-size: 0.9rem;
}

.subtotal {
    font-weight: bold !important;
    color: #ba7d37 !important;
}

.total-section {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 2px solid #f0f0f0;
}

.total-section h3 {
    color: #ba7d37;
    font-size: 1.3rem;
    margin-bottom: 10px;
}

.total-section p {
    color: #5d4037;
    margin: 5px 0;
}

.checkout-form .form-group {
    margin-bottom: 20px;
}

.checkout-form label {
    display: block;
    color: #3e2723;
    font-weight: 600;
    margin-bottom: 8px;
}

.checkout-form input,
.checkout-form textarea {
    width: 100%;
    padding: 12px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.3s;
}

.checkout-form input:focus,
.checkout-form textarea:focus {
    outline: none;
    border-color: #ba7d37;
}

.submit-order-btn {
    width: 100%;
    background: linear-gradient(135deg, #ffb74d, #ff9800);
    color: #3e2723;
    border: none;
    padding: 15px;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: bold;
    cursor: pointer;
    transition: transform 0.3s, box-shadow 0.3s;
}

.submit-order-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 183, 77, 0.4);
}

@media (max-width: 768px) {
    .checkout-content {
        grid-template-columns: 1fr;
        gap: 30px;
    }
    
    .checkout-container {
        margin: 100px auto 30px;
        padding: 0 15px;
    }
    
    .order-summary, .customer-info {
        padding: 20px;
    }
}
</style>

<?php require_once 'includes/footer.php'; ?>
