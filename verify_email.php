<?php
session_start();
include 'conn/db_connection.php';

if (isset($_GET['token'])) {
    $verification_token = $_GET['token'];

    // Use prepared statements to prevent SQL injection
    $sql = "SELECT * FROM users WHERE verification_token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $verification_token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Update the user's verified status in the database
        $sql_update_verified = "UPDATE users SET verified = 1, verification_token = NULL WHERE verification_token = ?";
        $stmt_update = $conn->prepare($sql_update_verified);
        $stmt_update->bind_param("s", $verification_token);

        if ($stmt_update->execute()) {
            $message = "Email verification successful!";
            echo "<script>
            setTimeout(function() {
                switch ('" . $_SESSION['role'] . "') {
                    case 'intern':
                        window.location.href = 'intern_dashboard.php';
                        break;
                    case 'supervisor':
                        window.location.href = 'supervisor_dashboard.php';
                        break;
                    case 'admin':
                        window.location.href = 'admin_dashboard.php';
                        break;
                    default:
                        console.log('Invalid role');
                }
            }, 1500);
          </script>";
        } else {
            $message = "Error updating verification status: " . $stmt_update->error;
        }
    } else {
        $message = "Invalid verification token.";
    }
} else {
    $message = "Verification token not provided.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <link rel="stylesheet" href="css/verify_email.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="message-container">
            <h2>Email Verification</h2>
            <p><?php echo $message; ?></p>
            <a href="login.php" class="home-button">Go to Login</a>
        </div>
    </div>
</body>

</html>