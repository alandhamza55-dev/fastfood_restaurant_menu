<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = $mysqli->prepare("SELECT id, username, password FROM admin_users WHERE username = ? LIMIT 1");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $a = $stmt->get_result()->fetch_assoc();
    if ($a && password_verify($password, $a['password'])) {
        $_SESSION['admin_id'] = $a['id'];
        $_SESSION['admin_username'] = $a['username'];
        header('Location: dashboard.php');
        exit();
    } else {
        $error = "Invalid credentials.";
    }
}
?>
<!doctype html><html><head><meta charset="utf-8"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="container py-4">
<h1>Admin Login</h1>
<?php if (!empty($error)) echo '<div class="alert alert-danger">'.e($error).'</div>'; ?>
<form method="POST">
  <input name="username" class="form-control mb-2" placeholder="Username" required>
  <input name="password" type="password" class="form-control mb-2" placeholder="Password" required>
  <button class="btn btn-primary">Login</button>
</form>
</body></html>