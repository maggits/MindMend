<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

$file = 'mood-data.json';

// Load mood entries from the journal
if (file_exists($file)) {
    $json = file_get_contents($file);
    $entries = json_decode($json, true);
} else {
    $entries = [];
}

$view = $_GET['view'] ?? 'weekly';
$moods = ['🙂 Happy', '😢 Sad', '😄 Excited', '😡 Angry', '😰 Anxiety', '😱 Fear', '😳 Self-Conscious', '😐 Neutral'];
$colors = [
    '🙂 Happy' => 'rgba(255, 206, 86, 1)',
    '😢 Sad' => 'rgba(54, 162, 235, 1)',
    '😄 Excited' => 'rgba(75, 192, 192, 1)',
    '😡 Angry' => 'rgba(255, 99, 132, 1)',
    '😰 Anxiety' => 'rgba(153, 102, 255, 1)',
    '😱 Fear' => 'rgba(255, 159, 64, 1)',
    '😳 Self-Conscious' => 'rgba(255, 205, 86, 1)',
    '😐 Neutral' => 'rgba(201, 203, 207, 1)'
];

$dataSets = [];

if ($view === 'weekly') {
    $labels = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    $moodSums = array_fill_keys($moods, array_fill(0, 7, 0));
    $moodCounts = array_fill_keys($moods, array_fill(0, 7, 0));

    $currentWeek = date('W');
    $currentYear = date('o');

    foreach ($entries as $entry) {
        // Filter by the logged-in user's ID
        if ($entry['user_id'] == $_SESSION['user_id']) {
            $timestamp = strtotime($entry['date']);
            if (date('W', $timestamp) == $currentWeek && date('o', $timestamp) == $currentYear) {
                $dayOfWeek = date('w', $timestamp);
                $mood = $entry['mood'];
                if (in_array($mood, $moods)) {
                    // Scale the happiness value to 10
                    $moodSums[$mood][$dayOfWeek] += $entry['happiness']; 
                    $moodCounts[$mood][$dayOfWeek]++;
                }
            }
        }
    }

    foreach ($moods as $mood) {
        $data = [];
        for ($i = 0; $i < 7; $i++) {
            $average = $moodCounts[$mood][$i] > 0 ? $moodSums[$mood][$i] / $moodCounts[$mood][$i] : 0;
            $data[] = round($average, 2);
        }
        $dataSets[] = [
            'label' => $mood,
            'data' => $data,
            'borderColor' => $colors[$mood],
            'backgroundColor' => $colors[$mood],
            'tension' => 0.3
        ];
    }

} elseif ($view === 'monthly') {
    $daysInMonth = date('t');
    $labels = range(1, $daysInMonth);

    $moodSums = array_fill_keys($moods, array_fill(1, $daysInMonth, 0));
    $moodCounts = array_fill_keys($moods, array_fill(1, $daysInMonth, 0));

    $currentMonth = date('m');
    $currentYear = date('Y');

    foreach ($entries as $entry) {
        // Filter by the logged-in user's ID
        if ($entry['user_id'] == $_SESSION['user_id']) {
            $timestamp = strtotime($entry['date']);
            if (date('m', $timestamp) == $currentMonth && date('Y', $timestamp) == $currentYear) {
                $day = (int)date('j', $timestamp);
                $mood = $entry['mood'];
                if (in_array($mood, $moods)) {
                    // Scale the happiness value to 10
                    $moodSums[$mood][$day] += $entry['happiness'];
                    $moodCounts[$mood][$day]++;
                }
            }
        }
    }

    foreach ($moods as $mood) {
        $data = [];
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $average = $moodCounts[$mood][$i] > 0 ? $moodSums[$mood][$i] / $moodCounts[$mood][$i] : 0;
            $data[] = round($average, 2);
        }
        $dataSets[] = [
            'label' => $mood,
            'data' => $data,
            'borderColor' => $colors[$mood],
            'backgroundColor' => $colors[$mood],
            'tension' => 0.3
        ];
    }

} elseif ($view === 'yearly') {
    $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    $moodSums = array_fill_keys($moods, array_fill(1, 12, 0));
    $moodCounts = array_fill_keys($moods, array_fill(1, 12, 0));

    $currentYear = date('Y');

    foreach ($entries as $entry) {
        // Filter by the logged-in user's ID
        if ($entry['user_id'] == $_SESSION['user_id']) {
            $timestamp = strtotime($entry['date']);
            if (date('Y', $timestamp) == $currentYear) {
                $month = (int)date('n', $timestamp);
                $mood = $entry['mood'];
                if (in_array($mood, $moods)) {
                    // Scale the happiness value to 10
                    $moodSums[$mood][$month] += $entry['happiness']; 
                    $moodCounts[$mood][$month]++;
                }
            }
        }
    }

    foreach ($moods as $mood) {
        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $average = $moodCounts[$mood][$i] > 0 ? $moodSums[$mood][$i] / $moodCounts[$mood][$i] : 0;
            $data[] = round($average, 2);
        }
        $dataSets[] = [
            'label' => $mood,
            'data' => $data,
            'borderColor' => $colors[$mood],
            'backgroundColor' => $colors[$mood],
            'tension' => 0.3
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Mood Progress Report 📊</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #006A71;
      padding: 30px;
      color: #333;
    }
    .container {
      max-width: 1000px;
      margin: auto;
      background-color: #9ACBD0;
      padding: 30px;
      border-radius: 20px;
      backdrop-filter: blur(15px);
      box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    }
    h1 {
      text-align: center;
      font-size: 2.2rem;
      margin-bottom: 20px;
    }
    .view-buttons {
      text-align: center;
      margin-bottom: 25px;
    }
    .view-buttons a {
      text-decoration: none;
      background: #fff;
      padding: 10px 18px;
      border-radius: 30px;
      margin: 0 5px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.15);
      color: #333;
      transition: all 0.3s;
    }
    .view-buttons a:hover {
      background: #ffe4b3;
    }
    .month-display {
      text-align: center;
      margin-bottom: 20px;
      font-size: 1.2rem;
    }
    .button1 {
      display: inline-block;
      margin-bottom: 20px;
      padding: 10px 18px;
      background: #fff;
      border-radius: 30px;
      text-decoration: none;
      color: #333;
      box-shadow: 0 6px 18px rgba(0,0,0,0.15);
      transition: background 0.3s ease;
    }
    .button1:hover {
      background: #ffe4b3;
    }
  </style>
</head>
<body>

<div class="container">
<!-- Back Button -->
<a href="dashboard.php" class="button1">
  ← Back
</a>

  <h1>📊 Mood Progress Report (<?= ucfirst($view) ?>)</h1>

  <div class="view-buttons">
    <a href="?view=weekly">Weekly</a>
    <a href="?view=monthly">Monthly</a>
    <a href="?view=yearly">Yearly</a>
  </div>

  <?php if ($view === 'monthly'): ?>
    <div class="month-display">📅 <?= date('F') ?> <?= date('Y') ?></div>
  <?php endif; ?>

  <canvas id="moodChart"></canvas>
</div>

<script>
const ctx = document.getElementById('moodChart').getContext('2d');

const moodChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: <?= json_encode($dataSets) ?>
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'top' },
            title: {
                display: true,
                text: 'Mood Trends (0-10 Scale)'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: 10, // Set the max value to 10
                title: { display: true, text: 'Average Moods (0-10)' }
            },
            x: {
                title: { display: true, text: '<?= $view === "weekly" ? "Day of the Week" : ($view === "monthly" ? "Day of the Month" : "Month of the Year") ?>' }
            }
        }
    }
});
</script>

</body>
</html>
