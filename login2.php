<?php
session_start();

$host = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "mindb";

$conn = new mysqli($host, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = "";
$errors = [];

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = sanitize($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if (empty($email)) {
        $errors[] = "Please enter your email.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($password)) {
        $errors[] = "Please enter your password.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id, username, email, password FROM accounts WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user["password"])) {
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["username"] = $user["username"];
                header("Location: dashboard.php");
                exit;
            } else {
                $errors[] = "Incorrect password.";
            }
        } else {
            $errors[] = "No account found with that email.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login</title>
<style>
  * {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
  }
  body {
    font-family: 'Segoe UI', sans-serif;
    background-color: #006A71;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .container {
    background-color: #9ACBD0;
    padding: 35px 30px;
    border-radius: 16px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    width: 100%;
    max-width: 400px;
  }
  h2 {
    text-align: center;
    color: #333;
    margin-bottom: 25px;
  }
  label {
    font-weight: 600;
    font-size: 14px;
  }
  input[type="email"], .password-field {
    width: 100%;
    padding: 14px 45px 14px 14px;
    margin-top: 8px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 15px;
    transition: 0.2s ease;
  }
  input[type="email"]:focus, .password-field:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
  }
  input[type="submit"] {
    background: #007bff;
    color: #fff;
    padding: 14px;
    border: none;
    border-radius: 8px;
    width: 100%;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s ease;
  }
  input[type="submit"]:hover {
    background: #0056b3;
  }
  .error {
    background: #ffdddd;
    border-left: 5px solid #f44336;
    padding: 12px;
    margin-bottom: 20px;
    border-radius: 6px;
  }
  .login-link {
    text-align: center;
    margin-top: 20px;
    font-size: 14px;
  }
  .login-link a {
    color: #007bff;
    text-decoration: none;
    font-weight: 600;
  }
  .login-link a:hover {
    text-decoration: underline;
  }
  .password-container {
    position: relative;
  }
  .toggle-password {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    padding: 4px;
    transition: 0.2s ease;
  }
  .toggle-password svg {
    display: block;
  }
  .toggle-password:hover svg {
    fill: #007bff;
  }

  /* Responsive design */
  @media (max-width: 768px) {
    .container {
      width: 90%;
      padding: 20px;
    }
  }
</style>
</head>
<body>

<div class="container">
  <h2>Login</h2>

  <?php
  if (!empty($errors)) {
      echo '<div class="error"><ul>';
      foreach ($errors as $error) {
          echo '<li>' . $error . '</li>';
      }
      echo '</ul></div>';
  }

  if (isset($_GET["registered"]) && $_GET["registered"] == 1) {
      echo '<div class="success" style="background: #d4edda; padding: 12px; border-left: 5px solid #28a745; margin-bottom: 20px; border-radius: 6px;">Registration successful. You can now log in.</div>';
  }
  ?>

  <form action="login2.php" method="POST">
    <label for="email">Email</label>
    <input type="email" id="email" name="email" placeholder="Enter your email" required value="<?php echo htmlspecialchars($email); ?>">

    <label for="password">Password</label>
    <div class="password-container">
      <input type="password" id="password" name="password" class="password-field" placeholder="Enter your password" required>
      <span class="toggle-password" id="togglePassword">
        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" height="22" viewBox="0 0 24 24" width="22" fill="#666">
          <path d="M12 4.5C7.05 4.5 2.73 7.61 1 12c1.73 4.39 6.05 7.5 11 7.5s9.27-3.11 11-7.5C21.27 7.61 16.95 4.5 12 4.5zm0 13c-3.59 0-6.5-2.91-6.5-6.5S8.41 4.5 12 4.5 18.5 7.41 18.5 11 15.59 17.5 12 17.5zm0-10A3.5 3.5 0 1 0 15.5 11 3.5 3.5 0 0 0 12 7.5z"/>
        </svg>
      </span>
    </div>

    <input type="submit" value="Login">
  </form>

  <div class="login-link">
    Don't have an account? <a href="register2.php">Register here</a>.
  </div>
</div>

<script>
  const passwordInput = document.getElementById("password");
  const togglePassword = document.getElementById("togglePassword");

  togglePassword.addEventListener("click", function () {
    const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
    passwordInput.setAttribute("type", type);

    const eyeIcon = document.getElementById("eyeIcon");

    if (type === "text") {
      // Eye-off icon when password is visible
      eyeIcon.innerHTML = `<path d="M12 5c-7.633 0-11 7-11 7s3.367 7 11 7 11-7 11-7-3.367-7-11-7zm0 12c-2.76 0-5-2.238-5-5 0-.671.133-1.313.373-1.898L3.707 6.707 5.121 5.293 19.707 19.879 18.293 21.293l-3.553-3.553A7.94 7.94 0 0 1 12 17z"/>`;
    } else {
      // Eye icon when password is hidden
      eyeIcon.innerHTML = `<path d="M12 4.5C7.05 4.5 2.73 7.61 1 12c1.73 4.39 6.05 7.5 11 7.5s9.27-3.11 11-7.5C21.27 7.61 16.95 4.5 12 4.5zm0 13c-3.59 0-6.5-2.91-6.5-6.5S8.41 4.5 12 4.5 18.5 7.41 18.5 11 15.59 17.5 12 17.5zm0-10A3.5 3.5 0 1 0 15.5 11 3.5 3.5 0 0 0 12 7.5z"/>`;
    }
  });
</script>

</body>
</html>
