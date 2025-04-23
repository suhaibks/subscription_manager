<?php
// home.php - Home page for subscriptions
require_once 'config.php';

// Ensure the user is logged in.
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$error = "";
$success = "";

// Process deletion if requested
if (isset($_GET['action']) && $_GET['action'] == "delete" && isset($_GET['id'])) {
    $sub_id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM subscriptions WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $sub_id, $user_id);
    if ($stmt->execute()) {
        $success = "Subscription deleted successfully.";
    } else {
        $error = "Error deleting subscription.";
    }
    $stmt->close();
}

// Process form submission for adding subscription
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $amount = floatval($_POST['amount']);
    // Convert date from dd/mm/yyyy to yyyy-mm-dd format if needed
    // Assuming the input is of type date, the browser returns yyyy-mm-dd format.
    $renewal_date = $_POST['renewal_date'];
    $recurrence = $_POST['recurrence'];
    $recurrence_count = intval($_POST['recurrence_count']);

    $stmt = $conn->prepare("INSERT INTO subscriptions (user_id, name, amount, renewal_date, recurrence, recurrence_count) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isdssi", $user_id, $name, $amount, $renewal_date, $recurrence, $recurrence_count);
    if ($stmt->execute()) {
        $success = "Subscription added successfully.";
    } else {
        $error = "Error adding subscription.";
    }
    $stmt->close();
}

