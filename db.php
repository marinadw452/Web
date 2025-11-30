<?php
$DATABASE_URL = getenv("DATABASE_URL");

if (!$DATABASE_URL) {
    die("DATABASE_URL not set!");
}

$conn = pg_connect($DATABASE_URL);

if (!$conn) {
    die("Failed to connect to the database.");
}
?>
