<?php
session_start();

// Get product ID
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

// Add in session
if ($product_id > 0) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity; // Increment the quantity 
    } else {
        $_SESSION['cart'][$product_id] = $quantity; // new product 
    }
}

header("Location: cart.php");
exit;
?>
