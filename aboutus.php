<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us - MindMend</title>
  <style>
    body {
      background-color: #006A71;
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      color: white;
    }

    header {
      background-color: #9ACBD0;
      padding: 10px 20px;
    }

    .container {
      position: relative;
      max-width: 800px;
      margin: 50px auto;
  background-color: #9ACBD0;
      color: #333;
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.15);
      text-align: center;
      animation: fadeIn 0.8s ease;
    }

    .container img {
      width: 200px;
      margin-bottom: 2px; /* reduced space between logo and heading */
    }

    .container h1 {
      margin-bottom: 25px;
      color: #006A71;
    }

    .container p {
      text-align: justify;
      line-height: 1.8;
      font-size: 16px;
      margin-bottom: 15px;
    }

    .back-btn {
      position: absolute;
      top: 20px;
      left: 20px;
      background-color: #006A71;
      color: white;
      text-decoration: none;
      padding: 10px 15px;
      border-radius: 5px;
    }

    .back-btn:hover {
      background-color: #004c4c;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 768px) {
      .nav-links {
        flex-direction: column;
      }

      .navbar {
        flex-direction: column;
        align-items: flex-start;
      }

      .container {
        margin: 30px 15px;
        padding: 20px;
      }
    }
  </style>
</head>
<body>

<header>
  <!-- You can add your header content here -->
</header>

<div class="container">
  <!-- Back Button positioned at the upper left corner -->
  <a href="dashboard.php" class="back-btn">Back to Dashboard</a>

  <img src="IMAGES/d.png" alt="MindMend Logo">
  <h1>About Us</h1>
  <p>
    Welcome to <strong>MindMend</strong>, your personal mental health companion. Our mission is to provide you with the tools and resources you need to track your mood, reflect on your thoughts, and improve your overall well-being.
  </p>
  <p>
    At <strong>MindMend</strong>, we believe that mental health is just as important as physical health. That's why we've created a user-friendly platform that allows you to easily log your moods, journal your thoughts, and monitor your progress over time.
  </p>
  <p>
    Our team is dedicated to helping you on your journey to better mental health. We are constantly working to improve our platform and provide you with the best possible experience.
  </p>
</div>

</body>
</html>
