<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $mysqli->prepare("SELECT id, username, password FROM users WHERE username = ? LIMIT 1");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $u = $stmt->get_result()->fetch_assoc();
    if ($u && password_verify($password, $u['password'])) {
        $_SESSION['user_id'] = $u['id'];
        $_SESSION['username'] = $u['username'];
        header('Location: index.php'); exit();
    } else {
        $error = "Invalid credentials.";
    }
}
?>
<!doctype html><html><head><meta charset="utf-8"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="container py-4">
<h1>Login</h1>
<?php if (!empty($error)) echo '<div class="alert alert-danger">'.e($error).'</div>'; ?>
<form method="POST">
  <input name="username" class="form-control mb-2" placeholder="Username" required>
  <input name="password" type="password" class="form-control mb-2" placeholder="Password" required>
  <button class="btn btn-primary">Login</button>
</form>
<p>Don't have an account? <a href="register.php">Register</a></p>
</body></html>