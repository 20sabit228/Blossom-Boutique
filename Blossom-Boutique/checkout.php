<?php
session_start();
include('connection.php'); 


// Checking for logged in
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header("Location: login.php?error=Please log in first");
    exit;
}

$total_price = 0;
$delivery_charge = 5;
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$products = [];

if (!empty($cart_items)) {
    $product_ids = array_keys($cart_items);

    $escaped_product_ids = array_map(function($id) use ($conn) {
        return "'" . $conn->real_escape_string($id) . "'";
    }, $product_ids);

    // array of product IDs 
    $product_ids_str = implode(",", $escaped_product_ids);

    // product details 
    $sql = "SELECT * FROM products WHERE product_id IN ($product_ids_str)";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $row['quantity'] = $cart_items[$row['product_id']];
        $products[] = $row;
        $total_price += $row['price'] * $row['quantity'];
    }
}

$final_price = $total_price + $delivery_charge;

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Blossom Boutique</title>
    
    <link rel="stylesheet" href="style.css">
    <style>
        .checkout-container {
            display: flex;
            justify-content: space-between;
            padding: 20px;
        }

        .cart-summary {
            width: 60%;
            padding-right: 20px;
        }

        .delivery-form {
            width: 35%;
            border: 1px solid #ccc;
            padding: 20px;
            background-color: #f9f9f9;
        }

        .delivery-form form {
            display: flex;
            flex-direction: column;
        }

        .delivery-form label,
        .delivery-form input {
            margin-bottom: 15px;
        }

        .place-order-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
        }

        .place-order-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <header>
        <h1>Blossom Boutique</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li>About Us</li>
                <li>Terms of Use</li>
                <li>Privacy Policy</li>
            </ul>
    </header>

    <section class="checkout-section">
        <div class="checkout-container">
            <!-- Cart Summary -->
            <div class="cart-summary">
                <h3 style="font-size: 24px;">Cart Summary</h3> 
                <p style="font-size: 20px;">Total Price: $<?php echo number_format($total_price, 2); ?></p> 
                <p style="font-size: 20px;">Delivery Charge: $<?php echo number_format($delivery_charge, 2); ?></p> 
                <p style="font-size: 22px; font-weight: bold;">Final Price: $<?php echo number_format($final_price, 2); ?></p> 

                <!-- chosen products -->
                <div class="chosen-products">
                    <h3>Chosen Products:</h3>
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                            <p><?php echo htmlspecialchars($product['name']); ?> - Quantity: <?php echo $product['quantity']; ?> - Price: $<?php echo number_format($product['price'] * $product['quantity'], 2); ?></p>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No products in the cart.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Delivery Details -->
            <div class="delivery-form">
                <form action="place_order.php" method="post">
                    <h3>Delivery Details</h3>
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name" required>

                    <label for="address">Address:</label>
                    <input type="text" name="address" id="address" required>

                    <label for="country">Country:</label>
                    <input type="text" name="country" id="country" required>

                    <input type="hidden" name="total_price" value="<?php echo $final_price; ?>">

                    <button type="submit" class="place-order-button">Place Order</button>
                </form>
            </div>
        </div>
    </section>

    <footer>
        <p>Contact us at: info@blossomboutique.com | &copy; 2024 Blossom Boutique Plant Store</p>
    </footer>
</body>
</html>
