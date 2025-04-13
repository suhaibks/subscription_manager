<?php
// header.php
if (!isset($_SESSION)) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Subscription Manager</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
 <header>
  <nav class="navbar">
    <div class="nav-left">
      <h1 class="logo">Subscription Manager</h1>
    </div>
    <div class="nav-right-wrapper">
      <ul class="nav-right">
        <li><a href="home.php">Home</a></li>
        <li><a href="calendar.php">Calendar</a></li>
        <li><a href="analysis.php">Analysis</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
      </ul>
      <?php if (isset($_SESSION['username'])): ?>
      <div class="profile">
        <img src="assets/images/profile.jpeg" alt="Profile Icon" id="profileIcon">
        <div class="profile-dropdown" id="profileDropdown">
          <p><?php echo htmlspecialchars($_SESSION['username']); ?></p>
          <a href="logout.php">Logout</a>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </nav>
</header>

