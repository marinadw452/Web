<?php
// تفعيل عرض الأخطاء للتطوير
error_reporting(E_ALL);
ini_set('display_errors', 1);

// إعدادات قاعدة البيانات
$host     = 'localhost';
$user     = 'root';
$password = 'root'; // MAMP = root | XAMPP = ''
$database = 'shop_db';
$port     = 3306;

// بدء الجلسة إذا لم تبدأ
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// تعيين المنطقة الزمنية
date_default_timezone_set('Asia/Riyadh');

// الاتصال بقاعدة البيانات
$conn = new mysqli($host, $user, $password, $database, $port);

if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// Best-effort schema migrations (non-destructive)
function ensure_schema(mysqli $conn): void {
    // Users
    $hasUsersTable = $conn->query("SHOW TABLES LIKE 'users'");
    if (!$hasUsersTable || $hasUsersTable->num_rows === 0) {
        $conn->query("CREATE TABLE IF NOT EXISTS users (
            id INT(11) NOT NULL AUTO_INCREMENT,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL,
            phone VARCHAR(50) DEFAULT NULL,
            role VARCHAR(50) NOT NULL DEFAULT 'user',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY uniq_users_email (email)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    // Ensure role column exists (older databases)
    $roleCol = $conn->query("SHOW COLUMNS FROM users LIKE 'role'");
    if ($roleCol && $roleCol->num_rows === 0) {
        $conn->query("ALTER TABLE users ADD COLUMN role VARCHAR(50) NOT NULL DEFAULT 'user'");
    }

    // Seed default admin user (login via normal login.php)
    $ADMIN_USER = 'admin';
    $ADMIN_EMAIL = 'admin@local.test';
    $ADMIN_PASS = 'Admin@1234';
    $checkAdmin = $conn->prepare("SELECT id FROM users WHERE email = ? OR name = ? LIMIT 1");
    if ($checkAdmin) {
        $checkAdmin->bind_param('ss', $ADMIN_EMAIL, $ADMIN_USER);
        $checkAdmin->execute();
        $resAdmin = $checkAdmin->get_result();
        $exists = ($resAdmin && $resAdmin->num_rows > 0);
        $checkAdmin->close();

        if (!$exists) {
            $hash = password_hash($ADMIN_PASS, PASSWORD_DEFAULT);
            $ins = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'admin')");
            if ($ins) {
                $ins->bind_param('sss', $ADMIN_USER, $ADMIN_EMAIL, $hash);
                $ins->execute();
                $ins->close();
            }
        }
    }

    // Categories
    $conn->query("CREATE TABLE IF NOT EXISTS categories (\n        id INT(11) NOT NULL AUTO_INCREMENT,\n        name VARCHAR(100) NOT NULL,\n        PRIMARY KEY (id),\n        UNIQUE KEY uniq_categories_name (name)\n    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    $conn->query("INSERT IGNORE INTO categories (name) VALUES ('نساء'),('رجالي'),('أطفال'),('أثاث'),('إكسسوارات'),('كورسات تعليمية')");

    // Sellers
    $hasSellersTable = $conn->query("SHOW TABLES LIKE 'sellers'");
    if (!$hasSellersTable || $hasSellersTable->num_rows === 0) {
        $conn->query("CREATE TABLE IF NOT EXISTS sellers (
            id INT(11) NOT NULL,
            store_name VARCHAR(255) NOT NULL,
            store_description TEXT DEFAULT NULL,
            city VARCHAR(100) DEFAULT NULL,
            seller_type ENUM('individual','company') NOT NULL DEFAULT 'individual',
            is_handmade TINYINT(1) NOT NULL DEFAULT 1,
            background_url VARCHAR(255) DEFAULT NULL,
            logo_url VARCHAR(255) DEFAULT NULL,
            verification_status VARCHAR(50) DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    $hasSellersTable = $conn->query("SHOW TABLES LIKE 'sellers'");
    if ($hasSellersTable && $hasSellersTable->num_rows === 1) {
        $sellerCols = [
            'store_description' => "ALTER TABLE sellers ADD COLUMN store_description TEXT DEFAULT NULL",
            'city' => "ALTER TABLE sellers ADD COLUMN city VARCHAR(100) DEFAULT NULL",
            'seller_type' => "ALTER TABLE sellers ADD COLUMN seller_type ENUM('individual','company') NOT NULL DEFAULT 'individual'",
            'is_handmade' => "ALTER TABLE sellers ADD COLUMN is_handmade TINYINT(1) NOT NULL DEFAULT 1",
            'background_url' => "ALTER TABLE sellers ADD COLUMN background_url VARCHAR(255) DEFAULT NULL",
            'logo_url' => "ALTER TABLE sellers ADD COLUMN logo_url VARCHAR(255) DEFAULT NULL",
            'verification_status' => "ALTER TABLE sellers ADD COLUMN verification_status VARCHAR(50) DEFAULT NULL",
            'delivery_fee' => "ALTER TABLE sellers ADD COLUMN delivery_fee DECIMAL(10,2) NOT NULL DEFAULT 0.00",
        ];
        foreach ($sellerCols as $col => $sql) {
            $res = $conn->query("SHOW COLUMNS FROM sellers LIKE '" . $conn->real_escape_string($col) . "'");
            if ($res && $res->num_rows === 0) {
                $conn->query($sql);
            }
        }
    }

    // Products columns
    $hasProductsTable = $conn->query("SHOW TABLES LIKE 'products'");
    if (!$hasProductsTable || $hasProductsTable->num_rows === 0) {
        $conn->query("CREATE TABLE IF NOT EXISTS products (\n            id INT(11) NOT NULL AUTO_INCREMENT,\n            name VARCHAR(255) NOT NULL,\n            price DECIMAL(10,2) NOT NULL DEFAULT 0.00,\n            discount_price DECIMAL(10,2) DEFAULT NULL,\n            image_url VARCHAR(255) DEFAULT NULL,\n            sizes VARCHAR(255) DEFAULT NULL,\n            materials VARCHAR(255) DEFAULT NULL,\n            category_id INT(11) DEFAULT NULL,\n            seller_id INT(11) DEFAULT NULL,\n            status ENUM('DRAFT','PUBLISHED','ARCHIVED') NOT NULL DEFAULT 'PUBLISHED',\n            sort_order INT(11) NOT NULL DEFAULT 0,\n            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,\n            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,\n            PRIMARY KEY (id),\n            KEY idx_products_category_id (category_id),\n            KEY idx_products_seller_id (seller_id),\n            KEY idx_products_status (status)\n        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    $productCols = [
        'description' => "ALTER TABLE products ADD COLUMN description TEXT DEFAULT NULL",
        'materials' => "ALTER TABLE products ADD COLUMN materials VARCHAR(255) DEFAULT NULL",
        'sizes' => "ALTER TABLE products ADD COLUMN sizes VARCHAR(255) DEFAULT NULL",
        'discount_price' => "ALTER TABLE products ADD COLUMN discount_price DECIMAL(10,2) DEFAULT NULL",
        'category_id' => "ALTER TABLE products ADD COLUMN category_id INT(11) DEFAULT NULL",
        'seller_id' => "ALTER TABLE products ADD COLUMN seller_id INT(11) DEFAULT NULL",
        'stock' => "ALTER TABLE products ADD COLUMN stock INT(11) NOT NULL DEFAULT 0",
        'image_url' => "ALTER TABLE products ADD COLUMN image_url VARCHAR(255) DEFAULT NULL",
        'status' => "ALTER TABLE products ADD COLUMN status ENUM('DRAFT','PUBLISHED','ARCHIVED') NOT NULL DEFAULT 'PUBLISHED'",
        'sort_order' => "ALTER TABLE products ADD COLUMN sort_order INT(11) NOT NULL DEFAULT 0",
        'created_at' => "ALTER TABLE products ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP",
        'updated_at' => "ALTER TABLE products ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
    ];

    foreach ($productCols as $col => $sql) {
        $res = $conn->query("SHOW COLUMNS FROM products LIKE '" . $conn->real_escape_string($col) . "'");
        if ($res && $res->num_rows === 0) {
            $conn->query($sql);
        }
    }

    // Backfill (optional)
    $hasLegacyCategory = $conn->query("SHOW COLUMNS FROM products LIKE 'category'");
    $hasCategoryId = $conn->query("SHOW COLUMNS FROM products LIKE 'category_id'");
    if ($hasLegacyCategory && $hasLegacyCategory->num_rows === 1 && $hasCategoryId && $hasCategoryId->num_rows === 1) {
        $conn->query("UPDATE products p\n            LEFT JOIN categories c ON c.name = p.category\n            SET p.category_id = COALESCE(p.category_id, c.id)\n            WHERE p.category_id IS NULL AND p.category IS NOT NULL AND p.category <> ''");
    }

    $hasLegacyImage = $conn->query("SHOW COLUMNS FROM products LIKE 'image'");
    $hasImageUrl = $conn->query("SHOW COLUMNS FROM products LIKE 'image_url'");
    if ($hasLegacyImage && $hasLegacyImage->num_rows === 1 && $hasImageUrl && $hasImageUrl->num_rows === 1) {
        $conn->query("UPDATE products SET image_url = COALESCE(image_url, image) WHERE image_url IS NULL AND image IS NOT NULL AND image <> ''");
    }

    // Orders
    $conn->query("CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_group VARCHAR(50) DEFAULT NULL,
        order_number VARCHAR(50) UNIQUE NOT NULL,
        user_id INT NOT NULL,
        seller_id INT NOT NULL,
        subtotal_amount DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
        delivery_fee DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
        total_amount DECIMAL(10, 2) NOT NULL,
        status ENUM('pending', 'preparing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
        payment_method ENUM('cash_on_delivery') DEFAULT 'cash_on_delivery',
        payment_status ENUM('pending', 'paid') DEFAULT 'pending',
        customer_name VARCHAR(255) NOT NULL,
        customer_phone VARCHAR(20) NOT NULL,
        customer_address TEXT NOT NULL,
        customer_city VARCHAR(100) NOT NULL,
        notes TEXT,
        estimated_delivery_date DATE,
        actual_delivery_date DATE NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        KEY idx_orders_user_id (user_id),
        KEY idx_orders_seller_id (seller_id),
        KEY idx_orders_status (status),
        KEY idx_orders_created_at (created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // Orders columns (migrations)
    $orderCols = [
        'order_group' => "ALTER TABLE orders ADD COLUMN order_group VARCHAR(50) DEFAULT NULL",
        'subtotal_amount' => "ALTER TABLE orders ADD COLUMN subtotal_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00",
        'delivery_fee' => "ALTER TABLE orders ADD COLUMN delivery_fee DECIMAL(10,2) NOT NULL DEFAULT 0.00",
    ];
    foreach ($orderCols as $col => $sql) {
        $res = $conn->query("SHOW COLUMNS FROM orders LIKE '" . $conn->real_escape_string($col) . "'");
        if ($res && $res->num_rows === 0) {
            $conn->query($sql);
        }
    }

    // Normalize/upgrade status ENUMs (non-destructive)
    $desiredOrderStatusEnum = "ENUM('pending','preparing','processing','shipped','delivered','cancelled','canceled')";
    $orderStatusRes = $conn->query("SHOW COLUMNS FROM orders LIKE 'status'");
    if ($orderStatusRes && $orderStatusRes->num_rows === 1) {
        $orderStatusRow = $orderStatusRes->fetch_assoc();
        $orderStatusType = strtolower((string)($orderStatusRow['Type'] ?? ''));
        if (strpos($orderStatusType, 'enum(') === 0 && (strpos($orderStatusType, "'cancelled'") === false || strpos($orderStatusType, "'preparing'") === false)) {
            $conn->query("ALTER TABLE orders MODIFY COLUMN status $desiredOrderStatusEnum NOT NULL DEFAULT 'pending'");
        }
    }

    // Order items
    $conn->query("CREATE TABLE IF NOT EXISTS order_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT NOT NULL,
        product_id INT NOT NULL,
        product_name VARCHAR(255) NOT NULL,
        product_price DECIMAL(10, 2) NOT NULL,
        quantity INT NOT NULL,
        size VARCHAR(50) NULL,
        color VARCHAR(50) NULL,
        subtotal DECIMAL(10, 2) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        KEY idx_order_items_order_id (order_id),
        KEY idx_order_items_product_id (product_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // Order tracking
    $conn->query("CREATE TABLE IF NOT EXISTS order_tracking (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT NOT NULL,
        status ENUM('pending', 'preparing', 'processing', 'shipped', 'delivered', 'cancelled', 'canceled') NOT NULL,
        description TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        created_by INT NOT NULL,
        KEY idx_tracking_order_id (order_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $desiredTrackingStatusEnum = "ENUM('pending','preparing','processing','shipped','delivered','cancelled','canceled')";
    $trackingStatusRes = $conn->query("SHOW COLUMNS FROM order_tracking LIKE 'status'");
    if ($trackingStatusRes && $trackingStatusRes->num_rows === 1) {
        $trackingStatusRow = $trackingStatusRes->fetch_assoc();
        $trackingStatusType = strtolower((string)($trackingStatusRow['Type'] ?? ''));
        if (strpos($trackingStatusType, 'enum(') === 0 && (strpos($trackingStatusType, "'cancelled'") === false || strpos($trackingStatusType, "'preparing'") === false)) {
            $conn->query("ALTER TABLE order_tracking MODIFY COLUMN status $desiredTrackingStatusEnum NOT NULL");
        }
    }

    // Notifications (unified for customer + seller)
    $conn->query("CREATE TABLE IF NOT EXISTS notifications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        order_id INT NULL,
        type VARCHAR(50) NOT NULL,
        title VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        url VARCHAR(255) DEFAULT NULL,
        is_read TINYINT(1) NOT NULL DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        KEY idx_notifications_user_id (user_id),
        KEY idx_notifications_is_read (is_read),
        KEY idx_notifications_created_at (created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // Contact messages
    $conn->query("CREATE TABLE IF NOT EXISTS contact_messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NULL,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        phone VARCHAR(50) NULL,
        subject VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        ip_address VARCHAR(64) NULL,
        user_agent VARCHAR(255) NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        KEY idx_contact_messages_user_id (user_id),
        KEY idx_contact_messages_created_at (created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // Courses (YouTube learning courses)
    $conn->query("CREATE TABLE IF NOT EXISTS courses (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT NULL,
        language ENUM('ar','en') NOT NULL DEFAULT 'ar',
        youtube_url VARCHAR(500) NOT NULL,
        youtube_id VARCHAR(32) NOT NULL,
        thumbnail_url VARCHAR(500) NULL,
        is_published TINYINT(1) NOT NULL DEFAULT 1,
        sort_order INT NOT NULL DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        KEY idx_courses_language (language),
        KEY idx_courses_is_published (is_published),
        KEY idx_courses_sort_order (sort_order)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $coursesCols = [
        'description' => "ALTER TABLE courses ADD COLUMN description TEXT NULL",
        'language' => "ALTER TABLE courses ADD COLUMN language ENUM('ar','en') NOT NULL DEFAULT 'ar'",
        'youtube_url' => "ALTER TABLE courses ADD COLUMN youtube_url VARCHAR(500) NULL",
        'youtube_id' => "ALTER TABLE courses ADD COLUMN youtube_id VARCHAR(32) NULL",
        'thumbnail_url' => "ALTER TABLE courses ADD COLUMN thumbnail_url VARCHAR(500) NULL",
        'is_published' => "ALTER TABLE courses ADD COLUMN is_published TINYINT(1) NOT NULL DEFAULT 1",
        'sort_order' => "ALTER TABLE courses ADD COLUMN sort_order INT NOT NULL DEFAULT 0",
        'created_at' => "ALTER TABLE courses ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP",
        'updated_at' => "ALTER TABLE courses ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
    ];
    foreach ($coursesCols as $col => $sql) {
        $res = $conn->query("SHOW COLUMNS FROM courses LIKE '" . $conn->real_escape_string($col) . "'");
        if ($res && $res->num_rows === 0) {
            $conn->query($sql);
        }
    }
}

ensure_schema($conn);

// Ensure uploads directories exist
@mkdir(__DIR__ . '/uploads', 0775, true);
@mkdir(__DIR__ . '/uploads/products', 0775, true);
@mkdir(__DIR__ . '/uploads/shops', 0775, true);

function db(): ?mysqli {
    global $conn;
    return $conn;
}

// دالة للتحقق من تسجيل الدخول
function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

// دالة للتحقق من صلاحيات المستخدم
function hasRole(string $role): bool {
    return ($_SESSION['role'] ?? '') === $role;
}

function phone_digits(string $phone): string {
    $digits = preg_replace('/\D+/u', '', (string)$phone);
    return $digits === null ? '' : $digits;
}

function phone_has_min_digits(?string $phone, int $minDigits = 10): bool {
    $digits = phone_digits((string)$phone);
    return mb_strlen($digits) >= $minDigits;
}

// دالة للتحويل إلى صفحة أخرى
function redirect(string $url) {
    header("Location: $url");
    exit;
}