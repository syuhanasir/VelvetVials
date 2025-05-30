<?php

function getCartFromCookies() {
    if (isset($_COOKIE['cart'])) {
        return json_decode($_COOKIE['cart'], true);
    }
    return [];
}

function saveCartToCookies($cart) {
    $expiryDate = new DateTime();
    $expiryDate->modify('+1 year'); 
    setcookie('cart', json_encode($cart), $expiryDate->getTimestamp(), '/');
}

$cart = getCartFromCookies();

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "velvetvials"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$productID = isset($_GET['id']) ? $_GET['id'] : ''; 
if (empty($productID)) {
    die("Invalid product ID");
}

$sql = "SELECT * FROM product WHERE prodID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $productID); 
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    die("Product not found");
}

$conn->close();

header("Cache-Control: no-cache, must-revalidate"); 
header("Pragma: no-cache"); 
header("Expires: 0"); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
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

        .product-details {
            max-width: 800px;
            margin: 30px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .product-details h3 {
            margin-bottom: 20px;
            color: #007BFF;
        }

        .product-details p {
            font-size: 16px;
            margin: 10px 0;
            color: #555;
        }

        .product-details p strong {
            color: #333;
        }

        .product-details button {
            display: block;
            width: 100%;
            padding: 10px;
            font-size: 16px;
            color: white;
            background-color: #007BFF;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            text-align: center;
        }

        .product-details button:hover {
            background-color: #0056b3;
        }

        footer {
            text-align: center;
            padding: 10px;
            background-color: #333;
            color: white;
            margin-top: 20px;
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

<h1>Product Details</h1>

<div class="product-details">
    <h3><?= htmlspecialchars($product['prodName']) ?></h3>
    <p>Price: $<?= htmlspecialchars($product['prodPrice']) ?></p>
    <p>Category: <?= htmlspecialchars($product['prodCat']) ?></p>
    <p>Description: <?= htmlspecialchars($product['prodDesc']) ?></p>

    <button id="add-to-cart" data-id="<?= htmlspecialchars($product['prodID']) ?>" data-name="<?= htmlspecialchars($product['prodName']) ?>" data-price="<?= $product['prodPrice'] ?>">Add to Cart</button>
</div>

<script>

document.getElementById('add-to-cart').addEventListener('click', function() {
    let productID = this.getAttribute('data-id');
    let productName = this.getAttribute('data-name');
    let productPrice = this.getAttribute('data-price');

    let cart = getCartFromCookies(); 
    
    let productExists = cart.find(item => item.prodID === productID);
    if (productExists) {
        productExists.quantity++;
    } else {
        cart.push({ prodID: productID, prodName: productName, prodPrice: productPrice, quantity: 1 });
    }

    saveCartToCookies(cart);

    updateCartCount();

    window.location.href = 'cartPage.php';
});

function getCartFromCookies() {
    if (document.cookie.indexOf('cart=') != -1) {
        let cartCookie = document.cookie.split('; ').find(row => row.startsWith('cart=')).split('=')[1];
        return JSON.parse(decodeURIComponent(cartCookie));
    }
    return [];
}

function saveCartToCookies(cart) {
    const expiryDate = new Date();
    expiryDate.setFullYear(expiryDate.getFullYear() + 1); 
    document.cookie = `cart=${encodeURIComponent(JSON.stringify(cart))}; expires=${expiryDate.toUTCString()}; path=/`;
}

function updateCartCount() {
    let cart = getCartFromCookies();
    document.getElementById('cart-count').innerText = cart.length;
}
</script>

<footer id="footer">
            <p>&copy; 2025 Velvet Vials. All rights reserved.</p>
        </footer>
</body>
</html>
