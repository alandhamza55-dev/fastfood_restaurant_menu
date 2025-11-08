<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/functions.php';

$q = trim($_GET['q'] ?? '');
$sql = "SELECT id, name, description, price, image FROM menu_items WHERE available = 1";
$params = [];
if ($q !== '') {
    $sql .= " AND (name LIKE ? OR description LIKE ?)";
    $like = "%$q%";
    $params[] = $like;
    $params[] = $like;
}
$sql .= " ORDER BY id DESC";

$stmt = $mysqli->prepare($sql);
if ($params) {
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
}
$stmt->execute();
$res = $stmt->get_result();
?>
<!doctype html><html><head><meta charset="utf-8"><title>Aland Burger House</title>
<link href="../assets/css/style.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body>
<div class="header">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center">
      <div><h1 class="brand">Aland Burger House</h1><div>Quick. Tasty. Delivered.</div></div>
      <div>
        <?php if (is_logged()): ?>
          Hello, <?= e($_SESSION['username']) ?> | <a href="logout.php" style="color:white;">Logout</a>
        <?php else: ?>
          <a href="login.php" style="color:white;">Login</a> | <a href="register.php" style="color:white;">Register</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<div class="container">
  <form method="GET" class="mb-3">
    <div class="input-group">
      <input name="q" value="<?= e($q) ?>" class="form-control" placeholder="Search burgers, pizza...">
      <button class="btn btn-primary">Search</button>
      <a href="cart.php" class="btn btn-success">Cart (<?= array_sum($_SESSION['cart'] ?? []) ?>)</a>
    </div>
  </form>

  <div class="row">
    <?php while($row = $res->fetch_assoc()): ?>
      <div class="col-md-4 mb-3">
        <div class="card menu-item">
          <?php if (!empty($row['image'])): ?>
            <img src="../assets/uploads/<?= e($row['image']) ?>" alt="" class="w-100">
          <?php endif; ?>
          <div class="p-3">
            <h5><?= e($row['name']) ?></h5>
            <p><?= e(mb_strimwidth($row['description'], 0, 120, '...')) ?></p>
            <div class="d-flex justify-content-between align-items-center">
              <div class="price">$<?= number_format($row['price'], 2) ?></div>
              <div>
                <a href="item.php?id=<?= $row['id'] ?>" class="btn btn-outline-primary btn-sm">View</a>
                <a href="cart.php?action=add&id=<?= $row['id'] ?>" class="btn btn-success btn-sm">Add</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>

  <div class="footer">Aland Burger House — Hot & Fresh</div>
</div>

</body></html>