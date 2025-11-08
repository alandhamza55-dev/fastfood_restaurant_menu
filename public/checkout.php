<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/functions.php';
require_login();

if (empty($_SESSION['cart'])) {
    echo "Cart empty. <a href='index.php'>Back</a>";
    exit();
}

$ids = implode(',', array_map('intval', array_keys($_SESSION['cart'])));
$res = $mysqli->query("SELECT * FROM menu_items WHERE id IN ($ids)");
$total = 0; $items = [];
while ($r = $res->fetch_assoc()) {
    $qty = $_SESSION['cart'][$r['id']];
    $items[] = ['id' => $r['id'], 'price' => $r['price'], 'qty' => $qty];
    $total += $qty * $r['price'];
}

$stmt = $mysqli->prepare("INSERT INTO orders (user_id, total) VALUES (?, ?)"); $stmt->bind_param('id', $_SESSION['user_id'], $total); $stmt->execute(); $order_id = $stmt->insert_id;

$stmt2 = $mysqli->prepare("INSERT INTO order_items (order_id, menu_item_id, quantity, price) VALUES (?, ?, ?, ?)"); foreach ($items as $it) { $stmt2->bind_param('iiid', $order_id, $it['id'], $it['qty'], $it['price']); $stmt2->execute(); }

unset($_SESSION['cart']);
header("Location: order_success.php?id={$order_id}");
exit();