<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'] ?? '';

    if ($action === "add") {
        $prodID = $_POST['prodID'];
        $prodName = $_POST['prodName'];
        $prodCat = $_POST['prodCat'];
        $prodDesc = $_POST['prodDesc'];
        $prodPrice = $_POST['prodPrice'];
        $prodStock = $_POST['prodStock'];

        $sql = "INSERT INTO product (prodID, prodName, prodCat, prodDesc, prodPrice, prodStock) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssdi", $prodID, $prodName, $prodCat, $prodDesc, $prodPrice, $prodStock);

        if ($stmt->execute()) {
            echo "Product added successfully!";
        } else {
            echo "Error adding product: " . $stmt->error;
        }
        $stmt->close();
    }

    if ($action === "edit") {
        $prodID = $_POST['prodID'];
        $prodName = $_POST['prodName'];
        $prodPrice = $_POST['prodPrice'];
        $prodStock = $_POST['prodStock'];

        $sql = "UPDATE product SET prodName = ?, prodPrice = ?, prodStock = ? WHERE prodID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdss", $prodName, $prodPrice, $prodStock, $prodID);

        if ($stmt->execute()) {
            echo "Product updated successfully!";
        } else {
            echo "Error updating product: " . $stmt->error;
        }
        $stmt->close();
    }

    if ($action === "delete") {
        $prodID = $_POST['prodID'];

        $sql = "DELETE FROM product WHERE prodID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $prodID);

        if ($stmt->execute()) {
            echo "Product deleted successfully!";
        } else {
            echo "Error deleting product: " . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>
