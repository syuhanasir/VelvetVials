<?php

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "velvetvials"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $userFname = $conn->real_escape_string($_POST['firstName']);
    $userLname = $conn->real_escape_string($_POST['lastName']);
    $userEmail = $conn->real_escape_string($_POST['email']);
    $userPhone = $conn->real_escape_string($_POST['phone']);
    $userAddress = $conn->real_escape_string($_POST['address']);
    $userPassword = $_POST['password'];
    $userMembership = $conn->real_escape_string($_POST['membership']);

    if (!preg_match("/^[a-zA-Z\s]+$/", $userFname)) {
        $error_message .= "First Name must contain only letters.<br>";
    }
    if (!preg_match("/^[a-zA-Z\s]+$/", $userLname)) {
        $error_message .= "Last Name must contain only letters.<br>";
    }
    if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
        $error_message .= "Invalid email format.<br>";
    }
    if (!preg_match("/^[0-9]{10}$/", $userPhone)) {
        $error_message .= "Phone number must be exactly 10 digits.<br>";
    }
    if (strlen($userPassword) < 8 || !preg_match("/[a-z]/", $userPassword) || !preg_match("/[A-Z]/", $userPassword) || !preg_match("/\d/", $userPassword)) {
        $error_message .= "Password must be at least 8 characters long and include an uppercase letter, a lowercase letter, and a number.<br>";
    }
    if ($userMembership == "") {
        $error_message .= "Please select a membership type.<br>";
    }

    if (!empty($error_message)) {
        echo "<script>window.onload = function() { showErrorModal(`" . addslashes($error_message) . "`); }</script>";
    } else {
     
        $email_check_query = "SELECT * FROM users WHERE userEmail = '$userEmail' LIMIT 1";
        $result = $conn->query($email_check_query);
        if ($result->num_rows > 0) {
            echo "<script>window.onload = function() { showErrorModal('Email is already registered. Please use another email or login.'); }</script>";
        } else {
            
            $userPassword = password_hash($userPassword, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (userFname, userLname, userEmail, userPhone, userAddress, userPassword, userMembership) 
                    VALUES ('$userFname', '$userLname', '$userEmail', '$userPhone', '$userAddress', '$userPassword', '$userMembership')";

            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Sign-up successful! Please verify your email.'); window.location.href = 'login.php';</script>";
            } else {
                echo "<script>window.onload = function() { showErrorModal('Error: " . addslashes($conn->error) . "'); }</script>";
            }
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign-Up Page</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/signUpValidation.js" defer></script>
</head>
<body>
<nav class="nav-bar">
    <a id="logo" href="index.php">Velvet Vials</a>
    <div class="nav-bar-links">
        <ul>
            <li><a href="shop.php">Shop</a></li>
            <li><a href="login.php">Log In</a></li>
            <li><a href="signUp.php">Sign Up</a></li>
        </ul>
    </div>
</nav>

<div class="container">
    <h2>Sign-Up Form</h2>
    <form id="signup-form" action="signUp.php" method="POST" onsubmit="return validateForm()">
        <label for="firstName">First Name:</label>
        <input type="text" id="firstName" name="firstName" required>

        <label for="lastName">Last Name:</label>
        <input type="text" id="lastName" name="lastName" required>

        <label for="email">Email:</label>
        <input type="text" id="email" name="email" required>

        <label for="phone">Phone Number:</label>
        <input type="text" id="phone" name="phone" required>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <label for="membership">Select Membership:</label>
        <select id="membership" name="membership" required>
            <option value="">-- Select Membership --</option>
            <option value="Silver">Silver</option>
            <option value="Gold">Gold</option>
            <option value="Platinum">Platinum</option>
        </select>

        <label>
            <input type="checkbox" id="terms" name="terms" required>
            I agree to the <a href="#">Terms and Conditions</a>
        </label>

        <input type="submit" class="button-style" value="Sign Up">
    </form>
</div>

<div id="errorModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <h3>Sign-Up Error</h3>
        <p id="modal-error-message"></p>
    </div>
</div>

<script>
function showErrorModal(message) {
    const modal = document.getElementById('errorModal');
    const errorMessageElement = document.getElementById('modal-error-message');
    errorMessageElement.innerHTML = message;
    modal.style.display = 'block';
}

function closeModal() {
    const modal = document.getElementById('errorModal');
    modal.style.display = 'none';
}
</script>

<footer id="footer">
    <p>&copy; 2025 Velvet Vials. All rights reserved.</p>
</footer>

</body>
</html>
