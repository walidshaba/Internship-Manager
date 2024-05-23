<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="form-container">
            <h2>Login</h2>
            <form action="login_process.php" method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group password-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                    <button type="button" id="togglePassword" class="toggle-password">Show</button>
                </div>
                <button type="submit">Login</button>
            </form>
            <p>Don't have an account? <a href="register.html">Register here</a></p>
            <p><a href="forgot_password.php">Forgot your password?</a></p>
        </div>
    </div>
    <script src="script/script.js"></script>
</body>

</html>