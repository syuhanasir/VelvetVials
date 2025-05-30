<?php
include 'db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['adminID'], $data['adminName'], $data['adminEmail'], $data['adminPassword'], $data['adminPhoneNum'])) {
    $adminID = intval($data['adminID']);
    $adminName = trim($data['adminName']);
    $adminEmail = trim($data['adminEmail']);
    $adminPassword = trim($data['adminPassword']);
    $adminPhoneNum = trim($data['adminPhoneNum']);

    $stmt = $conn->prepare("UPDATE admins SET adminName = ?, adminEmail = ?, adminPassword = ?, adminPhoneNum = ? WHERE adminID = ?");
    if ($stmt === false) {
        echo json_encode(['success' => false, 'error' => 'SQL prepare failed: ' . $conn->error]);
        exit;
    }
    $stmt->bind_param("ssssi", $adminName, $adminEmail, $adminPassword, $adminPhoneNum, $adminID);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Execute failed: ' . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid input data']);
}

$conn->close();
?>
