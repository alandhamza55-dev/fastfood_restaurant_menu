<?php
// correct path to config.php
require_once __DIR__ . '/../inc/config.php';

// new password
$newPassword = 'admin123';
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

// update admin password
$sql = "UPDATE admin_users SET password=? WHERE username='admin'";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $hashedPassword);

if ($stmt->execute()) {
    echo "✅ Admin password successfully reset to 'admin123'.";
} else {
    echo "❌ Error: " . $stmt->error;
}
?>

