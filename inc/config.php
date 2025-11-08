<?php
// inc/config.php
declare(strict_types=1);

// start session once
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// DB settings
$db_host = '127.0.0.1';
$db_user = 'root';
$db_pass = '';
$db_name = 'fastfood_pro_db';

$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($mysqli->connect_errno) {
    http_response_code(500);
    echo "<h2>Database connection error</h2>";
    echo "Error: (" . $mysqli->connect_errno . ") " . htmlspecialchars($mysqli->connect_error);
    exit;
}
$mysqli->set_charset('utf8mb4');
$BASE_PATH = '/fastfood_pro';
?>