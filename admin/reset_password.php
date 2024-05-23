<?php
session_start();
require '../conn/db_connection.php'; // Database connection

if (isset($_GET['token'])) {
    $token = $_GET['token'];
} else {
    echo "Invalid token.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword === $confirmPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update the user's password
        $sql = "UPDATE Users SET password = ?, reset_token = NULL WHERE reset_token = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $hashedPassword, $token);

        if ($stmt->execute()) {
            echo "Your password has been reset successfully.";
        } else {
            echo "Error resetting password.";
        }
    } else {
        echo "Passwords do not match.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../css/reset_password_styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="form-container">
            <h2>Reset Password</h2>
            <form action="reset_password.php?token=<?php echo $token; ?>" method="post">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" required>
                <span id="togglePassword" class="fa fa-fw fa-eye field-icon"></span>

                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
                <button type="submit">Reset Password</button>
            </form>

        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.querySelector('#togglePassword');
            const password = document.querySelector('#new_password');

            togglePassword.addEventListener('click', function() {
                // Toggle the type attribute
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);

                // Toggle the eye slash icon
                this.classList.toggle('fa-eye-slash');
            });
        });
    </script>
</body>

</html>