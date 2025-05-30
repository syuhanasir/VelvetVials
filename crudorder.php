<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $orderID = $_POST['orderID'];
    $newStatus = $_POST['newStatus'];

    if (!in_array($newStatus, ['Pending', 'Shipped', 'Cancelled'])) {
        echo "Invalid status.";
        exit;
    }

    $stmt = $conn->prepare("UPDATE `order` SET `orderStatus` = ? WHERE `orderID` = ?");
    $stmt->bind_param("si", $newStatus, $orderID);

    if ($stmt->execute()) {
        echo "Success";
    } else {
        echo "Error updating status: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
