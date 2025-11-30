<?php
require_once "db.php"; // لو حاط اتصالك في ملف آخر

$result = pg_query($conn, "SELECT NOW()");
$row = pg_fetch_assoc($result);

echo "Database OK<br>";
echo $row['now'];
