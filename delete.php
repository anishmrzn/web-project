<?php
include 'connection.php';

if (isset($_GET['id'])) {
    $productid = $_GET['id'];

    $stmt = $con->prepare("DELETE FROM products WHERE productid = ?");
    $stmt->bind_param("i", $productid);

    if ($stmt->execute()) {
        echo "Product deleted successfully";
        header('Location: index.php'); // Redirect to the homepage
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid product ID.";
    exit();
}

$con->close();
?>
