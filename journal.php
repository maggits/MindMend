<?php
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login2.php");
    exit();
}

$file = 'mood-data.json';
$user_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mood = $_POST['mood'] ?? '';
    $happiness = $_POST['happiness'] ?? '';
    $note = $_POST['note'] ?? '';
    $date = date('Y-m-d H:i:s');

    // Validate happiness value to ensure it's between 1 and 10
    if ($mood && is_numeric($happiness) && $happiness >= 1 && $happiness <= 10) {
        if (!file_exists($file)) {
            file_put_contents($file, '[]');
        }
        $json = file_get_contents($file);
        $data = json_decode($json, true);

        $entry_id = uniqid(); // unique ID for each entry

        $data[] = [
            'id'        => $entry_id,
            'user_id'   => $user_id,
            'mood'      => $mood,
            'happiness' => $happiness,
            'note'      => $note,
            'date'      => $date
        ];

        file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));

        header("Location: journal.php");
        exit();
    } else {
        // If validation fails, show an error message
        $error_message = "Please select a valid happiness level between 1 and 10.";
    }
}

// Handle entry deletion by ID
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];

    if (file_exists($file)) {
        $json = file_get_contents($file);
        $allEntries = json_decode($json, true);

        $newEntries = array_filter($allEntries, function($entry) use ($deleteId, $user_id) {
            return !($entry['id'] === $deleteId && $entry['user_id'] == $user_id);
        });

        file_put_contents($file, json_encode(array_values($newEntries), JSON_PRETTY_PRINT));
    }

    header("Location: journal.php");
    exit();
}

// Load mood entries for current user
if (file_exists($file)) {
    $json = file_get_contents($file);
    $allEntries = json_decode($json, true);

    $entries = array_values(array_filter($allEntries, function($entry) use ($user_id) {
        return $entry['user_id'] == $user_id;
    }));
} else {
    $entries = [];
}

// Mood to emoji mapping
$moodEmojiMap = [
    'Happy'    => '😊',
    'Sad'      => '😢',
    'Angry'    => '😠',
    'Excited'  => '🤩',
    'Anxious'  => '😰',
    'Calm'     => '😌',
    'Tired'    => '😴',
    'Stressed' => '😣'
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Mood Journal 📔</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      padding: 30px 20px;
      font-family: 'Poppins', sans-serif;
      background-color: #006A71;
      min-height: 100vh;
      display: flex;
      align-items: flex-start;
      justify-content: center;
      padding: 50px 20px;
    }
    .container {
      background-color: #9ACBD0;
      backdrop-filter: blur(15px);
      box-shadow: 0 8px 32px rgba(0,0,0,0.2);
      border-radius: 20px;
      padding: 40px 30px;
      max-width: 680px;
      width: 100%;
      animation: fadeIn 0.9s ease;
      color: #333;
    }
    @keyframes fadeIn {
      from {opacity: 0; transform: translateY(20px);}
      to {opacity: 1; transform: translateY(0);}
    }
    h1 {
      text-align: center;
      font-size: 2.2rem;
      margin-bottom: 20px;
      color: #333;
    }
    .journal-entry {
      background: rgba(255, 255, 255, 0.65);
      padding: 18px 20px;
      border-radius: 14px;
      margin-bottom: 15px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .journal-entry h3 {
      margin: 0 0 6px;
      font-size: 1.1rem;
    }
    .journal-entry p {
      margin: 3px 0;
      font-size: 0.95rem;
    }
    .back-button {
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
    .back-button:hover {
      background: #ffe4b3;
    }
    .delete-button {
      background-color: red;
      color: white;
      padding: 5px 10px;
      border-radius: 5px;
      text-decoration: none;
      float: right;
    }
    .delete-button:hover {
      background-color: darkred;
    }
  </style>
</head>
<body>

  <div class="container">
    <a href="dashboard.php" class="back-button">← Back</a>
    <h1>📔 Mood Journal</h1>

    <!-- Show error message if there is one -->
    <?php if (!empty($error_message)) : ?>
      <div style="color: red; text-align: center;"><?= $error_message ?></div>
    <?php endif; ?>

    <?php if (!empty($entries)) :
      // Sort newest first
      usort($entries, function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
      });
    ?>

      <?php foreach ($entries as $entry) :
        $emoji = $moodEmojiMap[$entry['mood']] ?? '';
      ?>
        <div class="journal-entry">
          <h3><?= $emoji ?> <?= htmlspecialchars($entry['mood']) ?> — <?= htmlspecialchars($entry['date']) ?>
            <a href="?delete_id=<?= $entry['id'] ?>" class="delete-button"
               onclick="return confirm('Are you sure you want to delete this entry?');">Delete</a>
          </h3>
          <p><strong>Intensity:</strong> <?= htmlspecialchars($entry['happiness']) ?>/10</p>
          <?php if (!empty($entry['note'])) : ?>
            <p><strong>Note:</strong> <?= nl2br(htmlspecialchars($entry['note'])) ?></p>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>

    <?php else : ?>
      <p>No entries yet. Head back and log your first mood!</p>
    <?php endif; ?>
  </div>

</body>
</html>
