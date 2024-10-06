<?php
session_start();

$product_id = isset($_POST['product_id']) ? $_POST['product_id'] : '';
$action = isset($_POST['action']) ? $_POST['action'] : '';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Update the cart 
if ($product_id && isset($_SESSION['cart'][$product_id])) {
    switch ($action) {
        case 'increase':
            $_SESSION['cart'][$product_id] += 1; 
            break;
        case 'decrease':
            if ($_SESSION['cart'][$product_id] > 1) {
                $_SESSION['cart'][$product_id] -= 1; 
            } else {
                unset($_SESSION['cart'][$product_id]); 
            }
            break;
    }
} elseif ($product_id && $action == 'increase') {
    $_SESSION['cart'][$product_id] = 1; 
}

header("Location: cart.php");
exit;
?>
