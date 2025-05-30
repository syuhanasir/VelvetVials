<?php

session_start();

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

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


function saveCartToCookies($cart) {
    $expiryDate = new DateTime();
    $expiryDate->modify('+1 year');
    setcookie('cart', json_encode($cart), $expiryDate->getTimestamp(), '/');
}


$cart = getCartFromCookies();

if (count($cart) === 0) {
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "velvetvials";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['addToCart'])) {
    $prodID = $_POST['prodID'];
    $productFound = false;

    foreach ($cart as $key => $item) {
        if ($item['prodName'] == 'Example Product') {
            unset($cart[$key]);
        }
    }

    $cart = array_values($cart);

    foreach ($cart as &$item) {
        if ($item['prodID'] === $prodID) {

            $item['quantity'] += 1;
            $productFound = true;
            break;
        }
    }

    if (!$productFound) {
        $sql = "SELECT * FROM product WHERE prodID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $prodID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();
            $cart[] = [
                'prodID' => $prodID,
                'prodName' => $product['prodName'],
                'prodPrice' => $product['prodPrice'],
                'quantity' => 1
            ];
        }
    }

    saveCartToCookies($cart);

    header("Location: cartPage.php");
    exit;
}

if (isset($_POST['removeFromCart'])) {
    $prodID = $_POST['prodID'];

    foreach ($cart as $key => $item) {
        if ($item['prodID'] === $prodID) {
            unset($cart[$key]);
            break;
        }
    }

    $cart = array_values($cart);
    saveCartToCookies($cart);

    header("Location: cartPage.php");
    exit;
}

if (isset($_POST['updateQuantity'])) {
    $prodID = $_POST['prodID'];
    $newQuantity = $_POST['quantity'];

    $sql = "SELECT prodStock FROM product WHERE prodID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $prodID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $availableStock = $product['prodStock'];

        if ($newQuantity > $availableStock) {
             echo "<script>
             alert('Sorry, you can only buy a maximum of $availableStock for product $prodName');
             window.history.back();
         </script>";
         exit;
     }
 }


 foreach ($cart as &$item) {
     if ($item['prodID'] === $prodID) {
         $item['quantity'] = $newQuantity;
         break;
     }
 }

 saveCartToCookies($cart);

 header("Location: cartPage.php");
 exit;
}

$productDetails = [];
foreach ($cart as $item) {
    $sql = "SELECT * FROM product WHERE prodID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $item['prodID']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $productDetails[] = $result->fetch_assoc();
    }
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #007BFF;
            color: white;
        }

        table tr:last-child td {
            border-bottom: none;
        }

        .btn {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px 15px;
            text-align: center;
            font-size: 16px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        form {
            margin: 0;
        }

        input[type="number"] {
            width: 60px;
            padding: 5px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        p {
            text-align: center;
            font-size: 18px;
            color: #555;
            font-size: 0.9rem;
        }

    </style>
    <script>
        function validateQuantity(prodID, availableStock) {
            const quantityInput = document.querySelector(`#quantity-${prodID}`);
            const newQuantity = parseInt(quantityInput.value, 10);

            if (newQuantity > availableStock) {
                alert(`Sorry, you can only buy a maximum of ${availableStock} for this product.`);
                quantityInput.value = availableStock;
                return false;
            }
            return true;
        }
    </script>
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

<h1>Your Cart</h1>

<?php if (count($cart) > 0): ?>
    <table>
        <tr>
            <th>Product Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Actions</th>
        </tr>

        <?php
        $totalPrice = 0;
        foreach ($productDetails as $index => $product) {
        
            $cartItem = $cart[$index];
            $totalPrice += $product['prodPrice'] * $cartItem['quantity'];
            echo "<tr>
                    <td>{$product['prodName']}</td>
                    <td>$" . number_format($product['prodPrice'], 2) . "</td>
                    <td>
                        <form method='post' action='cartPage.php'>
                            <input type='hidden' name='prodID' value='{$product['prodID']}'>
                            <input type='number' name='quantity' value='{$cartItem['quantity']}' min='1' required>
                            <button type='submit' name='updateQuantity'>Update Quantity</button>
                        </form>
                    </td>
                    <td>$" . number_format($product['prodPrice'] * $cartItem['quantity'], 2) . "</td>
                    <td>
                        <!-- Remove Item -->
                        <form method='post' action='cartPage.php' style='display:inline;'>
                            <input type='hidden' name='prodID' value='{$product['prodID']}'>
                            <button type='submit' name='removeFromCart'>Remove</button>
                        </form>
                    </td>
                  </tr>";
        }
        ?>
    </table>

    <p>Total Price: $<?= number_format($totalPrice, 2) ?></p>
    <br>
    <a href="checkout.php" class="btn">Proceed to Checkout</a>

<?php else: ?>
    <p>Your cart is empty.</p>
<?php endif; ?>

<a href="shopPublic.php" class="btn">Go to Shop</a>

<footer id="footer">
    <p>&copy; 2025 Velvet Vials. All rights reserved.</p>
</footer>

</body>
</html>
