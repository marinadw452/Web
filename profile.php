<?php
$page_title = "الملف الشخصي";
require_once 'includes/header.php';
require_once 'includes/db_connect.php';

$errors = [];

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=" . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    $conn = db_connect();
    $errors = [];
    
    // Validate input
    if (empty($name)) {
        $errors[] = "حقل الاسم مطلوب";
    }
    if ($phone !== '' && !phone_has_min_digits($phone, 10)) {
        $errors[] = "رقم الجوال يجب أن لا يقل عن 10 أرقام";
    }
    
    // Update password if provided
    $wants_password_change = (trim($new_password) !== '' || trim($confirm_password) !== '');
    if ($wants_password_change) {
        if (trim($current_password) === '') {
            $errors[] = "يجب إدخال كلمة المرور الحالية";
        } elseif (trim($new_password) === '') {
            $errors[] = "يجب إدخال كلمة المرور الجديدة";
        } elseif (strlen($new_password) < 6) {
            $errors[] = "يجب أن تتكون كلمة المرور من 6 أحرف على الأقل";
        } elseif ($new_password !== $confirm_password) {
            $errors[] = "كلمة المرور الجديدة غير متطابقة";
        } else {
            // Verify current password
            $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->bind_param("i", $_SESSION['user_id']);
            $stmt->execute();
            $stmt->bind_result($db_password_hash);
            $stmt->fetch();
            $stmt->close();

            if (empty($db_password_hash)) {
                $errors[] = "المستخدم غير موجود";
            } elseif (!password_verify($current_password, $db_password_hash)) {
                $errors[] = "كلمة المرور الحالية غير صحيحة";
            }
        }
    }
    
    // Update data if no errors
    if (empty($errors)) {
        if ($wants_password_change) {
            // Update name, phone, and password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET name = ?, phone = ?, city = ?, address = ?, password = ? WHERE id = ?");
            if ($stmt) {
                $stmt->bind_param("sssssi", $name, $phone, $city, $address, $hashed_password, $_SESSION['user_id']);
            } else {
                $stmt = $conn->prepare("UPDATE users SET name = ?, phone = ?, password = ? WHERE id = ?");
                $stmt->bind_param("sssi", $name, $phone, $hashed_password, $_SESSION['user_id']);
            }
        } else {
            // Update name and phone only
            $stmt = $conn->prepare("UPDATE users SET name = ?, phone = ?, city = ?, address = ? WHERE id = ?");
            if ($stmt) {
                $stmt->bind_param("ssssi", $name, $phone, $city, $address, $_SESSION['user_id']);
            } else {
                $stmt = $conn->prepare("UPDATE users SET name = ?, phone = ? WHERE id = ?");
                $stmt->bind_param("ssi", $name, $phone, $_SESSION['user_id']);
            }
        }
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "تم تحديث الملف الشخصي بنجاح";
            $_SESSION['name'] = $name;
            header("Location: profile.php");
            exit;
        } else {
            $errors[] = "حدث خطأ أثناء تحديث الملف الشخصي: " . $conn->error;
        }
    }
}

// Fetch user data
$conn = db_connect();
$stmt = $conn->prepare("SELECT name, email, phone, city, address, created_at FROM users WHERE id = ?");
$has_address_fields = true;
if (!$stmt) {
    $has_address_fields = false;
    $stmt = $conn->prepare("SELECT name, email, phone, created_at FROM users WHERE id = ?");
}
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$db_city = '';
$db_address = '';
if ($has_address_fields) {
    $stmt->bind_result($db_name, $db_email, $db_phone, $db_city, $db_address, $db_created_at);
} else {
    $stmt->bind_result($db_name, $db_email, $db_phone, $db_created_at);
}
$stmt->fetch();
$stmt->close();

if (empty($db_email)) {
    echo '<div class="container" style="margin-top: 110px;">لا يمكن العثور على بيانات المستخدم.</div>';
    include 'includes/footer.php';
    exit;
}

$user = [
    'name' => $db_name,
    'email' => $db_email,
    'phone' => $db_phone,
    'city' => $db_city,
    'address' => $db_address,
    'created_at' => $db_created_at,
];

