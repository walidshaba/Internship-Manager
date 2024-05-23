<?php
session_start();
require '../conn/db_connection.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch the username from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT username FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$username = htmlspecialchars($user['username']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="../css/navbar_styles.css">
</head>

<body>
    <nav class="navbar">
        <div class="navbar-left">
            <span>Welcome, <?php echo $username; ?></span>
        </div>
        <div class="navbar-right">
            <a href="../logout.php">Logout</a>
        </div>
    </nav>
</body>

</html>