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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
          
            height: 100vh;
            margin: 0;
        }
        .form-container {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: absolute;
            transform: translate(-50%,-50%);
            top: 50%;
            left: 50%;
            padding: 30px;
            max-width: 500px;
            width: 100%;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }
        .form-container input[type="text"],
        .form-container input[type="number"],
        .form-container textarea,
        .form-container input[type="file"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-container input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .form-container input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .error-message, .success-message {
            margin: 10px 0;
            padding: 10px;
            border-radius: 4px;
        }
        .error-message {
            color: #dc3545;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }
        .success-message {
            color: #28a745;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
        }
        .navbar {
            background-color: #007bff;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            position: sticky;
            top: 0;
            width: 100%;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px;
            transition: background-color 0.3s;
        }
        .navbar a:hover {
            background-color: #0056b3;
        }
        .navbar .nav-item {
            margin: 0 10px;
        }
    </style>
</head>
<body>
<div class="navbar">
        <div class="nav-item"><a href="home.php">Home</a></div>
        <div class="nav-item"><a href="addproducts.php">Add Product</a></div>
    </div>

    <div class="form-container">
        <h2>Edit Product</h2>
        <?php
        if (isset($error_message)) {
            echo '<div class="error-message">' . htmlspecialchars($error_message) . '</div>';
        }
        if (isset($success_message)) {
            echo '<div class="success-message">' . htmlspecialchars($success_message) . '</div>';
        }
        ?>
        <form method="post" action="" enctype="multipart/form-data">
            <input type="text" name="productname" value="<?php echo htmlspecialchars($productname); ?>" placeholder="Product Name" required><br>
            <textarea name="description" placeholder="Description" required><?php echo htmlspecialchars($description); ?></textarea><br>
            <input type="number" name="price" step="0.01" value="<?php echo htmlspecialchars($price); ?>" placeholder="Price" required><br>
            <input type="file" name="productimage"><br>
            <input type="submit" value="Update Product">
        </form>
    </div>
</body>
</html>
