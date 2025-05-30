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
    <title>Product Management - Velvet Vials</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
    
        body {
            font-family: Arial, sans-serif;
            background: rgb(215, 213, 218);
            margin: 0;
            padding: 0;
        }

        .add-btn {
            float: right;
            margin: 2rem;
            padding: 10px 20px;
            font-size: 1rem;
            color: white;
            background-color: #4A2D54;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .add-btn:hover {
            background-color: rgb(164, 107, 225);
        }

        .popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .popup-content {
            background-color: white;
            padding: 2rem;
            border-radius: 10px;
            width: 400px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .popup-content h3 {
            margin-top: 0;
            color: #4A2D54;
        }

        .popup-content input, .popup-content button {
            width: 100%;
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .popup-content button {
            background-color: #4A2D54;
            color: white;
            cursor: pointer;
        }

        .popup-content button:hover {
            background-color: rgb(164, 107, 225);
        }

        .close-btn {
            background-color: #ff4d4d;
            margin-top: 0;
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

    <h2>Product Management</h2>
    <button class="add-btn" onclick="openPopup()">+ Add New Product</button>

    <table>
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Description</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="productTable">
            <?php
            $query = "SELECT * FROM product";
            $result = $conn->query($query);
            while ($row = $result->fetch_assoc()) {
                echo "<tr id='row-".$row['prodID']."'>
                        <td>".$row['prodID']."</td>
                        <td>".$row['prodName']."</td>
                        <td>".$row['prodCat']."</td>
                        <td>".$row['prodDesc']."</td>
                        <td>".$row['prodPrice']."</td>
                        <td>".$row['prodStock']."</td>
                        <td>
                            <button class='btn-edit' onclick='editProduct(\"".$row['prodID']."\")'>Edit</button>
                            <button class='btn-delete' onclick='deleteProduct(\"".$row['prodID']."\")'>Delete</button>
                        </td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
    
    <div class="popup" id="addProductPopup">
        <div class="popup-content">
            <h3>Add New Product</h3>
            <form id="addProductForm">
                <input type="text" id="prodID" placeholder="Product ID" required>
                <input type="text" id="prodName" placeholder="Product Name" required>
                <input type="text" id="prodCat" placeholder="Category" required>
                <input type="text" id="prodDesc" placeholder="Description" required>
                <input type="number" id="prodPrice" placeholder="Price" required>
                <input type="number" id="prodStock" placeholder="Stock" required>
                <button type="submit">Add Product</button>
                <button type="button" class="close-btn" onclick="closePopup()">Close</button>
            </form>
        </div>
    </div>

    <footer id="footer">
        <p>&copy; 2025 Velvet Vials. All rights reserved.</p>
    </footer>

    <script src="js/mngprod.js"></script>
</body>
</html>
