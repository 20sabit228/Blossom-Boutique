<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Blossom Boutiquee</title>
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
        <h2>Sign Up</h2>
        
        <!-- error messages -->
        <?php if (isset($_GET['error'])): ?>
            <p style="color: red; font-weight: bold;"><?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php endif; ?>
        
        <form action="signup_process.php" method="post">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="phone">Phone:</label>
            <input type="tel" name="phone" id="phone" pattern="[0-9]{10}" maxlength="10" minlength="10" required 
            title="Phone number should be exactly 10 digits">

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <label for="re_password">Re-enter Password:</label>
            <input type="password" name="re_password" id="re_password" required>

            <button type="submit">Sign Up</button>
        </form>
    </section>

    <footer>
        <p>Contact us at: info@blossomboutique.com | &copy; 2024 Blossom Boutique Plant Store</p>
    </footer>
</body>
</html>
