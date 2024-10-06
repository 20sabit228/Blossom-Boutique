<?php
session_start();
include('connection.php'); 

// Checking for logged in
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header("Location: login.php?error=Please log in first");
    exit;
}

$total_price = 0;
$products = [];
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// product details from the database for display
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $row['quantity'] = isset($cart_items[$row['product_id']]) ? $cart_items[$row['product_id']] : 0;  
    $products[] = $row;
    $total_price += $row['price'] * $row['quantity']; 
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - Blossom Boutique</title>
    <link rel="shortcut icon" href="Images/Blossom_Boutique_Logo.png" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
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
        </nav>
    </header>

    <section class="cart-section">
        <div class="cart-container">
            <!-- Products Section -->
            <div class="product-section">
                <h2>Products</h2>
                <div class="product-list">
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                            <div class="product-item">
                                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" />
                                <p><?php echo htmlspecialchars($product['name']); ?></p>
                                <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
                                <p>Type: <?php echo htmlspecialchars($product['product_type']); ?></p> 
                                <form action="update_cart.php" method="post">
                                    <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                    <div class="quantity-controls">
                                        <button type="submit" name="action" value="decrease" class="quantity-button">-</button>
                                        <input type="text" name="quantity" value="<?php echo $product['quantity']; ?>" readonly class="quantity-display">
                                        <button type="submit" name="action" value="increase" class="quantity-button">+</button>
                                    </div>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No products available.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="cart-summary">
                <h2>Cart Summary</h2>
                <p>Total Price: <span class="total-price">$<?php echo number_format($total_price, 2); ?></span></p>
                
                <!-- products in the cart summary -->
                <div class="chosen-products">
                    <h3>Chosen Products:</h3>
                    <?php if (!empty($cart_items)): ?>
                        <?php foreach ($products as $product): ?>
                            <?php if ($product['quantity'] > 0): ?>
                                <p><?php echo htmlspecialchars($product['name']); ?> - Quantity: <?php echo $product['quantity']; ?> - Price: $<?php echo number_format($product['price'] * $product['quantity'], 2); ?> - Type: <?php echo htmlspecialchars($product['product_type']); ?></p> 
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No products in the cart.</p>
                    <?php endif; ?>
                </div>

                <!-- Checkout Button -->
                <?php if (!empty($cart_items)): ?>
                    <a href="checkout.php" class="checkout-button">Proceed to Checkout</a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <footer>
        <p>Contact us at: info@blossomboutique.com | &copy; 2024 Blossom Boutique Plant Store</p>
    </footer>
</body>
</html>
