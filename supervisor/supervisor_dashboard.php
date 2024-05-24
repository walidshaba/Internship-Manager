<?php
session_start();
require '../conn/db_connection.php';

// Check if the user is logged in and is a supervisor
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'supervisor') {
    header('Location: ../login.php');
    exit;
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
    <title>Supervisor Dashboard</title>
    <link rel="stylesheet" href="../css/supervisor_dashboard_styles.css">
</head>

<body>

    <header>
        <a href="supervisor_dashboard.php" style="color: #fff; text-decoration:none;">
            <div class="logo">Supervisor's Dashboard</div>
        </a>
        <div class="welcome" style="text-transform:uppercase;">Welcome, <?php echo htmlspecialchars($username); ?></div>
        <div class="burger" id="burger">
            &#9776;
        </div>
    </header>
    <nav class="left-navbar" id="left-navbar">
        <a href="supervisor_dashboard.php">Dashboard</a>
        <a href="message_center.php">Message Center</a>
        <a href="../profile.php">Profile Management</a>
        <a href="review_attendance.php">Approve Attendance</a>
        <a href="submitted_logbooks.php">Interns Logoboks</a>

        <a href="../logout.php">Logout</a>
    </nav>
    <main class="main-container">
        <div class="container">
            <h2>Supervisors Panels</h2>
        </div>

    </main>
</body>
<script src="../script/supervisor_dashboard.js"></script>

</html>