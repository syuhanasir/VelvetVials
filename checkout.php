<?php
ob_start(); 
session_start();



function getCartFromCookies() {
    if (isset($_COOKIE['cart'])) {
        $cart = json_decode($_COOKIE['cart'], true); 

        foreach ($cart as $key => $item) {
            if ($item['prodName'] == 'Example Product') {
                unset($cart[$key]);
            }
        }

        $cart = array_values($cart); 

        return $cart;
    }
    return [];
}

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "velvetvials"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$today = new DateTime(); 

$newYearExpiry = new DateTime('2025-01-31'); 
$valentineExpiry = new DateTime('2025-02-14'); 
$flashSaleStart = new DateTime('2025-01-04');
$flashSaleExpiry = new DateTime('2025-01-18');

if (!isset($_COOKIE['userEmail'])) {
    header("Location: login.php"); 
    exit;
}

$userEmail = $_COOKIE['userEmail'];
$sql = "SELECT userID, loyaltyPoint, loyaltyRedeem, userMembership FROM users WHERE userEmail = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die('Error preparing the query: ' . $conn->error);
}

$stmt->bind_param("s", $userEmail);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("User not found.");
}

$row = $result->fetch_assoc();
$userID = $row['userID'];
$loyaltyPoints = $row['loyaltyPoint']; 
$loyaltyRedeem = $row['loyaltyRedeem'];
$membership = $row['userMembership'];

$cart = getCartFromCookies();
if (count($cart) === 0) {
    header("Location: shopPublic.php");
    exit;
}

foreach ($cart as $item) {
    $prodID = $item['prodID'];
    $sql = "SELECT prodStock FROM product WHERE prodID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $prodID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $availableStock = $product['prodStock'];

        if ($item['quantity'] > $availableStock) {
            echo "<script>alert('Sorry, you can only buy a maximum of $availableStock for product {$item['prodName']}');</script>";
            header("Location: cartPage.php");
            ob_end_flush();
            exit;
        }
    }
}

$totalPrice = 0;
foreach ($cart as $item) {
    $totalPrice += $item['prodPrice'] * $item['quantity'];
}


$discount = 0;
if ($membership == 'Silver') {
    $discount = 0.05; 
} elseif ($membership == 'Gold') {
    $discount = 0.15;
} elseif ($membership == 'Platinum') {
    $discount = 0.20;
}


$shippingFee = 0;
if ($membership == 'Silver') {
    $shippingFee = 4;
}

$voucherDiscount = 0; 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderMethodPay = $_POST['paymentMethod']; 
    $deliveryAddress = $_POST['deliveryAddress'];

    if ($deliveryAddress === 'newAddress') {
        $newAddress = $_POST['newAddress'];
        $deliveryAddress = $newAddress;
    } else {
        $sql = "SELECT userAddress FROM users WHERE userEmail = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die('Error preparing the query: ' . $conn->error);
        }
        $stmt->bind_param("s", $userEmail);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $deliveryAddress = $row['userAddress'];
    }

    $pointsToRedeem = 0;
    if (isset($_POST['redeemPoints']) && $loyaltyPoints >= 50) {
        $pointsToRedeem = min($loyaltyPoints, $totalPrice / 0.10);
        $loyaltyPoints -= $pointsToRedeem; 
        $loyaltyRedeem += $pointsToRedeem; 
    }


if (isset($_POST['voucherCode'])) {
    $voucherCode = strtoupper(trim($_POST['voucherCode']));
    $validVoucher = false; 

     if (isset($_POST['voucherCode'])) {
        $voucherCode = strtoupper(trim($_POST['voucherCode']));
        $validVoucher = false; 

        if ($voucherCode === 'NY10' && $today <= $newYearExpiry) {
            $voucherDiscount = 0.10;
            $validVoucher = true;
        } elseif ($voucherCode === 'VL25' && $today <= $valentineExpiry) {
            $voucherDiscount = 0.25;
            $validVoucher = true;
        } elseif ($voucherCode === 'FS15' && 
                  $flashSaleStart <= $today && 
                  $today <= $flashSaleExpiry) {
            $voucherDiscount = 0.15;
            $validVoucher = true;
        }
    
    if (!$validVoucher) {
        echo "<p>Invalid or expired voucher code. No discount applied.</p>";
        $voucherDiscount = 0;
    }
}
}


