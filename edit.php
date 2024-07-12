<?php
include 'connection.php';

if (isset($_GET['id'])) {
    $productid = $_GET['id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $productname = $_POST['productname'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $target_file = "";

        if (!empty($_FILES["productimage"]["name"])) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["productimage"]["name"]);

            // Ensure the uploads directory exists and is writable
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            if (!move_uploaded_file($_FILES["productimage"]["tmp_name"], $target_file)) {
                echo "Sorry, there was an error uploading your file.";
                $target_file = "";
            }
        }

        if ($target_file) {
            $stmt = $con->prepare("UPDATE products SET productname = ?, description = ?, price = ?, productimage = ? WHERE productid = ?");
            $stmt->bind_param("ssdsi", $productname, $description, $price, $target_file, $productid);
        } else {
            $stmt = $con->prepare("UPDATE products SET productname = ?, description = ?, price = ? WHERE productid = ?");
            $stmt->bind_param("ssdi", $productname, $description, $price, $productid);
        }

        if ($stmt->execute()) {
            echo "Product updated successfully";
            header('Location: index.php'); // Redirect to the homepage
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $stmt = $con->prepare("SELECT productname, description, price, productimage FROM products WHERE productid = ?");
        $stmt->bind_param("i", $productid);
        $stmt->execute();
        $stmt->bind_result($productname, $description, $price, $productimage);
        $stmt->fetch();
        $stmt->close();
    }
} else {
    echo "Invalid product ID.";
    exit();
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
</head>
<body>
    <h2>Edit Product</h2>
    <form method="post" action="" enctype="multipart/form-data">
        Product Name: <input type="text" name="productname" value="<?php echo htmlspecialchars($productname); ?>" required><br>
        Description: <textarea name="description" required><?php echo htmlspecialchars($description); ?></textarea><br>
        Price: <input type="number" name="price" step="0.01" value="<?php echo htmlspecialchars($price); ?>" required><br>
        Product Image: <input type="file" name="productimage"><br>
        <input type="submit" value="Update Product">
    </form>
</body>
</html>
