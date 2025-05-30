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
    <title>User Management - Velvet Vials</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
         
           body {
            font-family: Arial, sans-serif;
            background-color: rgb(215, 213, 218);
            margin: 0;
            padding: 0;
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

    <h2>User Management</h2>

    <table>
        <thead>
            <tr>
                <th>User ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Membership</th>
                <th>Requested Membership</th>
                <th>Approve Membership</th>
                <th>Signup Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT * FROM users";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $statusOptions = ($row['userStatus'] === "active") ?
                        "<option value='active' selected>Activate</option>
                         <option value='inactive'>Deactivate</option>" :
                        "<option value='active'>Activate</option>
                         <option value='inactive' selected>Deactivate</option>";
                         
                    $approvalButton = $row['membershipRequest'] ? 
                    "<button onclick=\"approveMembership('{$row['userId']}')\">Approve</button>" : 
                    "No Request";


                    echo "<tr>
                        <td>{$row['userId']}</td>
                        <td>{$row['userFname']}</td>
                        <td>{$row['userLname']}</td>
                        <td>{$row['userEmail']}</td>
                        <td>{$row['userPhone']}</td>
                        <td>{$row['userAddress']}</td>
                        <td>{$row['userMembership']}</td>
                        <td>{$row['membershipRequest']}</td>
                        <td>$approvalButton</td>
                        <td>{$row['signup_date']}</td>
                        <td>{$row['userStatus']}</td>
                        <td>
                            <select onchange=\"toggleStatus('{$row['userId']}', this.value)\">
                                $statusOptions
                            </select>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='10'>No users found.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <script>
        function toggleStatus(userId, newStatus) {
            fetch("cruduser.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `action=toggleStatus&userId=${userId}&userStatus=${newStatus}`
        })
        .then(response => response.text())
        .then(responseText => {
            console.log("Server Response:", responseText);
            alert(responseText);
            location.reload();
    })
    .catch(error => console.error("Error:", error));
}

function approveMembership(userId) {
            fetch("cruduser.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `action=approveMembership&userId=${userId}`
            })
            .then(response => response.text())
            .then(responseText => {
                console.log("Server Response:", responseText);
                alert(responseText);
                location.reload();
            })
            .catch(error => console.error("Error:", error));
        }
    </script>

<footer id="footer">
        <p>&copy; 2025 Velvet Vials. All rights reserved.</p>
    </footer>

</body>
</html>
