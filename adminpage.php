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

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Velvet Vials</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: rgb(245, 245, 245);
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h2 {
            margin-bottom: 1rem;
            color: #333;
        }

        .card-row {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .card-metric {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            padding: 20px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-metric:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card-metric i {
            font-size: 3rem;
            margin-bottom: 10px;
        }

        .card-metric h5 {
            margin-bottom: 0.5rem;
            font-size: 1.5rem;
            color: #333;
        }

        .card-metric .card-text {
            font-size: 1.2rem;
            color: #666;
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

    <div class="container">
        <h2>Dashboard</h2>
        <div class="card-row">
            <div class="card-metric" style="background-color: #f8d7da;">
                <i class="bi bi-cart-check text-danger"></i>
                <h5>Total Orders</h5>
                <p class="card-text" id="totalOrders">Loading...</p>
            </div>
            <div class="card-metric" style="background-color: #d1e7dd;">
                <i class="bi bi-people text-success"></i>
                <h5>Total Users</h5>
                <p class="card-text" id="totalUsers">Loading...</p>
            </div>
            <div class="card-metric" style="background-color: #cff4fc;">
                <i class="bi bi-box text-info"></i>
                <h5>Total Products</h5>
                <p class="card-text" id="totalProducts">Loading...</p>
            </div>
        </div>
    </div>

    <footer id="footer">
        <p>&copy; 2025 Velvet Vials. All rights reserved.</p>
    </footer>

    <script src="js/adminpage.js"></script>
</body>
</html>