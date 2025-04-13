<?php
require_once 'config.php';

$error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = trim($_POST['username']);
  $dob = $_POST['dob'];
  $email = trim($_POST['email']);
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  $stmt = $conn->prepare("INSERT INTO users (username, dob, email, password) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("ssss", $username, $dob, $email, $password);

  if ($stmt->execute()) {
    header("Location: index.php");
    exit;
  } else {
    $error = "Error: " . $stmt->error;
  }
  $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sign Up</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="auth-wrapper">
    <form method="POST" action="signup.php" class="auth-box">
      <h2>Sign Up</h2>
      <?php if ($error): ?>
        <p class="error"><?php echo $error; ?></p>
      <?php endif; ?>
      <input type="text" name="username" placeholder="Username" required>
      <input type="date" name="dob" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" id="password" required>
      <div class="show-password">
        <input type="checkbox" id="showPassword"> <label for="showPassword">Show Password</label>
      </div>
      <button type="submit" class="btn-primary">SIGN UP</button>
      <p class="auth-switch">Already have an account? <a href="index.php">Login</a></p>
    </form>
  </div>
  <script src="assets/js/script.js"></script>
</body>
</html>
