<?php
require_once 'config.php';

// Ensure user is logged in
if (!isset($_SESSION['username'])) {
  header("Location: index.php");
  exit;
}

$user_id = $_SESSION['user_id'];
$error = "";

// Get subscription ID
if (!isset($_GET['id'])) {
  header("Location: home.php");
  exit;
}
$sub_id = intval($_GET['id']);

// Fetch subscription details
$stmt = $conn->prepare("SELECT * FROM subscriptions WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $sub_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
  header("Location: home.php");
  exit;
}
$subscription = $result->fetch_assoc();
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['cancel'])) {
    header("Location: home.php");
    exit;
  }

  $name = trim($_POST['name']);
  $amount = floatval($_POST['amount']);
  $renewal_date = $_POST['renewal_date'];
  $recurrence = $_POST['recurrence'];
  $recurrence_count = intval($_POST['recurrence_count']);

  $stmt = $conn->prepare("UPDATE subscriptions SET name = ?, amount = ?, renewal_date = ?, recurrence = ?, recurrence_count = ? WHERE id = ? AND user_id = ?");
  $stmt->bind_param("sdsssii", $name, $amount, $renewal_date, $recurrence, $recurrence_count, $sub_id, $user_id);

  if ($stmt->execute()) {
    header("Location: home.php");
    exit;
  } else {
    $error = "Failed to update subscription.";
  }
  $stmt->close();
}
?>

<?php include 'header.php'; ?>

<style>
  .container {
    max-width: 600px;
    margin: 40px auto;
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
  }

  .container h2 {
    margin-bottom: 20px;
    font-size: 24px;
  }

  form {
    display: flex;
    flex-direction: column;
  }

  label {
    margin-bottom: 5px;
    font-weight: bold;
  }

  input, select {
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
  }

  .button-group {
    display: flex;
    gap: 15px;
  }

  .button-group button {
    flex: 1;
    padding: 12px;
    font-weight: bold;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
  }

  .btn-primary {
    background-color: #333;
    color: white;
  }

  .btn-primary:hover {
    background-color: #555;
  }

  .btn-secondary {
    background-color: #e0e0e0;
    color: #333;
  }

  .btn-secondary:hover {
    background-color: #cfcfcf;
  }

  .error {
    color: #dc3545;
    margin-bottom: 15px;
    font-size: 14px;
  }
</style>

<div class="container">
  <h2>Edit Subscription</h2>

  <?php if ($error): ?>
    <p class="error"><?php echo $error; ?></p>
  <?php endif; ?>

  <form method="POST" action="edit_subscription.php?id=<?php echo $sub_id; ?>">
    <label for="name">Subscription Name</label>
    <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($subscription['name']); ?>" required>

    <label for="amount">Price(₹)</label>
    <input type="number" step="0.01" name="amount" id="amount" value="<?php echo htmlspecialchars($subscription['amount']); ?>" required>

    <label for="renewal_date">Renewal Date</label>
    <input type="date" name="renewal_date" id="renewal_date" value="<?php echo $subscription['renewal_date']; ?>" required>

    <label for="recurrence">Recurrence</label>
    <select name="recurrence" id="recurrence">
      <option value="Monthly" <?php if($subscription['recurrence'] === 'Monthly') echo 'selected'; ?>>Monthly</option>
      <option value="Yearly" <?php if($subscription['recurrence'] === 'Yearly') echo 'selected'; ?>>Yearly</option>
    </select>

    <label for="recurrence_count">Recurrence Count</label>
    <input type="number" name="recurrence_count" id="recurrence_count" value="<?php echo $subscription['recurrence_count']; ?>" required>

    <div class="button-group">
      <button type="submit" class="btn-primary">Submit</button>
      <button type="submit" name="cancel" class="btn-secondary">Cancel</button>
    </div>
  </form>
</div>

<?php include 'footer.php'; ?>
