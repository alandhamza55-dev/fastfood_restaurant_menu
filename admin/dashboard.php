<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/functions.php';
require_admin();

$res = $mysqli->query("SELECT COUNT(*) AS c FROM orders");
$ordersCount = ($res->fetch_assoc()['c'] ?? 0);
$res = $mysqli->query("SELECT COUNT(*) AS c FROM menu_items");
$itemsCount = ($res->fetch_assoc()['c'] ?? 0);
?>
<!doctype html><html><head><meta charset="utf-8"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"><link href="../assets/css/style.css" rel="stylesheet"></head>
<body class="container py-4">
<div class="mb-3"><a href="items.php">Manage Items</a> | <a href="logout.php">Logout</a></div>
<h1>Admin Dashboard</h1>
<div class="row">
  <div class="col-md-4"><div class="card p-3">Orders: <?= (int)$ordersCount ?></div></div>
  <div class="col-md-4"><div class="card p-3">Menu Items: <?= (int)$itemsCount ?></div></div>
</div>
</body></html>