<?php
session_start();
require '../conn/db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: ../login.php");
    exit();
}

// Fetch the username
$user_id = $_SESSION['user_id'];
$sql = "SELECT username FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$username = $user['username'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intern Dashboard</title>
    <link rel="stylesheet" href="../css/intern_dashboard_styles.css">
</head>

<body>
    <header>
        <a href="intern_dashboard.php" style="color: #fff; text-decoration:none;">
            <div class="logo">Intern Dashboard</div>
        </a>
        <div class="welcome" style="text-transform:uppercase;">Welcome, <?php echo htmlspecialchars($username); ?></div>
        <div class="burger" id="burger">
            &#9776;
        </div>
    </header>
    <nav class="left-navbar" id="left-navbar">

        <!-- <a href="intern_overview.php">Overview</a> -->
        <a href="submit_update.php">Submit Update</a>
        <a href="message_center.php">Message Center</a>
        <a href="../profile.php">Profile Management</a>
        <a href="log_attendance.php">Log Attendance</a>
        <a href="../logout.php">Logout</a>
    </nav>
    <main class="main-content">
        <div class="container">
            <h1>Intern Panel</h1>
        </div>
    </main>
    <script src="../script/intern_dashboard.js"></script>
</body>

</html>