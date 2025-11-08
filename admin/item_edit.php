<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/functions.php';
require_admin();

$id = (int)($_GET['id'] ?? 0);
$item = null;
if ($id > 0) {
    $stmt = $mysqli->prepare("SELECT * FROM menu_items WHERE id = ? LIMIT 1");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $item = $stmt->get_result()->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $desc = trim($_POST['description'] ?? '');

    $img = null;
    if (!empty($_FILES['image']['name'])) {
        $img = handle_image_upload($_FILES['image'], __DIR__ . '/../assets/uploads/');
    }

    if ($id > 0) {
        if ($img !== null) {
            $stmt = $mysqli->prepare("UPDATE menu_items SET name=?, description=?, price=?, image=? WHERE id=?");
            $stmt->bind_param('ssdsi', $name, $desc, $price, $img, $id);
        } else {
            $stmt = $mysqli->prepare("UPDATE menu_items SET name=?, description=?, price=? WHERE id=?");
            $stmt->bind_param('ssdi', $name, $desc, $price, $id);
        }
        $stmt->execute();
    } else {
        $stmt = $mysqli->prepare("INSERT INTO menu_items (name, description, price, image, available) VALUES (?, ?, ?, ?, 1)");
        $stmt->bind_param('ssds', $name, $desc, $price, $img);
        $stmt->execute();
        $id = $stmt->insert_id;
    }
    header('Location: items.php'); exit();
}
?>
<!doctype html><html><head><meta charset="utf-8"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="container py-4">
<h1><?= $id ? 'Edit' : 'Add' ?> Item</h1>
<form method="POST" enctype="multipart/form-data">
  <input name="name" class="form-control mb-2" placeholder="Name" required value="<?= e($item['name'] ?? '') ?>">
  <input name="price" class="form-control mb-2" placeholder="Price" required value="<?= e($item['price'] ?? '') ?>">
  <textarea name="description" class="form-control mb-2" placeholder="Description"><?= e($item['description'] ?? '') ?></textarea>
  <input type="file" name="image" class="form-control mb-2">
  <button class="btn btn-primary">Save</button>
</form>
</body></html>