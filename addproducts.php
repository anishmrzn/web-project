<?php
include 'connection.php'; // Ensure this file sets up the $con variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productname = $_POST['productname'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["productimage"]["name"]);

    // Ensure the uploads directory exists and is writable
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    if (move_uploaded_file($_FILES["productimage"]["tmp_name"], $target_file)) {
        $stmt = $con->prepare("INSERT INTO products (productname, description, price, productimage) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $productname, $description, $price, $target_file);

        if ($stmt->execute()) {
            echo "New product added successfully";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

$con->close();
?>


<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
</head>
<body>
    <h2>Add Product</h2>
    <form method="post" action="addproducts.php" enctype="multipart/form-data">
        Product Name: <input type="text" name="productname" required><br>
        Description: <textarea name="description" required></textarea><br>
        Price: <input type="number" name="price" step="0.01" required><br>
        Product Image: <input type="file" name="productimage" required><br>
        <input type="submit" value="Add Product">
    </form>
</body>
</html>
