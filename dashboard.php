<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="dsgn.css">
  <title>MindMend</title>
</head>
<body>
  <header>
    <nav class="navbar">
      <img src="IMAGES/d.png" />

      <ul class="nav-links">
        <li class="home"><a href="dashboard.php">Home</a></li>

        <li class="dropdown">
          <input type="checkbox" id="menu-toggle">
          <label for="menu-toggle" class="toggle">
            <div class="bars"></div>
            <div class="bars"></div>
            <div class="bars"></div>
          </label>
          
          <div class="dropdown-content">
            <a href="profile.php">Profile</a>
            <a href="aboutus.php">About Us</a>
          </div>
        </li>
      </ul>
    </nav>
  </header>

  <main>
    <h1>Welcome to MindMend</h1>
  </main>

  <div class="icon-box-solid">
    <h2>Quick Access</h2>
    <div class="icon-grid">
      <a href="moodtracker.php" class="icon-content mood" data-social="mood tracker">
        <span class="emoji">😊</span>
        <div class="tooltip">Mood Tracker</div>
      </a>

      <a href="journal.php" class="icon-content journal" data-social="journal">
        <span class="emoji">📓</span>
        <div class="tooltip">Journal</div>
      </a>

      <a href="progress_report.php" class="icon-content report" data-social="progress report">
        <span class="emoji">📊</span>
        <div class="tooltip">Progress Report</div>
      </a>
    </div>
  </div>
</body>
</html>
