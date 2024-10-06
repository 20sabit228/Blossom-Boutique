<?php
include('connection.php');

// Checking form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $re_password = $_POST['re_password'];


    // 10 digit phn num
    if (!preg_match('/^[0-9]{10}$/', $phone)) {
        header('Location: signup.php?error=Invalid phone number. Please enter a 10-digit number.');
        exit;
    }

    // passwords match
    if ($password !== $re_password) {
        header("Location: signup.php?error=Passwords do not match. Please try again.");
        exit;
    }

    // email exists
    $email_check_sql = "SELECT id FROM customers WHERE email = ?";
    if ($stmt = $conn->prepare($email_check_sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            header("Location: signup.php?error=Email already registered. Please use a different email.");
            $stmt->close();
            exit;
        }

        $stmt->close();
    }

    $sql = "INSERT INTO customers (name, email, phone, password) VALUES (?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssis", $name, $email, $phone, $password);
        if ($stmt->execute()) {
            header("Location: login.php?registered=true");
            exit;
        } else {
            header("Location: signup.php?error=Error: Could not execute the query. Please try again.");
            exit;
        }

        $stmt->close();
    } else {
        header("Location: signup.php?error=Error: Could not prepare the query. Please try again.");
        exit;
    }

    $conn->close();
}
?>
