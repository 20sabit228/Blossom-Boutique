<?php
session_start();
include('connection.php');

// Check for logged in
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$error_message = "";

// form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate form inputs
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error_message = "All fields are required.";
    } elseif ($new_password !== $confirm_password) {
        $error_message = "New password and confirmation do not match.";
    } else {
        // current password from the database
        $sql = "SELECT password FROM customers WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($current_password !== $user['password']) {
            $error_message = "Current password is incorrect.";
        } else {
            // Update the password 
            $sql = "UPDATE customers SET password = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $new_password, $user_id);

            if ($stmt->execute()) {
                session_destroy();

                header("Location: login.php?message=password_updated");
                exit;
            } else {
                $error_message = "Error updating password. Please try again.";
            }

            $stmt->close();
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Password - Blossom Boutique</title>
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
    </header>

    <section class="profile-info">
        <h2>Update Password</h2>

        <!-- error message -->
        <?php if ($error_message): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form action="update_password.php" method="POST">
            <label for="current_password">Current:</label>
            <input type="password" id="current_password" name="current_password" required>

            <label for="new_password">New:</label>
            <input type="password" id="new_password" name="new_password" required>

            <label for="confirm_password">Confirm:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <button type="submit">Update Password</button>
        </form>
    </section>

    <footer>
        <p>Contact us at: info@blossomboutique.com | &copy; 2024 Blossom Boutique Plant Store</p>
    </footer>
</body>
</html>