// Retrieve subscriptions for the logged-in user
$stmt = $conn->prepare("SELECT * FROM subscriptions WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$subscriptions = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<?php include 'header.php'; ?>
<style>
  .home-heading {
    text-align: center;
    margin: 30px 0 10px;
  }
  .home-heading h2 {
    font-size: 28px;
    font-weight: bold;
    color: #222;
  }
  .home-heading p {
    font-size: 16px;
    color: #666;
    margin-top: 5px;
  }
  
  .hero-section {
    background: url('assets/images/hero-banner.jpeg') no-repeat center center/cover;
    height: 400px;
    border-radius: 0px;
    margin: 0px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: black;
     font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', sans-serif;
    font-size: 50px;
    font-weight: bold;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    text-align: center;
  }

  .stats-grid {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    margin: 30px 0;
    text-align: center;
  }

  .stat-box {
    background-color: #f4f6fb;
    padding: 20px;
    border-radius: 10px;
    width: 240px;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    margin: 10px;
  }

  .stat-box h3 {
    font-size: 30px;
    color: #333;
    margin-bottom: 5px;
  }

  .stat-box p {
    font-size: 14px;
    color: #777;
  }

   .section-wrapper {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    padding: 40px 20px;
    gap: 30px;
    background-color: #f7fafa;
    border-radius: 12px;
    margin: 40px 0;
  }

  .section-image {
    flex: 1;
    min-width: 280px;
    text-align: center;
  }

  .section-image img {
    width: 100%;
    max-width: 340px;
    border-radius: 10px;
  }

  .section-content {
    flex: 2;
    min-width: 280px;
  }

  .section-content h2 {
    font-size: 22px;
    color: black;
    margin-bottom: 12px;
  }

  .section-content ul {
    padding-left: 20px;
    font-size: 15px;
    color: #444;
    line-height: 1.7;
  }

  .section-content ul li {
    margin-bottom: 8px;
  }

.nav-buttons {
  display: flex;
  justify-content: center;
  gap: 40px;
  margin-top: 50px;
  flex-wrap: wrap;
}

.nav-button {
  background: #ffffff;
  border-radius: 10px;
  padding: 20px;
  width: 200px;
  text-align: center;
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
  transition: 0.3s ease;
  text-decoration: none;
}

.nav-button:hover {
  background: #e8f5e9;
}

.nav-button img {
  width: 60px;
  margin-bottom: 10px;
}

.nav-button span {
  display: block;
  font-weight: bold;
  color: black;
  font-size: 16px;
}

</style>

<!-- Hero Banner -->
<div class="hero-section">
    SUBSCRIPTION MANAGER<BR>DASHBOARD
</div>

<!-- Dynamic Stats -->
<div class="stats-grid">
  <div class="stat-box">
    <h3 id="activeUsers">0</h3>
    <p>Active Users</p>
  </div>
  <div class="stat-box">
    <h3 id="visitors">0</h3>
    <p>Visitors</p>
  </div>
  <div class="stat-box">
    <h3 id="likedby">0</h3>
    <p>Liked-by</p>
  </div>
</div>


<div class="home-heading">
  <h2>ANALYSE YOUR MONEY HERE</h2>
  <p>Save Time. Save Money. Stay Subscribed</p>
</div>

<div class="container" style="border-radius:15px">
  
  <?php if($success): ?>
    <p class="success"><?php echo $success; ?></p>
  <?php endif; ?>
  <?php if($error): ?>
    <p class="error"><?php echo $error; ?></p>
  <?php endif; ?>

  <!-- Add Subscription Form -->
  <div class="form-container">
    <h3>Add Subscription</h3>
    <form method="POST" action="home.php">
      <label for="name">Name</label>
      <input type="text" name="name" id="name" required>

      <label for="amount">Price(₹)</label>
      <input type="number" step="0.01" name="amount" id="amount" required>

      <label for="renewal_date">Renewal Date</label>
      <input type="date" name="renewal_date" id="renewal_date" required>

      <label for="recurrence">Recurrence</label>
      <select name="recurrence" id="recurrence">
        <option value="Monthly">Monthly</option>
        <option value="Yearly">Yearly</option>
      </select>

      <label for="recurrence_count">Recurrence Count</label>
      <input type="number" name="recurrence_count" id="recurrence_count" required>

      <button type="submit">Add Subscription</button>
    </form>
  </div>
</div>

<div class="container" style="border-radius:15px">
  <!-- Subscription List -->
  <div class="subscription-list" style="border-radius:15px">
  <h3>Your Subscriptions</h3>
  <?php if(count($subscriptions) > 0): ?>
    <div class="table-container">
      <table class="styled-table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Price(₹)</th>
            <th>Renewal Date</th>
            <th>Recurrence</th>
            <th>Recurrence Count</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($subscriptions as $sub): ?>
          <tr>
            <td><?php echo htmlspecialchars($sub['name']); ?></td>
            <td>₹<?php echo number_format($sub['amount'], 2); ?></td>
            <td><?php echo date("Y-m-d", strtotime($sub['renewal_date'])); ?></td>
            <td><?php echo htmlspecialchars($sub['recurrence']); ?></td>
            <td><?php echo $sub['recurrence_count']; ?></td>
            <td>
              <a href="edit_subscription.php?id=<?php echo $sub['id']; ?>" class="btn-edit">Edit</a>
              <a href="home.php?action=delete&id=<?php echo $sub['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this subscription?')">Delete</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <p>No subscriptions found.</p>
  <?php endif; ?>
</div>
</div>
  <script>
  // Animate example numbers
  function animateValue(id, start, end, duration, prefix = '') {
    const el = document.getElementById(id);
    let startTime = null;

    function animate(timestamp) {
      if (!startTime) startTime = timestamp;
      const progress = Math.min((timestamp - startTime) / duration, 1);
      const value = Math.floor(progress * (end - start) + start);
      el.textContent = prefix + value;
      if (progress < 1) requestAnimationFrame(animate);
    }

    requestAnimationFrame(animate);
  }

  // Trigger animations
  animateValue('activeUsers', 0, 542, 1000);
  animateValue('visitors', 0, 1283, 1200);
  animateValue('likedby', 0, 1058, 1300);
</script>
</div>
<!-- Section: Uses -->
<div class="section-wrapper">
  <div class="section-image">
    <img src="assets/images/uses.jpeg" alt="Uses of Subscription Manager">
  </div>
  <div class="section-content">
    <h2>Why Use Subscription Manager?</h2>
    <ul>
      <li>Track all your subscriptions in one place — personal and professional</li>
      <li>Stay ahead of renewals with a visual calendar</li>
      <li>Get rid of forgotten subscriptions draining your wallet</li>
      <li>Useful for students, freelancers, startups, and SaaS users</li>
    </ul>
  </div>
</div>

<!-- Section: Utilization -->
<div class="section-wrapper">
  <div class="section-content">
    <h2>How to Use Subscription Manager Effectively</h2>
    <ul>
      <li>Add all your services with recurrence details</li>
      <li>Use the calendar to plan monthly budgets</li>
      <li>Analyze your top expenses on the analysis page</li>
      <li>Make informed decisions using total spending stats</li>
    </ul>
  </div>
  <div class="section-image">
    <img src="assets/images/utilize.jpeg" alt="Utilizing Subscription Manager">
  </div>
</div>

<!-- Navigation Buttons -->
<div class="nav-buttons">
  <a href="calendar.php" class="nav-button">
    <img src="assets/images/calendar-icon.png" alt="Calendar">
    <span>View Calendar</span>
  </a>
  <a href="analysis.php" class="nav-button">
    <img src="assets/images/analysis-icon.png" alt="Analysis">
    <span>Check Analysis</span>
  </a>
</div>
<?php include 'footer.php'; ?>
