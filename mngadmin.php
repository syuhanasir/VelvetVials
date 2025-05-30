<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminLogin.php");
    exit();
}

$adminEmail = $_SESSION['adminEmail'] ?? null;

$conn = new mysqli("localhost", "root", "", "velvetvials");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($adminEmail) {
    $query = "SELECT * FROM admins WHERE adminEmail = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $adminEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $admin = $result->fetch_assoc();
    } else {

        echo "Admin not found!";
        exit;
    }
} else {
    echo "Admin not logged in!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Details - Velvet Vials</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
       
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f8f8;
            color: #333;
        }

        h2 {
            text-align: center;
            color: #4A2D54;
            margin-top: 20px;
            font-size: 2rem;
        }

        form {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        form label {
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
            color: #4A2D54;
        }
        form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        form button {
            padding: 10px 20px;
            background-color: #4A2D54;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        form button:hover {
            background-color: #5a3e66;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
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

    <h2>Welcome, <?= htmlspecialchars($admin['adminName']) ?>!</h2>
    <h3 style="text-align:center;color:#4A2D54;">Admin Details</h3>
    <form id="adminForm">
        <input type="hidden" id="adminID" value="<?= $admin['adminID'] ?>">
        Name: <input type="text" id="adminName" value="<?= htmlspecialchars($admin['adminName']) ?>" required>
        Email:<input type="email" id="adminEmail" value="<?= htmlspecialchars($admin['adminEmail']) ?>" required>
        Password:<input type="text" id="adminPassword" value="<?= htmlspecialchars($admin['adminPassword']) ?>" required>
        Phone Number:<input type="text" id="adminPhoneNum" value="<?= htmlspecialchars($admin['adminPhoneNum']) ?>" required>
        <button type="button" onclick="updateAdmin()">Update</button>
    </form>

    <footer id="footer">
        <p>&copy; 2025 Velvet Vials. All rights reserved.</p>
    </footer>

    <script src="js/mngadmin.js"></script>
</body>
</html>