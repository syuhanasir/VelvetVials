<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "velvetvials"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE userEmail = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        $userStatus = strtolower(trim($user['userStatus']));
        if ($userStatus !== 'active') {
            echo "Your account is currently " . htmlspecialchars($user['userStatus']) . ". Please contact support.";
            exit();
        }

        if (password_verify($password, $user['userPassword'])) {
           
            $_SESSION['user_logged_in'] = true;
            $_SESSION['userEmail'] = $user['userEmail'];
            setcookie('userEmail', $user['userEmail'], time() + (86400 * 30), "/");

            header("Location: member.php");
            exit();
        } else {
            echo "<p style='color: red;'>Invalid email or password. Please try again.</p>";
        }
    } else {
        echo "<p style='color: red;'>No user found with this email. Please sign up first.</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login</title>
    <link rel="stylesheet" href="css/style.css">
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
        <h2>Customer Login</h2>
        <form action="login.php" method="POST" onsubmit="return validateForm()">
            <label for="email">Email:</label>
            <input type="email" id="Email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="Password" name="password" required>
            
            <p id="error-msg" class="error"></p>
            
            <input type="submit" class="button-style" value="Login">
        </form>

        <p>If you're an admin, <a href="adminLogin.php">Login as Admin</a></p>
    </div>

    <script>
        function validateForm() {
            const email = document.getElementById('Email').value;
            const password = document.getElementById('Password').value;
            const errorMsg = document.getElementById('error-msg');
            
            if (!email || !password) {
                errorMsg.textContent = "Please fill out both fields.";
                return false;
            }

            return true;
        }
    </script>

    <footer id="footer">
        <p>&copy; 2025 Velvet Vials. All rights reserved.</p>
    </footer>

</body>
</html>
