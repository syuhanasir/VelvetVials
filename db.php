<?php
session_start();

$conn = new mysqli("localhost", "root", "", "velvetvials");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_COOKIE['admin_logged_in'])) {
    setcookie('admin_logged_in', 'true', time() + 3600, "/");
}
?>
