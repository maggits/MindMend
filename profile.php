<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login2.php");
    exit;
}

$host = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "mindb";

// Connect to DB
$conn = new mysqli($host, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userId = $_SESSION["user_id"];

// Fetch current user info (including current profile picture path)
$stmt = $conn->prepare("SELECT username, email, password_length, profile_picture FROM accounts WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($username, $email, $passwordLength, $profilePicture);
$stmt->fetch();
$stmt->close();

// Handle profile picture upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["profile_picture"])) {
    $uploadDir = "uploads/";
    $fileName = time() . "_" . basename($_FILES["profile_picture"]["name"]);
    $uploadFile = $uploadDir . $fileName;

    if (getimagesize($_FILES["profile_picture"]["tmp_name"])) {
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $uploadFile)) {
            // Delete old profile picture if exists and is not default
            if (!empty($profilePicture) && file_exists($profilePicture) && $profilePicture !== 'uploads/default-profile.png') {
                unlink($profilePicture);
            }

            // Update profile picture in DB
            $stmt = $conn->prepare("UPDATE accounts SET profile_picture = ? WHERE id = ?");
            $stmt->bind_param("si", $uploadFile, $userId);
            $stmt->execute();
            $stmt->close();

            header("Location: profile.php");
            exit;
        } else {
            echo "Error uploading your file.";
        }
    } else {
        echo "File is not an image.";
    }
}

// Fetch updated user info after potential upload
$stmt = $conn->prepare("SELECT username, email, password_length, profile_picture FROM accounts WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($username, $email, $passwordLength, $profilePicture);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>User Profile</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {
    font-family: 'Segoe UI', Tahoma, sans-serif;
    background-color: #006A71;
    margin: 0;
    padding: 0;
}

.profile-container {
    position: relative;
    max-width: 450px;
    margin: 60px auto;
    background-color: #9ACBD0;
    border-radius: 16px;
    padding: 35px 30px 30px;
    text-align: center;
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    animation: fadeInSlide 0.7s ease;
}

@keyframes fadeInSlide {
    from { opacity: 0; transform: translateY(30px);}
    to { opacity: 1; transform: translateY(0);}
}

.button1 {
    position: absolute;
    top: 20px;
    left: 20px;
    display: flex;
    align-items: center;
    gap: 8px;
    background: #f0f0f0;
    color: #333;
    text-decoration: none;
    padding: 10px 14px;
    border-radius: 50px;
    font-size: 14px;
    transition: background 0.3s ease, transform 0.2s ease;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.button1 svg {
    fill: #333;
    transition: fill 0.3s ease;
}

.button1:hover {
    background: #ddd;
    transform: scale(1.05);
}

.button1:hover svg {
    fill: #000;
}

.profile-pic-wrapper {
    position: relative;
    display: inline-block;
    margin-bottom: 20px;
}

.profile-pic {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #ddd;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.profile-pic:hover {
    transform: scale(1.05);
    box-shadow: 0 0 12px rgba(0,0,0,0.2);
}

.upload-btn {
    position: absolute;
    bottom: 0;
    right: 0;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 50%;
    width: 38px;
    height: 38px;
    cursor: pointer;
    font-size: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.3s ease;
}

.upload-btn:hover {
    background: #0056b3;
}

.profile-details {
    margin-top: 25px;
    text-align: left;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    padding: 14px 0;
    border-bottom: 1px solid #eee;
}

.detail-row:last-child {
    border-bottom: none;
}

.label {
    font-weight: 600;
    color: #555;
}

.value {
    color: #222;
}

.btn {
    display: block;
    width: 100%;
    padding: 14px 0;
    margin-top: 18px;
    text-align: center;
    text-decoration: none;
    border-radius: 8px;
    font-size: 16px;
    transition: all 0.3s ease;
}

.btn.primary {
    background-color: #007bff;
    color: #fff;
}

.btn.primary:hover {
    background-color: #0056b3;
}

.btn.danger {
    background-color: #dc3545;
    color: #fff;
}

.btn.danger:hover {
    background-color: #c82333;
}

input[type="file"] {
    display: none;
}
</style>
</head>
<body>

<div class="profile-container">
    <a href="dashboard.php" class="button1" title="Back to Dashboard">
        <svg height="16" width="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024">
        <path d="M874.690416 495.52477c0 11.2973-9.168824 20.466124-20.466124 20.466124l-604.773963 0 
        188.083679 188.083679c7.992021 7.992021 7.992021 20.947078 0 28.939099-4.001127 3.990894-9.240455 
        5.996574-14.46955 5.996574-5.239328 0-10.478655-1.995447-14.479783-5.996574l-223.00912-223.00912
        c-3.837398-3.837398-5.996574-9.046027-5.996574-14.46955 0-5.433756 2.159176-10.632151 
        5.996574-14.46955l223.019353-223.029586c7.992021-7.992021 20.957311-7.992021 28.949332 0 
        7.992021 8.002254 7.992021 20.957311 0 28.949332l-188.073446 188.073446 
        604.753497 0C865.521592 475.058646 874.690416 484.217237 874.690416 495.52477z"></path>
        </svg>
        <span>Back</span>
    </a>

    <h1>Your Profile</h1>

    <div class="profile-pic-wrapper">
        <?php
        $profilePicturePath = (!empty($profilePicture) && file_exists($profilePicture)) ? htmlspecialchars($profilePicture) : 'uploads/default-profile.png';
        ?>
        <img src="<?php echo $profilePicturePath; ?>" alt="Profile Picture" class="profile-pic" id="profilePic">
        <form action="profile.php" method="POST" enctype="multipart/form-data" id="picForm">
            <input type="file" name="profile_picture" id="profileInput" accept="image/*" onchange="document.getElementById('picForm').submit();">
            <button type="button" class="upload-btn" onclick="document.getElementById('profileInput').click();">✎</button>
        </form>
    </div>

    <div class="profile-details">
        <div class="detail-row">
            <div class="label">Username:</div>
            <div class="value"><?php echo htmlspecialchars($username); ?></div>
        </div>
        <div class="detail-row">
            <div class="label">Email:</div>
            <div class="value"><?php echo htmlspecialchars($email); ?></div>
        </div>
        <div class="detail-row">
            <div class="label">Password:</div>
            <div class="value"><?php echo str_repeat('*', $passwordLength); ?></div>
        </div>
    </div>

    <a href="change_password.php" class="btn primary">Change Password</a>
    <a href="logout.php" class="btn danger">Logout</a>
</div>

</body>
</html>
