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
$sql = "SELECT userMembership, membershipRequest, approveStat, membershipExpiryDate FROM users WHERE userEmail = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$result = $stmt->get_result();
$currentMembership = "None";
$membershipRequest = null;
$approveStat = null;
$membershipExpiryDate = null;

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $currentMembership = $row['userMembership'];
    $membershipRequest = $row['membershipRequest'];
    $approveStat = $row['approveStat'];
    $membershipExpiryDate = $row['membershipExpiryDate'];
}

$updateMessage = "";
$receipt = [];

$membershipPrices = [
    'Silver' => 20,
    'Gold' => 30,
    'Platinum' => 40,
];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['request_membership'])) {
    $selectedMembership = $_POST['membership'];

    if (array_key_exists($selectedMembership, $membershipPrices)) {
        $sql = "UPDATE users SET membershipRequest = ?, approveStat = 'Pending' WHERE userEmail = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $selectedMembership, $userEmail);

        if ($stmt->execute()) {
            $updateMessage = "Membership upgrade request to " . $selectedMembership . " has been sent!";
        } else {
            $updateMessage = "Error requesting membership upgrade: " . $stmt->error;
        }
    } else {
        $updateMessage = "Invalid membership selected.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_payment'])) {
    $selectedMembership = $_POST['membership'];

    if ($selectedMembership && array_key_exists($selectedMembership, $membershipPrices)) {
        $expiryDate = date('Y-m-d', strtotime('+1 month'));
        $price = $membershipPrices[$selectedMembership];
        $transactionID = 'TX' . rand(100000, 999999);

        $sql = "UPDATE users SET userMembership = ?, membershipRequest = NULL, approveStat = NULL, membershipExpiryDate = ? WHERE userEmail = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $selectedMembership, $expiryDate, $userEmail);

        if ($stmt->execute()) {
            $updateMessage = "Your membership has been successfully upgraded to " . $selectedMembership . "!";
            $currentMembership = $selectedMembership;
            $membershipRequest = null;
            $approveStat = null;
            $membershipExpiryDate = $expiryDate;

            $receipt = [
                'membership' => $selectedMembership,
                'price' => $price,
                'transactionID' => $transactionID,
                'expiryDate' => $expiryDate,
            ];
        } else {
            $updateMessage = "Error processing payment: " . $stmt->error;
        }
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
    <title>Membership Page</title>
    <link rel="stylesheet" href="css/style.css">
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
                <a href="cartPage.php">Your Cart</a>
            </div>
</nav>

<h2>Membership Page</h2>
<div class="container">
    <div>
        <h3>Current Membership: <?php echo $currentMembership; ?></h3>
        <?php if ($membershipExpiryDate): ?>
            <p>Expires on: <?php echo $membershipExpiryDate; ?></p>
        <?php endif; ?>
    </div>
    <div>
        <h3>Request Membership Upgrade</h3>
        <form method="POST">
            <select name="membership" required>
                <option value="">-- Select Membership --</option>
                <?php foreach ($membershipPrices as $membership => $price): ?>
                    <option value="<?php echo $membership; ?>"><?php echo $membership . " - RM" . $price; ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="request_membership">Submit Request</button>
        </form>
    </div>
    <?php if ($approveStat === 'Approved' && $membershipRequest): ?>
        <div>
            <h3>Complete Your Payment</h3>
            <p>Plan: <?php echo $membershipRequest; ?></p>
            <p>Price: RM<?php echo $membershipPrices[$membershipRequest]; ?></p>
            <form method="POST">
                <input type="hidden" name="membership" value="<?php echo $membershipRequest; ?>">
                <button type="submit" name="confirm_payment">Pay Now</button>
            </form>
        </div>
    <?php endif; ?>
    <?php if (!empty($receipt)): ?>
        <div>
            <h3>Receipt</h3>
            <p>Membership: <?php echo $receipt['membership']; ?></p>
            <p>Price: RM<?php echo $receipt['price']; ?></p>
            <p>Transaction ID: <?php echo $receipt['transactionID']; ?></p>
            <p>Expiry Date: <?php echo $receipt['expiryDate']; ?></p>
        </div>
    <?php endif; ?>
    <p><?php echo $updateMessage; ?></p>
</div>

<footer id="footer">
            <p>&copy; 2025 Velvet Vials. All rights reserved.</p>
</footer>

</body>
</html>
