<?php
include('connection.php');
session_start();

// Checking form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM customers WHERE BINARY email = ? AND BINARY password = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['id']; 
            $_SESSION['user_name'] = $user['name'];

            header("Location: index.php");
            exit;
        } else {
            header("Location: login.php?error=Invalid email or password. Please try again.");
            exit;
        }

        $stmt->close();
    } else {
        echo "Error: Could not prepare the query. " . $conn->error;
    }

    $conn->close();
}
?>
