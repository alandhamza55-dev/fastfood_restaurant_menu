<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $email === '' || $password === '') {
        $error = "All fields are required.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare("INSERT INTO users (username,email,password) VALUES (?,?,?)");
        $stmt->bind_param('sss', $username, $email, $hash);
        if ($stmt->execute()) {
            header('Location: login.php'); exit();
        } else {
            $error = $stmt->error;
        }
    }
}
?>
<!doctype html><html><head><meta charset="utf-8"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="container py-4">
<h1>Register</h1>
<?php if (!empty($error)) echo '<div class="alert alert-danger">'.e($error).'</div>'; ?>
<form method="POST">
  <input name="username" class="form-control mb-2" placeholder="Username" required>
  <input name="email" class="form-control mb-2" placeholder="Email" type="email" required>
  <input name="password" class="form-control mb-2" placeholder="Password" type="password" required>
  <button class="btn btn-primary">Register</button>
</form>
<p>Already registered? <a href="login.php">Login</a></p>
</body></html>