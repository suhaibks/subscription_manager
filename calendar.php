<?php
require_once 'config.php';
if (!isset($_SESSION['username'])) {
  header("Location: index.php");
  exit;
}
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT name, amount, renewal_date AS date, recurrence, recurrence_count FROM subscriptions WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$subscriptions = [];
while ($row = $result->fetch_assoc()) {
  $subscriptions[] = $row;
}
$stmt->close();
?>

<?php include 'header.php'; ?>

<style>
  .calendar-nav {
    margin: 20px 0;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
  }
  .calendar-nav select,
  .calendar-nav button {
    padding: 8px 12px;
    font-size: 14px;
    border-radius: 6px;
    border: 1px solid #ccc;
  }
  .today-btn {
    background-color: #4caf50;
    color: white;
    border: none;
  }
  .today-btn:hover {
    background-color: #45a045;
  }
  #calendar {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 5px;
    margin-bottom: 30px;
    text-align: center;
  }
  .calendar-day {
    padding: 10px 0;
    background: #f2f2f2;
    border-radius: 4px;
    min-height: 40px;
    font-size: 14px;
  }
  .calendar-day.today {
    background: #d4edda;
    color: #155724;
  }
  .calendar-day.due {
    background: #f8d7da;
    color: #721c24;
  }
  .subscription-month-details {
    background: #f9f9f9;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.05);
    font-size: 15px;
  }
  .subscription-month-details h3 {
    margin-bottom: 15px;
  }
  .subscription-entry {
    border-bottom: 1px dashed #ccc;
    padding: 10px 0;
  }
  .subscription-entry:last-child {
    border-bottom: none;
  }
</style>

<div class="container">
  <h2>Calendar</h2>

  <div class="calendar-nav">
    <select id="monthSelect"></select>
    <select id="yearSelect"></select>
    <button id="goBtn">Go</button>
    <button id="todayBtn" class="today-btn">Today</button>
  </div>

  <div id="calendar"></div>

  <div class="subscription-month-details">
    <h3>Subscriptions Renewing in <span id="monthYearLabel"></span></h3>
    <div id="monthlySubList"></div>
  </div>
</div>

<script>
  const calendarEl = document.getElementById("calendar");
  const monthSelect = document.getElementById("monthSelect");
  const yearSelect = document.getElementById("yearSelect");
  const goBtn = document.getElementById("goBtn");
  const todayBtn = document.getElementById("todayBtn");
  const monthYearLabel = document.getElementById("monthYearLabel");
  const monthlySubList = document.getElementById("monthlySubList");

  const subscriptions = <?php echo json_encode($subscriptions); ?>;

  function formatDate(year, month, day) {
    return `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
  }

  function getRecurringDates(sub) {
    const dates = [];
    const startDate = new Date(sub.date);
    const recurrence = sub.recurrence.toLowerCase();
    const count = parseInt(sub.recurrence_count);
    for (let i = 0; i < count; i++) {
      const nextDate = new Date(startDate);
      if (recurrence === 'monthly') nextDate.setMonth(nextDate.getMonth() + i);
      else if (recurrence === 'yearly') nextDate.setFullYear(nextDate.getFullYear() + i);
      else if (recurrence === 'weekly') nextDate.setDate(nextDate.getDate() + i * 7);
      dates.push(nextDate.toISOString().split("T")[0]);
    }
    return dates;
  }

  function buildCalendar(month, year) {
    calendarEl.innerHTML = "";

    const weekDays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    weekDays.forEach(day => {
      const head = document.createElement("div");
      head.innerHTML = `<strong>${day}</strong>`;
      calendarEl.appendChild(head);
    });

    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const recurringMap = {};

    subscriptions.forEach(sub => {
      getRecurringDates(sub).forEach(date => {
        recurringMap[date] = true;
      });
    });

    const today = new Date();
    const todayStr = formatDate(today.getFullYear(), today.getMonth(), today.getDate());

    for (let i = 0; i < firstDay; i++) calendarEl.appendChild(document.createElement("div"));

    for (let d = 1; d <= daysInMonth; d++) {
      const cell = document.createElement("div");
      cell.classList.add("calendar-day");
      cell.textContent = d;
      const cellDate = formatDate(year, month, d);
      if (cellDate === todayStr) cell.classList.add("today");
      if (recurringMap[cellDate]) cell.classList.add("due");
      calendarEl.appendChild(cell);
    }

    monthYearLabel.textContent = `${monthSelect.options[month].text} ${year}`;
    renderMonthlySubs(month, year);
  }

  function renderMonthlySubs(month, year) {
    monthlySubList.innerHTML = "";
    const filtered = subscriptions.filter(sub =>
      getRecurringDates(sub).some(dateStr => {
        const d = new Date(dateStr);
        return d.getMonth() === month && d.getFullYear() === year;
      })
    );

    if (filtered.length === 0) {
      monthlySubList.innerHTML = "<p>No subscriptions this month.</p>";
      return;
    }

    filtered.forEach(sub => {
      const div = document.createElement("div");
      div.classList.add("subscription-entry");
      div.innerHTML = `
        <strong>${sub.name}</strong> - ₹${parseFloat(sub.amount).toFixed(2)}<br>
        Renewal Date: ${sub.date}<br>
        Recurrence: ${sub.recurrence} (${sub.recurrence_count} times)
      `;
      monthlySubList.appendChild(div);
    });
  }

  function populateSelectors(defaultMonth, defaultYear) {
    const monthNames = [
      "January", "February", "March", "April", "May", "June",
      "July", "August", "September", "October", "November", "December"
    ];
    for (let m = 0; m < 12; m++) {
      const opt = document.createElement("option");
      opt.value = m;
      opt.textContent = monthNames[m];
      monthSelect.appendChild(opt);
    }

    const yearNow = new Date().getFullYear();
    for (let y = yearNow - 5; y <= yearNow + 5; y++) {
      const opt = document.createElement("option");
      opt.value = y;
      opt.textContent = y;
      yearSelect.appendChild(opt);
    }

    monthSelect.value = defaultMonth;
    yearSelect.value = defaultYear;
  }

  document.addEventListener("DOMContentLoaded", () => {
    const now = new Date();
    const month = now.getMonth();
    const year = now.getFullYear();

    populateSelectors(month, year);
    buildCalendar(month, year);

    goBtn.addEventListener("click", () => {
      const selectedMonth = parseInt(monthSelect.value);
      const selectedYear = parseInt(yearSelect.value);
      buildCalendar(selectedMonth, selectedYear);
    });

    todayBtn.addEventListener("click", () => {
      const today = new Date();
      const m = today.getMonth();
      const y = today.getFullYear();
      monthSelect.value = m;
      yearSelect.value = y;
      buildCalendar(m, y);
    });
  });
</script>


<?php include 'footer.php'; ?>
