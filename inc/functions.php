<?php
if (!function_exists('e')) {
    function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
}
function is_logged(): bool { return !empty($_SESSION['user_id']); }
function require_login(): void { if (!is_logged()) { header('Location: ../public/login.php'); exit(); } }
function admin_logged(): bool { return !empty($_SESSION['admin_id']); }
function require_admin(): void { if (!admin_logged()) { header('Location: login.php'); exit(); } }

function handle_image_upload(array $file, string $destDir): ?string {
    if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) return null;
    if ($file['error'] !== UPLOAD_ERR_OK) return null;
    if ($file['size'] > 2 * 1024 * 1024) return null;
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif'];
    if (!array_key_exists($mime, $allowed)) return null;
    if (!is_dir($destDir) && !mkdir($destDir, 0775, true)) return null;
    $ext = $allowed[$mime];
    $newName = bin2hex(random_bytes(8)) . '.' . $ext;
    $dest = rtrim($destDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $newName;
    if (move_uploaded_file($file['tmp_name'], $dest)) return $newName;
    return null;
}
?>