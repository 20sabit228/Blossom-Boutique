<?php
session_start();
include('connection.php');

// Checking for logged in
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT name, email, phone FROM customers WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Checking for orders
$sql_order_check = "SELECT COUNT(*) AS order_count FROM orders WHERE customer_id = ?";  
$stmt_check = $conn->prepare($sql_order_check);
$stmt_check->bind_param("i", $user_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
$order_data = $result_check->fetch_assoc();
$order_count = $order_data['order_count'];
$stmt_check->close();

// orders 
if ($order_count > 0) {
    $sql_orders = "SELECT id, total, created_at FROM orders WHERE customer_id = ? ORDER BY created_at ASC";
    $stmt_orders = $conn->prepare($sql_orders);
    $stmt_orders->bind_param("i", $user_id);
    $stmt_orders->execute();
    $result_orders = $stmt_orders->get_result();
    $stmt_orders->close();
}

$conn->close();

// update successful
$update_success = isset($_GET['updated']) && $_GET['updated'] === 'true';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Blossom Boutique</title>
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

    <section class="profile-info">
        <h2>Profile Information</h2>

        <!-- success message profile updateded -->
        <?php if ($update_success): ?>
            <p style="color: green; font-weight: bold;">Profile successfully updated!</p>
        <?php endif; ?>

        <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
        <a href="update_profile.php">Update Profile</a>
        <a href="update_password.php">Update Password</a>

        <!-- order history -->
        <h3>Order History</h3>
        <?php if ($order_count > 0): ?>
            <table>
                <tr>
                    <th>Order ID</th>
                    <th>Total Amount</th>
                    <th>Order Date</th>
                </tr>
                <?php while ($order = $result_orders->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['id']); ?></td>
                        <td>$<?php echo number_format($order['total'], 2); ?></td>
                        <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No order history found.</p>
        <?php endif; ?>
    </section>

    <footer>
        <p>Contact us at: info@blossomboutique.com | &copy; 2024 Blossom Boutique Plant Store</p>
    </footer>
</body>
</html>
