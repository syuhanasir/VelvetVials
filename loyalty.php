<?php

session_start(); 

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: login.php"); 
    exit();
}

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "velvetvials"; 
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userEmail = $_SESSION['userEmail'];
$sql = "SELECT loyaltyPoint, loyaltyEarn, loyaltyRedeem, lastCheckIn FROM users WHERE userEmail = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();

$loyaltyPoint = $userData['loyaltyPoint'];
$loyaltyEarn = $userData['loyaltyEarn'];
$loyaltyRedeem = $userData['loyaltyRedeem'];
$lastCheckIn = $userData['lastCheckIn'];

if (isset($_POST['check_in'])) {
    $currentTime = date('Y-m-d H:i:s');
    $today = new DateTime($currentTime);
    $lastCheckInTime = new DateTime($lastCheckIn);
    $interval = $today->diff($lastCheckInTime);

    
    if ($lastCheckIn == null || $interval->h >= 24 || $interval->days >= 1) {
        $loyaltyEarn += 5; 
        $loyaltyPoint += 5; 
        $message = "You earned 5 points for checking in today!";

        $update_sql = "UPDATE users SET loyaltyEarn = ?, loyaltyPoint = ?, lastCheckIn = ? WHERE userEmail = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("iiss", $loyaltyEarn, $loyaltyPoint, $currentTime, $userEmail);
        $stmt->execute();
        
        
        $alertMessage = "success:$message"; 
    } else {
        $remainingTime = 24 - $interval->h;
        $message = "You've already checked in today! Please try again in $remainingTime hours.";

        $alertMessage = "warning:$message"; 
    }
}

if (isset($_POST['redeem'])) {
    if ($loyaltyPoint >= 50) {
        $loyaltyPoint -= 50; 
        $loyaltyRedeem += 50; 
        $message = "You redeemed 50 points for a discount!";

       
        $update_sql = "UPDATE users SET loyaltyPoint = ?, loyaltyRedeem = ? WHERE userEmail = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("iis", $loyaltyPoint, $loyaltyRedeem, $userEmail);
        $stmt->execute();
        
       
        $alertMessage = "success:$message"; 
    } else {
        $message = "You need at least 50 points to redeem a reward.";

       
        $alertMessage = "warning:$message"; 
    }
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Velvet Vials Loyalty Program</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            <?php if (isset($alertMessage)): ?>
                var messageData = "<?php echo $alertMessage; ?>";
                var messageParts = messageData.split(":");
                var messageType = messageParts[0];
                var messageContent = messageParts[1];
                
                if (messageType === "success") {
                    alert(messageContent);
                } else if (messageType === "warning") {
                    alert(messageContent);
                }
            <?php endif; ?>
        });
    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        nav {
            background-color: #333;
            color: white;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        #logo {
            font-size: 24px;
            color: white;
            text-decoration: none;
        }
        .nav-bar-links ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }
        .nav-bar-links ul li {
            margin: 0 15px;
        }
        .nav-bar-links ul li a {
            color: white;
            text-decoration: none;
        }
        .search-bar input {
            padding: 5px;
        }
        #cart {
            color: white;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .hero {
            text-align: center;
            margin-bottom: 40px;
        }
        .hero h1 {
            color: #333;
        }
        .row {
            display: flex;
            justify-content: space-between;
        }
        .points-card {
            background-color: #f7f7f7;
            padding: 20px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            text-align: center;
            flex-basis: 30%;
        }
        .points-card h2 {
            color: #333;
            margin-bottom: 10px;
        }
        .points-card p {
            font-size: 18px;
            color: #555;
        }
        button {
            background-color: #333;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #555;
        }
        footer {
            text-align: center;
            padding: 20px;
            background-color: #333;
            color: white;
        }
        @media (max-width: 768px) {
            .row {
                flex-direction: column;
            }
            .points-card {
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>
<nav class="nav-bar">
    <a id="logo" href="member.php">Velvet Vials</a>
    <div class="nav-bar-links">
        <ul>
            <li><a href="shopPublic.php">Shop</a></li>
            <li><a href="membership.php">Membership</a></li>
            <li><a href="loyalty.php">Loyalty</a></li>
            <li><a href="account.php">Account</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <div id="cart">
        <a href="cartPage.php">Your Cart </a>
    </div>
</nav>

<div class="container">
    <div class="hero">
        <h1>Welcome to Velvet Vials Loyalty Program</h1>
        <p>Unlock exclusive rewards and enjoy benefits tailored just for you!</p>
    </div>

    <div class="row">
        <div class="points-card">
            <h2>Your Points</h2>
            <p><strong><?php echo $loyaltyPoint; ?></strong> Points</p>
        </div>
        <div class="points-card">
            <h2>Total Earned</h2>
            <p><strong><?php echo $loyaltyEarn; ?></strong> Points</p>
        </div>
        <div class="points-card">
            <h2>Total Redeemed</h2>
            <p><strong><?php echo $loyaltyRedeem; ?></strong> Points</p>
        </div>
    </div>

    <div class="row">
        <form method="POST">
            <button type="submit" name="check_in">Daily Check-In</button>
        </form>
    </div>
</div>

<footer id="footer">
    <p>&copy; 2025 Velvet Vials. All rights reserved.</p>
</footer>
</body>
</html>
