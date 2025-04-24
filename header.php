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
  <style>
    .logo-container {
      display: flex;
      align-items: center;
      gap: 10px;
      text-decoration: none;
    }

    .logo-icon {
      height: 40px;
      width: 40px;
      object-fit: cover;
      border-radius: 50%; /* Makes the logo circular */
    }

    .logo-text {
      font-size: 24px;
      color: white;
      margin: 0;
      font-family: Georgia, 'Times New Roman', serif;
    }
  </style>
</head>
<body>
<header>
  <nav class="navbar">
    <div class="nav-left">
      <a href="home.php" class="logo-container">
        <img src="assets/images/logo.jpeg" alt="Logo" class="logo-icon">
        <h1 class="logo-text">Subscription Manager</h1>
      </a>
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
