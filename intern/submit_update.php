<?php
session_start();
require '../conn/db_connection.php';

// Check if the user is logged in and is an intern
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'intern') {
    header('Location: ../login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Logbook Update</title>
    <link rel="stylesheet" href="../css/submit_update.css">
    <link rel="stylesheet" href="../css/intern_dashboard_styles.css">
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
        <a href="submit_update.php">Submit Update</a>
        <a href="message_center.php">Message Center</a>
        <a href="../profile.php">Profile Management</a>
        <a href="log_attendance.php">Log Attendance</a>
        <a href="../logout.php">Logout</a>
    </nav>
    <main>
        <h2>Make Submission</h2>

        <?php if (isset($_GET['success'])) : ?>
            <div class="success-message">Logbook submitted successfully!</div>
        <?php elseif (isset($_GET['error'])) : ?>
            <div class="error-message"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <form action="submit_update_functionality.php" method="post" enctype="multipart/form-data">
            <label for="subject">Subject:</label>
            <input type="text" id="subject" name="subject" required>

            <label for="logbook">Upload Logbook:</label>
            <input type="file" id="logbook" name="logbook" accept="image/*" required>

            <button type="submit">Submit Logbook</button>
        </form>

        <h2>Your Submissions</h2>
        <ul>
            <?php
            $user_id = $_SESSION['user_id'];
            $result = $conn->query("SELECT id, subject, logbook_path, submission_date FROM logbook_submissions WHERE user_id = $user_id ORDER BY submission_date DESC");

            while ($row = $result->fetch_assoc()) {
                echo "<li>";
                echo "<strong>Subject:</strong> " . htmlspecialchars($row['subject']) . "<br>";
                echo "<strong>File:</strong> <a href='" . htmlspecialchars($row['logbook_path']) . "'>View Logbook</a><br>";
                echo "<strong>Submitted on:</strong> " . htmlspecialchars($row['submission_date']) . "<br>";
                echo "<form action='delete_submission.php' method='post' class='delete-form'>
                        <input type='hidden' name='submission_id' value='" . $row['id'] . "'>
                        <button type='submit' class='delete-button'>Delete</button>
                      </form>";
                echo "</li>";
            }
            ?>
        </ul>
    </main>

</body>
<script src="../script/intern_dashboard.js"></script>

</html>