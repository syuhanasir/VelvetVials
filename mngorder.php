<?php

session_start();

if (!isset($_SESSION['admin_logged_in'] )|| $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminLogin.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "velvetvials");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT * FROM `order`";
$result = $conn->query($query);
$orders = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Velvet Vials</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
    
        body {
            font-family: Arial, sans-serif;
            background-color: rgb(215, 213, 218);
            margin: 0;
            padding: 0;
        }

        .nav-bar {
            background-color: #4A2D54;
            color: white;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-bar a {
            color: white;
            text-decoration: none;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .nav-bar-links ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 15px;
        }

        .nav-bar-links ul li a:hover {
            text-decoration: underline;
        }

        h2 {
            text-align: center;
            color: #4A2D54;
            margin: 2rem 0;
            font-size: 2rem;
        }

        table {
            width: 90%;
            margin: 0 auto;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        table thead {
            background-color: #4A2D54;
            color: white;
        }

        table th {
            background: #4A2D54;
            padding: 15px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #ddd;
        }

        table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
            font-size: 0.95rem;
        }

        table tr:hover {
            background-color: #e9e9f1;
        }

        select {
            padding: 5px 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
        }

        #footer {
            background-color: #4A2D54;
            color: white;
            text-align: center;
            padding: 1rem;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        #footer p {
            margin: 0;
        }
    </style>
</head>
<body>
    <nav class="nav-bar">
        <a id="logo" href="adminpage.php">Velvet Vials</a>
        <div class="nav-bar-links">
            <ul>
                <li><a href="mngadmin.php">Account</a></li>
                <li><a href="mnguser.php">User Management</a></li>
                <li><a href="mngorder.php">Order Management</a></li>
                <li><a href="mngprod.php">Product Management</a></li>
                <li><a href="logout.php">Log Out</a></li>
            </ul>
        </div>
    </nav>
    
    <h2>Order Management</h2>

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User ID</th>
                <th>Order Date</th>
                <th>Total Payment</th>
                <th>Payment Method</th>
                <th>Status</th>
                <th>Delivery Date</th>
                <th>Delivery Address</th>
                <th>Products</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="orderTable">
            <?php foreach ($orders as $order): ?>
                <tr id="row-<?= $order['orderID'] ?>">
                    <td><?= $order['orderID'] ?></td>
                    <td><?= $order['userId'] ?></td>
                    <td><?= $order['orderDate'] ?></td>
                    <td><?= $order['orderTotPay'] ?></td>
                    <td><?= $order['orderMethodPay'] ?></td>
                    <td><?= $order['orderStatus'] ?></td>
                    <td><?= $order['deliveryDate'] ?></td>
                    <td><?= $order['deliveryAddress'] ?></td>
                    <td><?= $order['products'] ?></td>
                    <td>
                         <select onchange="updateStatus(<?= $order['orderID'] ?>, this.value)">
                            <option value="Pending" <?= $order['orderStatus'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="Shipped" <?= $order['orderStatus'] == 'Shipped' ? 'selected' : '' ?>>Shipped</option>
                            <option value="Cancelled" <?= $order['orderStatus'] == 'Cancelled' ? 'selected' : '' ?>>Cancel</option>
                        </select>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script src="js/mngorder.js"></script>

    <footer id="footer">
        <p>&copy; 2025 Velvet Vials. All rights reserved.</p>
    </footer>
</body>
</html>
