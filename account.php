<?php
session_start();

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['userEmail'])) {
    if (isset($_COOKIE['userEmail'])) {
        $_SESSION['userEmail'] = $_COOKIE['userEmail'];
    } else {
        header("Location: login.php");
        exit();
    }
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

$userSql = "SELECT userId, userFname, userLname, userEmail, userPhone, userAddress, userPrefStatus, userPassword FROM users WHERE userEmail = ?";
$userStmt = $conn->prepare($userSql);

if ($userStmt === false) {
    die('Error preparing the user query: ' . $conn->error);
}

$userStmt->bind_param("s", $userEmail);
$userStmt->execute();
$userResult = $userStmt->get_result();

if ($userResult->num_rows > 0) {
    $userData = $userResult->fetch_assoc();
    $userId = $userData['userId']; 
} else {
    die("User not found.");
}

if ($userData['userPrefStatus'] === NULL) {
    $userData['userPrefStatus'] = 'No preference set';
}

$orderSql = "SELECT orderID, orderDate, orderTotPay, orderMethodPay, orderStatus, deliveryAddress, products 
             FROM `order` 
             WHERE userId = ?"; 
$orderStmt = $conn->prepare($orderSql);

if ($orderStmt === false) {
    die('Error preparing the order query: ' . $conn->error);
}

$orderStmt->bind_param("i", $userId);  
$orderStmt->execute();
$orderResult = $orderStmt->get_result();


if (isset($_POST['edit-profile'])) {
    $userFname = $_POST['userFname'];
    $userLname = $_POST['userLname'];
    $userPhone = $_POST['userPhone'];
    $userAddress = $_POST['userAddress'];
    $userPrefStatus = $_POST['userPrefStatus'];
    $userPassword = $_POST['userPassword'] ?? null;

    if (!empty($userPassword)) {
        $userPassword = password_hash($userPassword, PASSWORD_DEFAULT);
        $updateSql = "UPDATE users SET userFname = ?, userLname = ?, userPhone = ?, userAddress = ?, userPrefStatus = ?, userPassword = ? WHERE userId = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ssssssi", $userFname, $userLname, $userPhone, $userAddress, $userPrefStatus, $userPassword, $userId);
    } else {
        $updateSql = "UPDATE users SET userFname = ?, userLname = ?, userPhone = ?, userAddress = ?, userPrefStatus = ? WHERE userId = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("sssssi", $userFname, $userLname, $userPhone, $userAddress, $userPrefStatus, $userId);
    }

    if ($updateStmt === false) {
        die('Error preparing the update query: ' . $conn->error);
    }

    $updateStmt->execute();
    $updateStmt->close();

    header("Location: account.php");
    exit();

    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0"); 
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
    <div class="sidebar">
        <h2>Account</h2>
        <ul>
            <li><button id="profile-btn">Profile</button></li>
            <li><button id="order-history-btn">Order History</button></li>
        </ul>
    </div>

    <div class="main-content">
      
        <div id="profile-section" class="account-section">
            <h3>User Profile</h3>
            <p>Name: <span id="user-name"><?php echo $userData['userFname'] . ' ' . $userData['userLname']; ?></span></p>
            <p>Email: <span id="user-email"><?php echo $userData['userEmail']; ?></span></p>
            <p>Phone: <span id="user-phone"><?php echo $userData['userPhone']; ?></span></p>
            <p>Address: <span id="user-address"><?php echo $userData['userAddress']; ?></span></p>
            <p>Preferred Scent: <span id="user-preferences"><?php echo $userData['userPrefStatus']; ?></span></p>
            <p>Password: <span id="user-password">******</span></p> 
            <button id="edit-profile-btn">Edit Profile</button>
        </div>

        <div id="order-history-section" class="account-section" style="display: none;">
            <h3>Order History</h3>
            <?php if ($orderResult->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Order Date</th>
                            <th>Total Payment</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Delivery Address</th>
                            <th>Products</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($order = $orderResult->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $order['orderID']; ?></td>
                                <td><?php echo $order['orderDate']; ?></td>
                                <td><?php echo $order['orderTotPay']; ?></td>
                                <td><?php echo $order['orderMethodPay']; ?></td>
                                <td><?php echo $order['orderStatus']; ?></td>
                                <td><?php echo $order['deliveryAddress']; ?></td>
                                <td>
                                    <?php
                                    
                                    $productsSerialized = $order['products'];
                                    $products = unserialize($productsSerialized);

                                    if (is_array($products) && !empty($products)) {
                                        echo '<ul>';
                                        foreach ($products as $product) {
                                            echo '<li>' . htmlspecialchars($product['prodName']) . ' - ' . htmlspecialchars($product['prodPrice']) . ' (x' . htmlspecialchars($product['quantity']) . ')</li>';
                                        }
                                        echo '</ul>';
                                    } else {
                                        echo 'No products found for this order.';
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No orders found.</p>
            <?php endif; ?>
        </div>

       
        <div id="edit-profile-section" class="account-section" style="display: none;">
            <h3>Edit Profile</h3>
            <form id="edit-profile-form" method="POST" action="account.php">
                <input type="hidden" name="edit-profile" value="1">
                <label for="edit-name">Name</label>
                <input type="text" id="edit-name" name="userFname" value="<?php echo $userData['userFname']; ?>" required>
                <label for="edit-lname">Last Name</label>
                <input type="text" id="edit-lname" name="userLname" value="<?php echo $userData['userLname']; ?>" required>
                <label for="edit-phone">Phone</label>
                <input type="text" id="edit-phone" name="userPhone" value="<?php echo $userData['userPhone']; ?>" required>
                <label for="edit-address">Address</label>
                <input type="text" id="edit-address" name="userAddress" value="<?php echo $userData['userAddress']; ?>" required>
                <label for="edit-preferences">Preferred Scent</label>
                <input type="text" id="edit-preferences" name="userPrefStatus" value="<?php echo $userData['userPrefStatus']; ?>" required>
                <label for="edit-password">Password</label>
                <input type="password" id="edit-password" name="userPassword" value="" placeholder="Leave blank to keep current password">
                <button type="submit">Save</button>
            </form>
        </div>
    </div>
</div>

<script src="js/account.js"></script>
<script>
   
    document.getElementById('profile-btn').addEventListener('click', function() {
        document.getElementById('profile-section').style.display = 'block';
        document.getElementById('order-history-section').style.display = 'none';
        document.getElementById('edit-profile-section').style.display = 'none';
    });

    document.getElementById('order-history-btn').addEventListener('click', function() {
        document.getElementById('profile-section').style.display = 'none';
        document.getElementById('order-history-section').style.display = 'block';
        document.getElementById('edit-profile-section').style.display = 'none';
    });

    document.getElementById('edit-profile-btn').addEventListener('click', function() {
        document.getElementById('profile-section').style.display = 'none';
        document.getElementById('order-history-section').style.display = 'none';
        document.getElementById('edit-profile-section').style.display = 'block';
    });
</script>

<footer id="footer">
    <p>&copy; 2025 Velvet Vials. All rights reserved.</p>
</footer>

</body>
</html>
