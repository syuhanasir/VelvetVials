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

$orderID = $_GET['orderID']; 
$sql = "SELECT * FROM `order` WHERE orderID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $orderID);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();


$products = unserialize($row['products']); 

$sql = "SELECT loyaltyPoint FROM users WHERE userId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $row['userId']);
$stmt->execute();
$result = $stmt->get_result();
$userRow = $result->fetch_assoc();
$loyaltyPoints = $userRow['loyaltyPoint'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        h2, h3 {
            text-align: center;
            color:rgb(13, 2, 41);
        }

        ul {
            list-style: none;
            padding: 0;
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        ul li {
            background: #f4f4f4;
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
        }

        a {
            text-align: center;
            display: block;
            margin: 10px auto;
            max-width: 400px;
            color: white;
            text-decoration: none;
            font-size: 1.2rem;
            font-weight: bold;
        }

        a {
            text-decoration: none;
            background:rgb(199, 153, 215);
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            text-decoration: underline;
        }


        p {
            color: white;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: bold;
        }

        

    </style>
</head>
<body>
<nav class="nav-bar-2">
        <p>Velvet Vials</p>
</nav>

<h2>Receipt</h2>

<h3>Order ID: <?php echo $orderID; ?></h3>

<h3>Products Ordered:</h3>
<ul>
    <?php
   
    if ($products && is_array($products)) {
        foreach ($products as $item) {
            echo "<li>{$item['prodName']} - $ {$item['prodPrice']} x {$item['quantity']}</li>";
        }
    } else {
        echo "<li>No products available for this order.</li>";
    }
    ?>
</ul>

<h3>Payment Method: <?php echo $row['orderMethodPay']; ?></h3>

<h3>Delivery Address: <?php echo $row['deliveryAddress']; ?></h3>

<h3>Updated Loyalty Points: <?php echo $loyaltyPoints; ?></h3>

<h2>Total Paid: $<?php echo $row['orderTotPay']; ?></h2>


<a href="shopPublic.php">Go to Shop</a>



</body>
</html>
