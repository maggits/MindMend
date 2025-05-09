<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Mood Tracker 😊</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
      background-color: #006A71;
      min-height: 100vh;
      display: flex;
      align-items: flex-start;
      justify-content: center;
      padding: 50px 20px;
    }

    .container_mt-5 {
      background-color: #9ACBD0;
      backdrop-filter: blur(15px);
      box-shadow: 0 8px 32px rgba(0,0,0,0.2);
      border-radius: 20px;
      padding: 40px 30px;
      max-width: 600px;
      width: 100%;
      animation: fadeIn 0.9s ease;
      color: #333;
    }

    @keyframes fadeIn {
      from {opacity: 0; transform: translateY(20px);}
      to {opacity: 1; transform: translateY(0);}
    }

    h1.text-center {
      text-align: center;
      color: #333;
      font-size: 2.4rem;
      margin-bottom: 25px;
    }

    h2 {
      font-size: 1.2rem;
      margin-top: 20px;
      margin-bottom: 10px;
    }

    .form-label {
      display: block;
      margin-bottom: 10px;
      font-weight: 600;
      font-size: 1.1rem;
    }

    .form-select, .form-control, textarea {
      width: 100%;
      padding: 12px 14px;
      border: none;
      border-radius: 12px;
      background: rgba(255, 255, 255, 0.7);
      font-size: 1rem;
      margin-bottom: 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      outline: none;
      resize: none;
    }

    input[type=range] {
      width: 100%;
      margin: 10px 0 5px;
      -webkit-appearance: none;
      background: transparent;
    }

    input[type=range]::-webkit-slider-thumb {
      -webkit-appearance: none;
      height: 22px;
      width: 22px;
      border-radius: 50%;
      background: #ff9a76;
      cursor: pointer;
      box-shadow: 0 0 6px rgba(0,0,0,0.2);
    }

    input[type=range]::-webkit-slider-runnable-track {
      height: 6px;
      background: #fff;
      border-radius: 3px;
      box-shadow: inset 0 0 3px rgba(0,0,0,0.1);
    }

    button {
      display: block;
      width: 100%;
      background: #ffffff;
      border: none;
      border-radius: 30px;
      padding: 15px;
      font-size: 1.1rem;
      font-weight: 600;
      color: #333;
      cursor: pointer;
      transition: background 0.3s;
      box-shadow: 0 6px 18px rgba(0,0,0,0.15);
    }

    button:hover {
      background: #ffe4b3;
    }

    .button1 {
      display: inline-flex;
      align-items: center;
      background-color: #ffffff;
      border: none;
      border-radius: 30px;
      padding: 10px 16px;
      font-size: 1rem;
      font-weight: 600;
      color: #333;
      text-decoration: none;
      transition: all 0.2s linear;
      cursor: pointer;
      box-shadow: 0 4px 14px rgba(0,0,0,0.1);
      margin-bottom: 25px;
    }

    .button1 svg {
      margin-right: 8px;
      transition: all 0.3s ease;
    }

    .button1:hover svg {
      transform: translateX(-5px);
    }

    .button1:hover {
      background: #ffe4b3;
      box-shadow: 0 6px 18px rgba(0,0,0,0.15);
    }

    #scaleValue {
      font-weight: 600;
      color: #333;
      margin-left: 8px;
    }
  </style>
</head>

<body>
  <div class="container_mt-5">
    <!-- Back Button -->
    <a href="dashboard.php" class="button1">
      <svg height="16" width="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024">
        <path d="M874.690416 495.52477c0 11.2973-9.168824 20.466124-20.466124 20.466124l-604.773963 0 
        188.083679 188.083679c7.992021 7.992021 7.992021 20.947078 0 28.939099-4.001127 3.990894-9.240455 
        5.996574-14.46955 5.996574-5.239328 0-10.478655-1.995447-14.479783-5.996574l-223.00912-223.00912
        c-3.837398-3.837398-5.996574-9.046027-5.996574-14.46955 0-5.433756 2.159176-10.632151 
        5.996574-14.46955l223.019353-223.029586c7.992021-7.992021 20.957311-7.992021 28.949332 0 
        7.992021 8.002254 7.992021 20.957311 0 28.949332l-188.073446 188.073446 
        604.753497 0C865.521592 475.058646 874.690416 484.217237 874.690416 495.52477z">
        </path>
      </svg>
      <span>Back</span>
    </a>

    <!-- Title -->
    <h1 class="text-center">😊 Mood Tracker</h1>

    <!-- Mood Tracker Form -->
    <form action="journal.php" method="post">
      <div>
        <label for="mood" class="form-label">How are you feeling today?</label>
        <select class="form-select" id="mood" name="mood" required>
          <option value="">Select your mood</option>
          <option value="🙂 Happy">🙂 Happy — feeling or showing pleasure or contentment.</option>
          <option value="😢 Sad">😢 Sad — affected with or expressive of grief or unhappiness.</option>
          <option value="😄 Excited">😄 Excited — very enthusiastic and eager.</option>
          <option value="😡 Angry">😡 Angry — strong annoyance, displeasure, or hostility.</option>
          <option value="😰 Anxiety">😰 Anxiety — a feeling of fear, dread, and uneasiness.</option>
          <option value="😱 Fear">😱 Fear — belief something is dangerous or a threat.</option>
          <option value="😳 Self-Conscious">😳 Self-Conscious — worried about disapproval from others.</option>
          <option value="😐 Neutral">😐 Neutral — lacking strong or intense feelings.</option>
        </select>
      </div>

      <h2>On a scale of 1-10, how would you rate your mood today?</h2>

      <!-- Range input for mood rating -->
      <input type="range" name="happiness" min="1" max="10" value="5" step="1"
             oninput="document.getElementById('scaleValue').textContent = this.value">
             
      <!-- Display the current value -->
      <span id="scaleValue">10</span>
      <div>
        <label for="note" class="form-label">Add a note (optional)</label>
        <textarea class="form-control" id="note" name="note" rows="3" placeholder="Write something..."></textarea>
      </div>

      <button type="submit">Save Mood</button>
    </form>
  </div>
</body>
</html>
