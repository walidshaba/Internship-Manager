<?php
session_start();
require '../conn/db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $date = $_POST['date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $verification_method = $_POST['verification_method'];

    $stmt = $conn->prepare("INSERT INTO attendance (user_id, date, start_time, end_time, verification_method) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $user_id, $date, $start_time, $end_time, $verification_method);

    if ($stmt->execute()) {
        echo "Attendance logged successfully!";
    } else {
        echo "Error logging attendance: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Attendance</title>
    <link rel="stylesheet" href="../css/intern_dashboard_styles.css">
    <link rel="stylesheet" href="../css/log_attendance.css">
</head>

<body>
    <header>
        <a href="intern_dashboard.php" style="color: #fff; text-decoration:none;">
            <div class="logo">Intern Dashboard</div>
        </a>
        <div class="welcome">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></div>
        <div class="burger" id="burger">&#9776;</div>
    </header>
    <nav class="left-navbar" id="left-navbar">
        <!-- <a href="intern_overview.php">Overview</a> -->
        <a href="submit_update.php">Submit Update</a>
        <a href="message_center.php">Message Center</a>
        <a href="../profile.php">Profile Management</a>
        <!-- <a href="progress_tracking.php">Progress Tracking</a> -->
        <a href="log_attendance.php">Log Attendance</a>
        <a href="upload_photo.php">Upload Photo</a>
        <a href="../logout.php">Logout</a>
    </nav>
    <main>
        <div class="container">
            <form action="log_attendance.php" method="post">
                <label for="date">Date:</label>
                <input type="date" id="date" name="date" required />

                <label for="start_time">Start Time:</label>
                <input type="time" id="start_time" name="start_time" required />

                <label for="end_time">End Time:</label>
                <input type="time" id="end_time" name="end_time" required />

                <label for="verification_method">Verification Method:</label>
                <select id="verification_method" name="verification_method">
                    <option value="Photo">Photo Upload</option>
                    <option value="IP" disabled>IP Address</option>
                </select>

                <button type="submit">Log Attendance</button>
            </form>
        </div>
    </main>
    <script src="../script/intern_dashboard.js"></script>
</body>

</html>