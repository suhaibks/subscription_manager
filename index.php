<?php
require_once 'config.php';

if (isset($_SESSION['username'])) {
  header("Location: home.php");
  exit;
}

$error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = trim($_POST['username']);
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows === 1) {
    $stmt->bind_result($user_id, $hashed_password);
    $stmt->fetch();
    if (password_verify($password, $hashed_password)) {
      $_SESSION['username'] = $username;
      $_SESSION['user_id'] = $user_id;
      header("Location: home.php");
      exit;
    } else {
      $error = "Invalid username or password.";
    }
  } else {
    $error = "Invalid username or password.";
  }
  $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="auth-wrapper">
    <form method="POST" action="index.php" class="auth-box">
      <h2>Login</h2>
      <?php if ($error): ?>
        <p class="error"><?php echo $error; ?></p>
      <?php endif; ?>
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" id="password" required>
      <div class="show-password">
        <input type="checkbox" id="showPassword"> <label for="showPassword">Show Password</label>
      </div>
      <button type="submit" class="btn-primary">LOGIN</button>
      <p class="auth-switch">Don't have an account? <a href="signup.php">Sign up</a></p>
    </form>
  </div>
  <script src="assets/js/script.js"></script>
</body>
</html>
