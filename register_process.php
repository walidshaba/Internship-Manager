<?php
session_start();
require 'conn/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role']; // Default role
    $verification_token = bin2hex(random_bytes(16));
    $username = $_POST['username'];

    // Check if the email already exists
    $checkEmailSql = "SELECT * FROM users WHERE email = ?";
    $checkEmailStmt = $conn->prepare($checkEmailSql);
    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();
    $checkEmailResult = $checkEmailStmt->get_result();

    if ($checkEmailResult->num_rows > 0) {
        echo "<script>
            alert('Error: This email is already registered. Please use a different email.');
            javascript:history.back();
        </script>";
        $checkEmailStmt->close();
        $conn->close();
        exit();
    }

    // Check if there's an existing admin
    $result = $conn->query("SELECT * FROM users WHERE role = 'admin'");
    if ($result->num_rows > 0 && $_POST['role'] == 'admin') {
        die("Admin already exists.");
    }

    $sql = "INSERT INTO users (first_name, last_name, email, phone_number, password, role, verification_token, username) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $first_name, $last_name, $email, $phone_number, $password, $role, $verification_token, $username);

    if ($stmt->execute()) {
        // Send verification email
        $to = $email;
        $subject = "Email Verification";
        $message = "Click the link to verify your email: http://yourdomain.com/verify_email.php?token=$verification_token";
        $headers = "From: yourname@example.com\r\nReply-To: yourname@example.com";
        ini_set("SMTP", "smtp.gmail.com");
        ini_set("smtp_port", "587");
        ini_set("sendmail_from", "yourname@example.com");
        ini_set("auth_username", "your_gmail_username");
        ini_set("auth_password", "your_gmail_password");
        ini_set("smtp_auth", "true");
        ini_set("smtp_secure", "tls");
        if (mail($to, $subject, $message, $headers)) {
            echo "Registration successful! Please check your email to verify your account.";
        } else {
            echo "Error! sending Message to your email";
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
