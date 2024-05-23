<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deactivate Account</title>
    <link rel="stylesheet" href="css/deactivate_account.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="form-container">
            <h2>Deactivate Account</h2>
            <p>Are you sure you want to deactivate your account? This action cannot be undone.</p>
            <form action="deactivate_account_process.php" method="post" onsubmit="return confirmDeactivation();">
                <button type="submit">Deactivate Account</button>
            </form>
            <p><a href="profile.php">Cancel</a></p>
        </div>
    </div>
    <script src="script/deactivate_script.js"></script>
</body>

</html>