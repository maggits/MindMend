<?php
session_start();

// Database config
$host = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "mindb";

$conn = new mysqli($host, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$username = $email = "";
$errors = [];

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Send OTP Email Function
function sendOTPEmail($email) {
    // Generate OTP
    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_email'] = $email;

    // Send OTP via SMTP
    $subject = "Your OTP Code";
    $message = "Your OTP code is: " . $otp;

    // Using SMTPJS for sending email (for demo purposes, you should configure SMTP server in production)
    echo "<script type='text/javascript'>
        Email.send({
            Host: 'smtp.yourdomain.com',  // Replace with your SMTP host
            Username: 'your_email@domain.com',  // Replace with your email address
            Password: 'your_email_password',  // Replace with your email password
            To: '$email',
            From: 'your_email@domain.com',
            Subject: '$subject',
            Body: '$message'
        }).then(
            message => alert('OTP sent successfully')
        );
    </script>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitize($_POST["username"] ?? "");
    $email = sanitize($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";
    $terms = isset($_POST["terms"]) ? true : false; // Check if terms are accepted

    // Validations
    if (strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // Ensure user accepts terms and conditions
    if (!$terms) {
        $errors[] = "You must agree to the terms and conditions.";
    }

    // Check for duplicate username or email
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM accounts WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = "Username or email already taken.";
        }
        $stmt->close();
    }

    // Insert into DB if no errors
    if (empty($errors)) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO accounts (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $passwordHash);

        if ($stmt->execute()) {
            sendOTPEmail($email); // Send OTP email after successful registration
            $stmt->close();
            header("Location: login2.php?registered=1");
            exit;
        } else {
            $errors[] = "Error in registration. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://smtpjs.com/v3/smtp.js"></script>
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
    background-color: #006A71;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 400px;
            margin: 50px auto;
    background-color: #9ACBD0;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .terms-container {
            margin-bottom: 15px;
        }

        .error {
            background-color: #ffdddd;
            border-left: 5px solid #f44336;
            margin: 10px 0;
            padding: 10px;
            border-radius: 4px;
        }

        .error ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .error li {
            margin: 5px 0;
        }

        input[type="submit"], button {
            background: #007bff;
            color: white;
            font-size: 16px;
            border: none;
            padding: 12px;
            border-radius: 5px;
            width: 100%;
            cursor: pointer;
        }

        input[type="submit"]:hover, button:hover {
            background: #0056b3;
        }

        .login-link {
            text-align: center;
            margin-top: 15px;
        }

        .login-link a {
            color: #007bff;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .otp-button {
            background: #28a745;
            width: auto;
            padding: 10px;
            margin-left: 10px;
        }

        .otp-button:hover {
            background: #218838;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Register</h2>

    <?php
    if (!empty($errors)) {
        echo '<div class="error"><ul>';
        foreach ($errors as $error) {
            echo '<li>' . $error . '</li>';
        }
        echo '</ul></div>';
    }
    ?>

    <form action="register2.php" method="POST" novalidate>
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="Enter username" required value="<?php echo htmlspecialchars($username); ?>" />

        <label for="email">Email</label>
        <div class="otpverify" style="display: flex; align-items: center;">
            <input type="email" id="email" name="email" placeholder="Enter email" required value="<?php echo htmlspecialchars($email); ?>" style="flex: 1;" />
            <button type="button" class="otp-button" onclick="sendOTP()">Send OTP</button>
        </div>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter password" required />

        <label for="confirm_password">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm password" required />

        <div class="terms-container">
            <label for="terms">
                <input type="checkbox" name="terms" id="terms" required /> I agree to the <a href="javascript:void(0);" onclick="openModal()">Terms and Conditions</a>
            </label>
        </div>

        <input type="submit" value="Register" />
    </form>

    <div class="login-link">
        Already registered? <a href="login2.php">Login here</a>.
    </div>
</div>

<!-- Modal for Terms and Conditions -->
<div id="termsModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Terms and Conditions</h2>
        <p>Here are the terms and conditions...</p>
        <p>... more content ...</p>
    </div>
</div>

<script>
// Function to open and close the Terms Modal
function openModal() {
    document.getElementById('termsModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('termsModal').style.display = 'none';
}

// OTP Email sending function
function sendOTP() {
    var email = document.getElementById('email').value;
    if (email) {
        alert("OTP sent to your email!");
    } else {
        alert("Please enter a valid email.");
    }
}
</script>
</body>
</html>
