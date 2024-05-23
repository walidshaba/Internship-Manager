<?php
session_start();
require 'conn/db_connection.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $token = bin2hex(random_bytes(50));

    // Check if email exists
    $sql = "SELECT * FROM Users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Store the reset token
        $sql = "UPDATE Users SET reset_token = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $token, $email);
        if ($stmt->execute()) {
            // Send the reset link via email
            $resetLink = "http://yourwebsite.com/reset_password.php?token=$token";
            $to = $email;
            $subject = "Password Reset Request";
            $message = "Click the following link to reset your password: $resetLink";
            $headers = "From: no-reply@yourwebsite.com";

            if (mail($to, $subject, $message, $headers)) {
                echo "A password reset link has been sent to your email.";
            } else {
                echo "Failed to send email.";
            }
        } else {
            echo "Failed to store reset token.";
        }
    } else {
        echo "Email not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="css/forgot_password_styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="form-container">
            <h2>Forgot Password</h2>
            <form action="forgot_password.php" method="post">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
                <button type="submit">Send Reset Link</button>
            </form>
            <p><a href="login.php">Back to Login</a></p>
        </div>
    </div>
</body>

</html>