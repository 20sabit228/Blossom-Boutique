<?php
session_start();
include('connection.php');

// Checking for logged in
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$error_message = "";
$success_message = "";

$sql = "SELECT name, email, phone FROM customers WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    if (empty($name) || empty($email) || empty($phone)) {
        $error_message = "All fields are required.";
    } else {
        // Check email 
        $sql = "SELECT id FROM customers WHERE email = ? AND id != ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $email, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = "Email already exists. Please choose a different email.";
        } else {
            // Update profile 
            $sql = "UPDATE customers SET name = ?, email = ?, phone = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $name, $email, $phone, $user_id);

            if ($stmt->execute()) {
                $success_message = "Profile updated successfully.";
                $_SESSION['user_name'] = $name;

                header("Location: profile.php?updated=true");
                exit;
            } else {
                $error_message = "Error updating profile. Please try again.";
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
    <title>Update Profile - Blossom Boutique</title>
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
        <h2>Update Profile</h2>

        <!--success or error message -->
        <?php if ($error_message): ?>
            <p class="error-message" style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <p class="success-message" style="color: green;"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <form action="update_profile.php" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <label for="phone">Phone:</label>
            <input type="tel" name="phone" id="phone" pattern="[0-9]{10}" maxlength="10" minlength="10" value="<?php echo htmlspecialchars($user['phone']); ?>"required 
            title="Phone number should be exactly 10 digits">

            <button type="submit">Update Profile</button>
        </form>
    </section>

    <footer>
        <p>Contact us at: info@blossomboutique.com | &copy; 2024 Blossom Boutique Plant Store</p>
    </footer>
</body>
</html>
