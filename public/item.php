<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/functions.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: index.php'); exit(); }

$stmt = $mysqli->prepare("SELECT id, name, description, price, image FROM menu_items WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $id);
$stmt->execute();
$item = $stmt->get_result()->fetch_assoc();
if (!$item) { header('Location: index.php'); exit(); }
?>
<!doctype html><html><head><meta charset="utf-8"><title><?= e($item['name']) ?></title>
<link href="../assets/css/style.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body class="container py-4">
<a href="index.php">&larr; Back</a>
<div class="row mt-3">
  <div class="col-md-6">
    <?php if ($item['image']): ?>
      <img src="../assets/uploads/<?= e($item['image']) ?>" class="img-fluid">
    <?php endif; ?>
  </div>
  <div class="col-md-6">
    <h2><?= e($item['name']) ?></h2>
    <p><?= e($item['description']) ?></p>
    <p class="price">$<?= number_format($item['price'],2) ?></p>
    <a href="cart.php?action=add&id=<?= $item['id'] ?>" class="btn btn-success">Add to cart</a>
  </div>
</div>
</body></html>