<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/functions.php';

$action = $_GET['action'] ?? null;
$id = (int)($_GET['id'] ?? 0);

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

if ($action === 'add' && $id > 0) {
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
    header('Location: cart.php'); exit();
}
if ($action === 'remove' && isset($_SESSION['cart'][$id])) {
    unset($_SESSION['cart'][$id]);
    header('Location: cart.php'); exit();
}
if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['qty'] as $pid => $qty) {
        $pid = (int)$pid; $qty = max(0, (int)$qty);
        if ($qty > 0) $_SESSION['cart'][$pid] = $qty; else unset($_SESSION['cart'][$pid]);
    }
    header('Location: cart.php'); exit();
}

$items = [];
$total = 0.0;
if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_map('intval', array_keys($_SESSION['cart'])));
    $res = $mysqli->query("SELECT * FROM menu_items WHERE id IN ($ids)");
    while ($r = $res->fetch_assoc()) {
        $r['qty'] = $_SESSION['cart'][$r['id']];
        $r['subtotal'] = $r['price'] * $r['qty'];
        $total += $r['subtotal'];
        $items[] = $r;
    }
}
?>
<!doctype html><html><head><meta charset="utf-8"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="container py-4">
<h1>Your Cart</h1>
<form method="POST" action="cart.php?action=update">
<table class="table">
<thead><tr><th>Item</th><th>Price</th><th>Qty</th><th>Subtotal</th><th></th></tr></thead>
<tbody>
<?php foreach ($items as $it): ?>
<tr>
  <td><?= e($it['name']) ?></td>
  <td>$<?= number_format($it['price'],2) ?></td>
  <td><input type="number" name="qty[<?= $it['id'] ?>]" value="<?= $it['qty'] ?>" min="0" class="form-control" style="width:80px"></td>
  <td>$<?= number_format($it['subtotal'],2) ?></td>
  <td><a href="cart.php?action=remove&id=<?= $it['id'] ?>" class="btn btn-sm btn-danger">Remove</a></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<p><strong>Total: $<?= number_format($total,2) ?></strong></p>
<button class="btn btn-primary">Update</button>
<a href="checkout.php" class="btn btn-success">Checkout</a>
</form>
</body></html>