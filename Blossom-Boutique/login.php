<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Blossom Boutique</title>
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

    <section class="auth-form">
        <h2>Login</h2>

        <!-- success message for registration -->
        <?php if (isset($_GET['registered']) && $_GET['registered'] == 'true'): ?>
            <p style="color: green; font-weight: bold;">Successfully Registered! Please log in.</p>
        <?php endif; ?>

        
       <!-- uccess message for password update -->
       <?php if (isset($_GET['message']) && $_GET['message'] == 'password_updated'):?>
            <p style="color: green; font-weight: bold;">Password successfully changed, log in again.</p>
       <?php endif; ?> 

        <!-- error message -->
        <?php if (isset($_GET['error'])): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>


        <form action="login_process.php" method="post">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <button type="submit">Login</button>
        </form>
    </section>

    <footer>
        <p>Contact us at: info@blossomboutique.com | &copy; 2024 Blossom Boutique Plant Store</p>
    </footer>
</body>
</html>