$totalPrice = $totalPrice * (1 - $discount);
$totalPrice -= ($pointsToRedeem * 0.10);
$totalPrice *= (1 - $voucherDiscount);
$totalPrice += $shippingFee;

$totalPrice = round($totalPrice, 2);

    $products = serialize($cart);

    $sql = "INSERT INTO `order` (userId, orderTotPay, orderMethodPay, deliveryAddress, products)
            VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die('Error preparing the insert query: ' . $conn->error);
    }

    $stmt->bind_param("idsss", $userID, $totalPrice, $orderMethodPay, $deliveryAddress, $products);
    $stmt->execute();

    
    $orderID = $stmt->insert_id;

    foreach ($cart as $item) {
        $prodID = $item['prodID'];
        $quantity = $item['quantity'];

        $sql = "UPDATE product SET prodStock = prodStock - ? WHERE prodID = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die('Error preparing stock deduction query: ' . $conn->error);
        }
        $stmt->bind_param("is", $quantity, $prodID);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            echo "<script>alert('Failed to update stock for product ID: $prodID');</script>";
        }
    }

    $newLoyaltyPoints = $loyaltyPoints + round($totalPrice);
    $sql = "UPDATE users SET loyaltyPoint = ?, loyaltyRedeem = ? WHERE userID = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('Error preparing loyalty point update query: ' . $conn->error);
    }
    $stmt->bind_param("dii", $newLoyaltyPoints, $loyaltyRedeem, $userID);
    $stmt->execute();

    setcookie('cart', '', time() - 3600, '/');
    
    header("Location: receipt.php?orderID=" . $orderID);
    ob_end_flush();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        form {
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
        }

        h3 {
            margin-top: 20px;
            color: #6c63ff;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        p {
            margin: 10px 0;
        }

        select, input[type="text"], button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            background: #6c63ff;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            border: none;
        }

        button:hover {
            background: #4a47e0;
        }

        form {
                padding: 15px;
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
                <a href="cartPage.php">Your Cart</a>
            </div>
        </nav>


<h2>Checkout</h2>

<form action="checkout.php" method="POST">

    <h3>Cart Items</h3>
    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($cart as $item): ?>
            <tr>
                <td><?php echo $item['prodName']; ?></td>
                <td>RM<?php echo number_format($item['prodPrice'], 2); ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td>RM<?php echo number_format($item['prodPrice'] * $item['quantity'], 2); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        </table>

        <h3>Delivery Address</h3>
    <select name="deliveryAddress" required>
        <option value="existingAddress">Use my existing address</option>
        <option value="newAddress">Use a new address</option>
    </select>

    <input type="text" name="newAddress" placeholder="Enter new delivery address">

    <h3>Payment Method</h3>
    <select name="paymentMethod" required>
        <option value="Credit Card">Credit Card</option>
        <option value="Paypal">PayPal</option>
        <option value="Online Banking">Online Banking</option>
    </select>

    <h3>Voucher Code</h3>
    <p>Available vouchers:</p>
    <ul>
        <li>NY10: 10% off (Valid until <?php echo $newYearExpiry->format('F j, Y'); ?>)</li>
        <li>VL25: 25% off (Valid until <?php echo $valentineExpiry->format('F j, Y'); ?>)</li>
        <li>FS15: 15% off (Valid from <?php echo $flashSaleStart->format('F j, Y'); ?> to <?php echo $flashSaleExpiry->format('F j, Y'); ?>)</li>
    </ul>
    <input type="text" name="voucherCode" placeholder="Enter voucher code">
    
    
    <h3>Total Price of Items: RM<?php echo number_format($totalPrice, 2); ?></h3>
    <p>Shipping Fee: + RM<?php echo number_format($shippingFee, 2); ?></p>
    <P>Membership Discount: - RM<?php echo number_format($totalPrice * $discount,2); ?></P>

    <h3>Loyalty Points</h3>
    <p>You have <?php echo $loyaltyPoints; ?> loyalty points.</p>
    <?php if ($loyaltyPoints >= 50): ?>
        <input type="checkbox" name="redeemPoints" value="1"> Redeem points (up to RM<?php echo number_format($loyaltyPoints * 0.10, 2); ?> discount)
    <?php else: ?>
        <p>You need at least 50 points to redeem.</p>
    <?php endif; ?>

    <button type="submit">Complete Purchase</button>
</form>
<footer id="footer">
        <p>&copy; 2025 Velvet Vials. All rights reserved.</p>
    </footer>
</body>