// Fetch user's orders
$orders_stmt = $conn->prepare("
    SELECT o.id, o.total_amount, o.status, o.created_at, 
           COUNT(oi.id) as items_count
    FROM orders o
    LEFT JOIN order_items oi ON o.id = oi.order_id
    WHERE o.user_id = ?
    GROUP BY o.id
    ORDER BY o.created_at DESC
    LIMIT 5
");
$orders_stmt->bind_param("i", $_SESSION['user_id']);
$orders_stmt->execute();
$orders_stmt->bind_result($order_id, $order_total_amount, $order_status, $order_created_at, $order_items_count);
$orders = [];
while ($orders_stmt->fetch()) {
    $orders[] = [
        'id' => $order_id,
        'total_amount' => $order_total_amount,
        'status' => $order_status,
        'created_at' => $order_created_at,
        'items_count' => $order_items_count,
    ];
}
$orders_stmt->close();
?>

<div class="profile-container heritage-bg">
    <div class="container">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger" style="background-color: #ffebee; color: #c62828; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-right: 4px solid #c62828;">
                <ul style="margin: 0; padding-right: 20px;">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success" style="background-color: #e8f5e9; color: #2e7d32; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-right: 4px solid #2e7d32;">
                <?php 
                    echo $_SESSION['success']; 
                    unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>

        <div class="profile-header" style="display: flex; align-items: center; margin-bottom: 30px; padding: 20px; background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
            <?php
            $initial = !empty($user['name']) ? strtoupper(substr($user['name'], 0, 1)) : 'U';
            $hue = intval($_SESSION['user_id']) * 30 % 360;
            $avatar_bg = "hsl({$hue}, 70%, 60%)";
            $text_color = "hsl({$hue}, 30%, 20%)";
            ?>
            <div class="avatar" style="width: 80px; height: 80px; border-radius: 50%; background-color: <?php echo $avatar_bg; ?>; color: <?php echo $text_color; ?>; display: flex; align-items: center; justify-content: center; font-size: 32px; font-weight: bold; margin-left: 20px; flex-shrink: 0;">
                <?php echo $initial; ?>
            </div>
            <div>
                <h2 style="margin: 0; color: #3e2723; font-weight: 700;"><?php echo htmlspecialchars($user['name']); ?></h2>
                <p style="margin: 5px 0 0; color: #5d4037; font-size: 0.9em;">
                    <i class="fas fa-envelope" style="margin-left: 5px;"></i> <?php echo htmlspecialchars($user['email']); ?>
                </p>
                <?php if (!empty($user['phone'])): ?>
                    <p style="margin: 5px 0 0; color: #5d4037; font-size: 0.9em;">
                        <i class="fas fa-phone" style="margin-left: 5px;"></i> <?php echo htmlspecialchars($user['phone']); ?>
                    </p>
                <?php endif; ?>
                <?php if (!empty($user['city'])): ?>
                    <p style="margin: 5px 0 0; color: #5d4037; font-size: 0.9em;">
                        <i class="fas fa-map-marker-alt" style="margin-left: 5px;"></i> <?php echo htmlspecialchars($user['city']); ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>

        <div class="row">
            <!-- Personal Information Section -->
            <div class="col-md-8">
                <div class="card" style="border: none; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 30px;">
                    <div class="card-header" style="background: #ba7d37; color: white; border-radius: 12px 12px 0 0; padding: 15px 20px; font-weight: 600;">
                        <i class="fas fa-user-edit" style="margin-left: 8px;"></i> تعديل الملف الشخصي
                    </div>
                    <div class="card-body" style="padding: 25px;">
                        <form method="post">

                            <div class="form-group" style="margin-bottom: 20px;">
                                <label for="name" style="display: block; margin-bottom: 8px; font-weight: 600; color: #3e2723;">الاسم الكامل</label>
                                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" 
                                       class="form-control" style="width: 100%; padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px; font-family: 'Tajawal', sans-serif; font-size: 15px; transition: all 0.3s;"
                                       required>
                            </div>

                            <div class="form-group" style="margin-bottom: 20px;">
                                <label for="phone" style="display: block; margin-bottom: 8px; font-weight: 600; color: #3e2723;">رقم الجوال</label>
                                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" 
                                       minlength="10" inputmode="numeric"
                                       class="form-control" style="width: 100%; padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px; font-family: 'Tajawal', sans-serif; font-size: 15px;">
                            </div>

                            <div class="form-group" style="margin-bottom: 20px;">
                                <label for="city" style="display: block; margin-bottom: 8px; font-weight: 600; color: #3e2723;">المدينة</label>
                                <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($user['city'] ?? ''); ?>" 
                                       class="form-control" style="width: 100%; padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px; font-family: 'Tajawal', sans-serif; font-size: 15px;">
                            </div>

                            <div class="form-group" style="margin-bottom: 20px;">
                                <label for="address" style="display: block; margin-bottom: 8px; font-weight: 600; color: #3e2723;">العنوان</label>
                                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>" 
                                       class="form-control" style="width: 100%; padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px; font-family: 'Tajawal', sans-serif; font-size: 15px;">
                            </div>

                            <div class="form-group" style="margin-bottom: 20px;">
                                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #3e2723;">البريد الإلكتروني</label>
                                <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" 
                                       class="form-control" style="width: 100%; padding: 10px 15px; border: 1px solid #eee; border-radius: 8px; background-color: #f9f9f9; color: #666; cursor: not-allowed;" disabled>
                                <small class="text-muted" style="font-size: 0.8em; color: #888; display: block; margin-top: 5px;">لا يمكن تغيير البريد الإلكتروني</small>
                            </div>

                            <hr style="margin: 25px 0; border-color: #eee;">

                            <h5 style="color: #3e2723; margin-bottom: 20px; font-weight: 600;">
                                <i class="fas fa-key" style="margin-left: 8px;"></i> تغيير كلمة المرور
                            </h5>

                            <div class="form-group" style="margin-bottom: 15px;">
                                <label for="current_password" style="display: block; margin-bottom: 8px; font-weight: 600; color: #3e2723;">كلمة المرور الحالية</label>
                                <input type="password" id="current_password" name="current_password" 
                                       class="form-control" style="width: 100%; padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px; font-family: 'Tajawal', sans-serif; font-size: 15px;">
                                <small class="text-muted" style="font-size: 0.8em; color: #888;">اتركه فارغاً إذا كنت لا تريد تغيير كلمة المرور</small>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" style="margin-bottom: 15px;">
                                        <label for="new_password" style="display: block; margin-bottom: 8px; font-weight: 600; color: #3e2723;">كلمة المرور الجديدة</label>
                                        <input type="password" id="new_password" name="new_password" 
                                               class="form-control" style="width: 100%; padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px; font-family: 'Tajawal', sans-serif; font-size: 15px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" style="margin-bottom: 15px;">
                                        <label for="confirm_password" style="display: block; margin-bottom: 8px; font-weight: 600; color: #3e2723;">تأكيد كلمة المرور</label>
                                        <input type="password" id="confirm_password" name="confirm_password" 
                                               class="form-control" style="width: 100%; padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px; font-family: 'Tajawal', sans-serif; font-size: 15px;">
                                    </div>
                                </div>
                            </div>

                            <div class="text-left" style="margin-top: 25px;">
                                <button type="submit" class="btn" style="background-color: #ba7d37; color: white; border: none; padding: 10px 25px; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                                    <i class="fas fa-save" style="margin-left: 8px;"></i> حفظ التغييرات
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Orders Section -->
            <div class="col-md-4">
                <div class="card" style="border: none; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                    <div class="card-header" style="background: #ba7d37; color: white; border-radius: 12px 12px 0 0; padding: 15px 20px; font-weight: 600;">
                        <i class="fas fa-shopping-bag" style="margin-left: 8px;"></i> طلباتي الأخيرة
                    </div>

                    <div class="card-body" style="padding: 0;">
                        <?php if (count($orders) > 0): ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($orders as $order): 
                                    $status_class = [
                                        'pending' => 'warning',
                                        'processing' => 'info',
                                        'shipped' => 'primary',
                                        'delivered' => 'success',
                                        'cancelled' => 'danger'
                                    ][$order['status']] ?? 'secondary';
                                    
                                    $status_text = [
                                        'pending' => 'قيد الانتظار',
                                        'processing' => 'قيد المعالجة',
                                        'shipped' => 'تم الشحن',
                                        'delivered' => 'تم التسليم',
                                        'cancelled' => 'ملغي'
                                    ][$order['status']] ?? $order['status'];
                                ?>
                                    <div 
                                       class="list-group-item"
                                       style="border: none; padding: 15px 20px; border-bottom: 1px solid #f0f0f0; background: transparent;">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1" style="font-weight: 400; color: #3e2723;">طلب #<?php echo $order['id']; ?></h6>
                                                <small class="text-muted"><?php echo date('Y/m/d', strtotime($order['created_at'])); ?></small>
                                            </div>
                                            <div class="text-left">
                                                <span class="badge" style="background-color: <?php 
                                                    echo [
                                                        'pending' => '#ffc107',
                                                        'processing' => '#17a2b8',
                                                        'shipped' => '#007bff',
                                                        'delivered' => '#28a745',
                                                        'cancelled' => '#dc3545'
                                                    ][$order['status']] ?? '#6c757d'; 
                                                ?>; color: white; padding: 5px 10px; border-radius: 20px; font-size: 0.75em; font-weight: 500;">
                                                    <?php echo $status_text; ?>
                                                </span>
                                                <div class="mt-1">
                                                    <small class="text-muted"><?php echo $order['items_count']; ?> منتج</small>
                                                    <span class="mx-1">•</span>
                                                    <small class="text-muted"><?php echo number_format($order['total_amount'], 2); ?> ر.س</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="card-footer text-center" style="background: #f9f9f9; border-top: 1px solid #f0f0f0; border-radius: 0 0 12px 12px; padding: 15px;">
                                <a href="my_orders.php" class="btn btn-sm" style="background-color: #f0f0f0; color: #5d4037; border: none; padding: 5px 20px; border-radius: 20px; font-weight: 500; transition: all 0.3s;">
                                    عرض كل الطلبات <i class="fas fa-arrow-left" style="margin-right: 5px;"></i>
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="text-center p-4" style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
                                <div style="width: 80px; height: 80px; background-color: #f8f9fa; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 15px;">
                                    <i class="fas fa-shopping-bag" style="font-size: 30px; color: #adb5bd;"></i>
                                </div>
                                <h6 style="color: #6c757d; margin-bottom: 10px;">لا توجد طلبات سابقة</h6>
                                <p class="text-muted" style="font-size: 0.9em; margin-bottom: 0;">لم تقم بإجراء أي طلبات حتى الآن</p>
                                <a href="products.php" class="btn" style="background-color: #ba7d37; color: white; border: none; padding: 10px 25px; border-radius: 10px; font-weight: 600; transition: all 0.3s; display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
                                    تصفح المنتجات <i class="fas fa-arrow-left" style="margin-right: 5px;"></i>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Account Info -->
                <div class="card mt-4" style="border: none; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                    <div class="card-header" style="background: #ba7d37; color: white; border-radius: 12px 12px 0 0; padding: 15px 20px; font-weight: 600;">
                        <i class="fas fa-info-circle" style="margin-left: 8px;"></i> معلومات الحساب
                    </div>

                    <div class="card-body">
                        <ul class="list-unstyled" style="margin: 0; padding: 0;">
                            <li style="padding: 10px 0; border-bottom: 1px solid #f0f0f0; display: flex; align-items: center;">
                                <i class="fas fa-calendar-alt" style="margin-left: 10px; color: #ba7d37; width: 20px; text-align: center;"></i>
                                <span style="color: #5d4037;">مسجل منذ: <?php echo date('Y/m/d', strtotime($user['created_at'])); ?></span>
                            </li>
                            <li style="padding: 10px 0; border-bottom: 1px solid #f0f0f0; display: flex; align-items: center;">
                                <i class="fas fa-envelope" style="margin-left: 10px; color: #ba7d37; width: 20px; text-align: center;"></i>
                                <span style="color: #5d4037;"><?php echo htmlspecialchars($user['email']); ?></span>
                            </li>
                            <?php if (!empty($user['phone'])): ?>
                            <li style="padding: 10px 0; display: flex; align-items: center;">
                                <i class="fas fa-phone" style="margin-left: 10px; color: #ba7d37; width: 20px; text-align: center;"></i>
                                <span style="color: #5d4037;"><?php echo htmlspecialchars($user['phone']); ?></span>
                            </li>
                            <?php endif; ?>
                            <?php if (!empty($user['city'])): ?>
                            <li style="padding: 10px 0; border-top: 1px solid #f0f0f0; display: flex; align-items: center;">
                                <i class="fas fa-map-marker-alt" style="margin-left: 10px; color: #ba7d37; width: 20px; text-align: center;"></i>
                                <span style="color: #5d4037;"><?php echo htmlspecialchars($user['city']); ?></span>
                            </li>
                            <?php endif; ?>
                            <?php if (!empty($user['address'])): ?>
                            <li style="padding: 10px 0; display: flex; align-items: center;">
                                <i class="fas fa-map-signs" style="margin-left: 10px; color: #ba7d37; width: 20px; text-align: center;"></i>
                                <span style="color: #5d4037;"><?php echo htmlspecialchars($user['address']); ?></span>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>