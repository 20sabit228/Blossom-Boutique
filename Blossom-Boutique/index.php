<?php
session_start(); 
include('connection.php');  

// Checking for logged in
$is_logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'];
$user_name = $is_logged_in ? $_SESSION['user_name'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blossom Boutique</title>
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
        <div class="auth-buttons">
            <?php if ($is_logged_in): ?>
                <div class="user-info">
                    <span>Welcome, <?php echo htmlspecialchars($user_name); ?>!</span>
                    <a href="profile.php" class="auth-button profile-button">View Profile</a>
                    <a href="logout.php" class="auth-button logout-button">Logout</a>
                </div>
            <?php else: ?>
                <a href="login.php" class="auth-button">Login</a>
                <a href="signup.php" class="auth-button">Sign Up</a>
            <?php endif; ?>
        </div>
    </header>

    <section id="home" class="hero-section">
        <h2>Explore Our Beautiful Plant Collection</h2>
        <p>Find indoor and outdoor plants for your space.</p>
    </section>

    <!-- Filter Section -->
    <section class="options-section">
        <div class="option-box">
            <h3>Select Plant Collection</h3>
            <div class="option-buttons">
                <!-- Indoor and Outdoor buttons -->
                <a href="index.php?type=indoor" class="option-btn">Indoor Plants</a>
                <a href="index.php?type=outdoor" class="option-btn">Outdoor Plants</a>
            </div>
        </div>
    </section>

    <!-- Plant Collection Display -->
    <section class="plant-collection">
        <div class="collection-message">
            <h2>
                <?php
                    $type = isset($_GET['type']) ? $_GET['type'] : 'indoor'; // Default to 'indoor'
                    echo ucfirst($type) . " Plant Collection";
                ?>
            </h2>
        </div>

        <div class="plant-gallery">
            <?php
                // products from the database based on the selected type
                $sql = "SELECT * FROM products WHERE product_type = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $type);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    // Loop for displaying products
                    while ($row = $result->fetch_assoc()) {
                        echo '
                        <div class="plant-item">
                            <img src="' . $row['image_url'] . '" alt="' . $row['name'] . '" />
                            <p>' . $row['name'] . '</p>
                            <p>$' . number_format($row['price'], 2) . '</p>
                            <p>' . $row['description'] . '</p>
                            <div class="purchase-options">';
                            
                        // Buy Now button
                        if ($is_logged_in) {
                            echo '<a href="add_to_cart.php?product_id=' . $row['product_id'] . '&quantity=1" class="purchase-button">Buy Now</a>';
                        } else {
                            echo '<a href="login.php?error=Please log in first" class="purchase-button">Buy Now</a>';
                        }

                        echo '
                            </div>
                        </div>';
                    }
                } else {
                    echo "<p>No products available for this category.</p>";
                }

                $stmt->close();
                $conn->close();
            ?>
        </div>
    </section>

    <footer>
        <p>Contact us at: info@blossomboutique.com | &copy; 2024 Blossom Boutique Plant Store</p>
    </footer>
</body>
</html>
