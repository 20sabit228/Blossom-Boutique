<?php
session_start();
include('connection.php'); 

// Checking for logged in
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header("Location: login.php?error=Please log in first");
    exit;
}

if (!isset($_SESSION['user_id'])) {
    die("Customer ID is not set.");
}

$customer_id = $_SESSION['user_id']; 
$name = isset($_POST['name']) ? $conn->real_escape_string($_POST['name']) : '';
$address = isset($_POST['address']) ? $conn->real_escape_string($_POST['address']) : '';
$country = isset($_POST['country']) ? $conn->real_escape_string($_POST['country']) : '';
$total_price = isset($_POST['total_price']) ? floatval($_POST['total_price']) : 0;

if ($total_price <= 5) {
    die("Invalid total price.");
}

// order insertion query
$stmt = $conn->prepare("INSERT INTO orders (customer_id, total, name, address, country) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("idsss", $customer_id, $total_price, $name, $address, $country);

if (!$stmt->execute()) {
    die("Failed to place order: " . $stmt->error);
}

$order_id = $stmt->insert_id;
$stmt->close();

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");

    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        // price of the current product from the products table
        $price_query = $conn->prepare("SELECT price FROM products WHERE product_id = ?");
        $price_query->bind_param("s", $product_id);
        $price_query->execute();
        $price_query->bind_result($price);
        $price_query->fetch();
        $price_query->close();
        $stmt->bind_param("isid", $order_id, $product_id, $quantity, $price);
        $stmt->execute();
    }
    $stmt->close();
}

unset($_SESSION['cart']);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - Blossom Boutique</title>
    <link rel="shortcut icon" href="Images/Blossom_Boutique_Logo.png" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <style>
        .confirmation-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 60vh; 
            text-align: center;
            padding: 20px;
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
        </nav>
    </header>

    <section class="confirmation-container">
        <h2>Order Confirmation</h2>
        <p>Order placed successfully!</p>
        <p>Order ID: <?php echo htmlspecialchars($order_id); ?></p>
        <p>Name: <?php echo htmlspecialchars($name); ?></p>
        <p>Address: <?php echo htmlspecialchars($address); ?></p>
        <p>Country: <?php echo htmlspecialchars($country); ?></p><br>
        <p>We have received your order and are processing it. </p>
        <p>You will receive an email confirmation shortly.</p>
        <p>If you have any questions, please feel free to contact us.</p>
    </section>

    <footer>
        <p>Contact us at: info@blossomboutique.com | &copy; 2024 Blossom Boutique Plant Store</p>
    </footer>
</body>
</html>
