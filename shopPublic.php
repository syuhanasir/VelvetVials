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

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';


$sql = "SELECT * FROM product WHERE 1"; 
if ($searchQuery != '') {
    $sql .= " AND prodName LIKE '%$searchQuery%'"; 
}
if ($categoryFilter != '') {
    $sql .= " AND prodCat = '$categoryFilter'";
}
$result = $conn->query($sql);

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            display: flex;
            gap: 20px;
            padding: 20px;
        }
        .categories {
            flex: 1;
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 5px;
        }
        .categories h2 {
            margin-bottom: 10px;
        }
        .categories ul {
            list-style: none;
            padding: 0;
        }
        .categories li {
            margin: 5px 0;
        }
        .categories a {
            text-decoration: none;
            color: #333;
        }
        .categories a:hover {
            text-decoration: underline;
        }
        .product-listing {
            flex: 3;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
        .product-item {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .product-item img {
            max-width: 100%;
            height: auto;
            border-bottom: 1px solid #ddd;
            margin-bottom: 10px;
        }
        .product-item h3 {
            font-size: 18px;
            margin: 10px 0;
        }
        .product-item p {
            font-size: 16px;
            color: #555;
        }
        .product-item a {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 15px;
            background-color: #007BFF;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
        .product-item a:hover {
            background-color: #0056b3;
        }
        footer {
            text-align: center;
            padding: 10px;
            background-color: #333;
            color: #fff;
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

<h1>Our Products</h1>

<div class="container">
    
    <div class="categories">
        <h2>Categories</h2>
        <ul>
            <li><a href="shopPublic.php?category=perfume">Perfume</a></li>
            <li><a href="shopPublic.php?category=scented candle">Scented Candle</a></li>
            <li><a href="shopPublic.php?category=diffuser">Diffuser</a></li>
            <li><a href="shopPublic.php?category=aromatherapy">Aromatherapy</a></li>
            <li><a href="shopPublic.php?category=room spray">Room Sprays</a></li>
        </ul>
    </div>

   
    <div class="product-listing">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="product-item">
                    <img src="<?= $row['prodImage'] ?>" alt="<?= $row['prodName'] ?>">
                    <h3><?= $row['prodName'] ?></h3>
                    <p>Price: $<?= $row['prodPrice'] ?></p>
                    <a href="productDetail.php?id=<?= $row['prodID'] ?>">View Details</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No products found for this category or search.</p>
        <?php endif; ?>
    </div>
</div>

<footer id="footer">
            <p>&copy; 2025 Velvet Vials. All rights reserved.</p>
        </footer>
</body>
</html>
