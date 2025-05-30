<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? null;
    $userId = $_POST['userId'] ?? null;
    $userStatus = $_POST['userStatus'] ?? null;
    $membershipRequest = $_POST['membershipRequest'] ?? null;
    $decision = $_POST['approveStat'] ?? null;
    $userId = (int) $userId;

    error_log("Received Action: " . $action);
    error_log("Received userId: " . $userId);
    error_log("Received userStatus: " . $userStatus);
    error_log("Received approveStat: " . $membershipRequest);

    if ($action === "toggleStatus") {
        if ($userId && ($userStatus === "active" || $userStatus === "inactive")) {
            $stmt = $conn->prepare("UPDATE users SET userStatus = ? WHERE userId = ?");
            $stmt->bind_param("si", $userStatus, $userId);

            if ($stmt->execute()) {
                echo "User status updated successfully!";
            } else {
                echo "Error updating status: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Invalid input data!";
        }
    }

    
    if ($action === "approveMembership") {
        if ($userId) {
            $stmt = $conn->prepare("UPDATE users SET approveStat = 'Approved' WHERE userId = ?");
            $stmt->bind_param("i", $userId);

            if ($stmt->execute()) {
                echo "Membership request approved successfully!";
            } else {
                echo "Error approving membership: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Invalid input data!";
        }
    }
}

$conn->close();
?>
