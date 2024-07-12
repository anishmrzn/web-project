<?php
include 'connection.php';

// Fetch products from the database
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
        .product {
            border: 1px solid #ccc;
            padding: 10px;
            margin: 10px;
            text-align: center;
        }
        .product img {
            max-width: 100%;
            height: auto;
        }
        .product-buttons {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>Welcome to ShopNow</h1>

    <div class="products">
        <?php
        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo '<div class="product">';
                echo '<h2>' . htmlspecialchars($row["productname"]) . '</h2>';
                echo '<p>' . htmlspecialchars($row["description"]) . '</p>';
                echo '<p>Price: $' . htmlspecialchars($row["price"]) . '</p>';
                echo '<img src="' . htmlspecialchars($row["productimage"]) . '" alt="' . htmlspecialchars($row["productname"]) . '">';
                echo '<div class="product-buttons">';
                echo '<a href="edit.php?id=' . htmlspecialchars($row["productid"]) . '">Edit</a> ';
                echo '<a href="delete.php?id=' . htmlspecialchars($row["productid"]) . '">Delete</a>';
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
