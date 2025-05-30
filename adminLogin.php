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

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['adminEmail']);
    $password = $_POST['adminPassword']; 

    
    $sql = "SELECT * FROM admins WHERE adminEmail = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();


    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['adminPassword'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['adminEmail'] = $user['adminEmail'];

            header("Location: adminpage.php");
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "No admin found with this email.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .error {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
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
        <h2>Admin Login</h2>
        <form id="login-form" method="POST">
            <label for="adminEmail">Email:</label>
            <input type="email" name="adminEmail" id="adminEmail" required>
            
            <label for="adminPassword">Password:</label>
            <input type="password" name="adminPassword" id="adminPassword" required>
            
            <?php if (!empty($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <input type="submit" class="button-style" value="Login">
        </form>
    </div>
    <footer id="footer">
        <p>&copy; 2025 Velvet Vials. All rights reserved.</p>
    </footer>
</body>
</html>
