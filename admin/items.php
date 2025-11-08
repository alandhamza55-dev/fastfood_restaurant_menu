<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/functions.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $del = (int)$_POST['delete_id'];
    $stmt = $mysqli->prepare("DELETE FROM menu_items WHERE id = ?");
    $stmt->bind_param('i', $del);
    $stmt->execute();
    header('Location: items.php'); exit();
}

$res = $mysqli->query("SELECT id, name, price FROM menu_items ORDER BY id DESC");
?>
<!doctype html><html><head><meta charset="utf-8"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="container py-4">
<h1>Manage Items</h1>
<a href="item_edit.php" class="btn btn-primary mb-2">Add Item</a>
<table class="table">
<thead><tr><th>ID</th><th>Name</th><th>Price</th><th>Actions</th></tr></thead><tbody>
<?php while($r = $res->fetch_assoc()): ?>
<tr>
  <td><?= (int)$r['id'] ?></td>
  <td><?= e($r['name']) ?></td>
  <td>$<?= number_format($r['price'],2) ?></td>
  <td>
    <a href="item_edit.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
    <form method="POST" style="display:inline">
      <input type="hidden" name="delete_id" value="<?= $r['id'] ?>">
      <button class="btn btn-sm btn-link" onclick="return confirm('Delete?')">Delete</button>
    </form>
  </td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</body></html>