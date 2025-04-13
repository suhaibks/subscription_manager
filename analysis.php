<?php
// analysis.php
require_once 'config.php';

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Total amount spent (sum over all subscriptions as amount * recurrence_count)
$query = "SELECT SUM(amount * recurrence_count) AS total_spent FROM subscriptions WHERE user_id = $user_id";
$result = $conn->query($query);
$row = $result->fetch_assoc();
$total_spent = $row['total_spent'] ?? 0;

// Active subscriptions: recurrence_count > 0
$query = "SELECT COUNT(*) AS total_active FROM subscriptions WHERE user_id = $user_id AND recurrence_count > 0";
$result = $conn->query($query);
$row = $result->fetch_assoc();
$total_active = $row['total_active'] ?? 0;

// Least expensive active subscription
$query = "SELECT name, amount FROM subscriptions WHERE user_id = $user_id AND recurrence_count > 0 ORDER BY amount ASC LIMIT 1";
$result = $conn->query($query);
$least = $result->fetch_assoc();

// Most expensive active subscription
$query = "SELECT name, amount FROM subscriptions WHERE user_id = $user_id AND recurrence_count > 0 ORDER BY amount DESC LIMIT 1";
$result = $conn->query($query);
$most = $result->fetch_assoc();

// For cost per week/month/year, we approximate for monthly and yearly recurrence:
// For monthly: weekly cost = (amount * 12) / 52, monthly cost = amount, yearly cost = amount * 12
// For yearly: weekly cost = amount / 52, monthly cost = amount / 12, yearly cost = amount.
$weekly_cost = 0;
$monthly_cost = 0;
$yearly_cost = 0;

$query = "SELECT amount, recurrence FROM subscriptions WHERE user_id = $user_id";
$result = $conn->query($query);
while ($sub = $result->fetch_assoc()) {
    if ($sub['recurrence'] == "Monthly") {
        $weekly_cost += ($sub['amount'] * 12) / 52;
        $monthly_cost += $sub['amount'];
        $yearly_cost += $sub['amount'] * 12;
    } else {
        $weekly_cost += $sub['amount'] / 52;
        $monthly_cost += $sub['amount'] / 12;
        $yearly_cost += $sub['amount'];
    }
}
?>

<?php include 'header.php'; ?>
<style>
  .analysis-info {
    margin-top: 30px;
    background-color: #f9f9f9;
    padding: 25px;
    border-radius: 10px;
    font-size: 15px;
    color: #444;
    line-height: 1.6;
    box-shadow: 0 0 8px rgba(0, 0, 0, 0.04);
  }
  .analysis-info h3 {
    margin-bottom: 12px;
    color: #333;
    font-size: 20px;
  }
  .analysis-info ul {
    margin-left: 20px;
    padding-left: 10px;
  }
  .analysis-info ul li {
    margin-bottom: 8px;
  }
</style>
<div class="container analysis">
  <h2>Analysis</h2>
  <div class="grid">
    <div class="grid-item">
      <h3>Total Cost per Week</h3>
      <p>₹<?php echo number_format($weekly_cost, 2); ?></p>
    </div>
    <div class="grid-item">
      <h3>Total Cost per Month</h3>
      <p>₹<?php echo number_format($monthly_cost, 2); ?></p>
    </div>
    <div class="grid-item">
      <h3>Total Cost per Year</h3>
      <p>₹<?php echo number_format($yearly_cost, 2); ?></p>
    </div>
    <div class="grid-item">
      <h3>Total Active Subscriptions</h3>
      <p><?php echo $total_active; ?></p>
    </div>
    <div class="grid-item">
      <h3>Least Expensive (Active)</h3>
      <p><?php echo $least ? htmlspecialchars($least['name'])." (₹".number_format($least['amount'],2).")" : "N/A"; ?></p>
    </div>
    <div class="grid-item">
      <h3>Most Expensive (Active)</h3>
      <p><?php echo $most ? htmlspecialchars($most['name'])." (₹".number_format($most['amount'],2).")" : "N/A"; ?></p>
    </div>
    <div class="grid-item">
      <h3>Total Amount Spent</h3>
      <p>₹<?php echo number_format($total_spent,2); ?></p>
    </div>
  </div>
  <div class="analysis-info">
  <h3>About This Analysis</h3>
  <p>
    This section provides a detailed overview of your subscription spending habits. Each metric helps you understand and optimize your recurring expenses:
  </p><br><!-- comment -->
  <ul>
    <li><strong>Total Cost per Week/Month/Year:</strong> Shows how much you’re spending in each period across all active subscriptions.</li>
    <li><strong>Total Active Subscriptions:</strong> Counts only the subscriptions that are currently recurring (based on recurrence count).</li>
    <li><strong>Most/Least Expensive Subscriptions:</strong> Helps you identify high- and low-cost services so you can evaluate their value.</li>
    <li><strong>Total Amount Spent:</strong> Calculates the full cost across all subscriptions (past and ongoing).</li>
  </ul>
  <p>
    Use these insights to make smarter financial decisions, cancel unused services, and plan better for upcoming payments.
  </p>
</div>
</div>
<?php include 'footer.php'; ?>
