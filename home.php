<?php
include 'connection.php';


$sql = "SELECT productid, productname, description, price, productimage FROM products";
$result = $con->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopNow</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
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
        h1 {
            color: #333;
            margin: 20px 0;
        }
        .products {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 20px;
        }
        .product {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            max-width: 300px;
            padding: 20px;
            text-align: center;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .product:hover {
            transform: translateY(-10px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .product img {
            border-bottom: 1px solid #ddd;
            margin-bottom: 15px;
            max-width: 100%;
            border-radius: 8px 8px 0 0;
        }
        .product h2 {
            color: #333;
            font-size: 20px;
            margin: 15px 0;
        }
        .product p {
            color: #666;
            font-size: 16px;
            margin: 10px 0;
        }
        .product-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }
        .product-buttons a {
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            color: #fff;
            padding: 10px 15px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .product-buttons a:hover {
            background-color: #0056b3;
        }
        .product-buttons a.delete {
            background-color: #dc3545;
        }
        .product-buttons a.delete:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="nav-item"><a href="home.php">Home</a></div>
        <div class="nav-item"><a href="addproducts.php">Add Product</a></div>
    </div>

    <h1>Welcome to ShopNow</h1>

    <div class="products">
        <?php
        if ($result->num_rows > 0) {

            while($row = $result->fetch_assoc()) {
                echo '<div class="product">';
                echo '<img src="' . htmlspecialchars($row["productimage"]) . '" alt="' . htmlspecialchars($row["productname"]) . '">';
                echo '<h2>' . htmlspecialchars($row["productname"]) . '</h2>';
                echo '<p>' . htmlspecialchars($row["description"]) . '</p>';
                echo '<p>Price: $' . htmlspecialchars($row["price"]) . '</p>';
                echo '<div class="product-buttons">';
                echo '<a href="edit.php?id=' . htmlspecialchars($row["productid"]) . '">Edit</a> ';
                echo '<a class="delete" href="delete.php?id=' . htmlspecialchars($row["productid"]) . '">Delete</a>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo "No products found.";
        }
        ?>
    </div>
</body>
</html>

<?php
$con->close();
?>
