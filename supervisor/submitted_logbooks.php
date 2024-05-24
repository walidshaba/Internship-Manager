<?php
session_start();
require '../conn/db_connection.php';

// Check if the user is logged in and is a supervisor
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'supervisor') {
    header('Location: ../login.php');
    exit;
}

// Handle form submission for grading
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submission_id']) && isset($_POST['grade'])) {
    $submission_id = $_POST['submission_id'];
    $grade = $_POST['grade'];

    // Update the logbook submission with the grade if it hasn't been graded yet
    $stmt = $conn->prepare("UPDATE logbook_submissions SET grade = ? WHERE id = ? AND grade IS NULL");
    $stmt->bind_param("ii", $grade, $submission_id);

    if ($stmt->execute()) {
        $message = "Logbook graded successfully!";
    } else {
        $message = "Error grading logbook: " . $stmt->error;
    }
}

// Fetch username
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
    <title>Submitted Logbooks</title>
    <link rel="stylesheet" href="../css/submitted_logbook.css">
    <link rel="stylesheet" href="../css/supervisor_dashboard_styles.css">
</head>

<body>
    <header>
        <a href="supervisor_dashboard.php" style="color: #fff; text-decoration:none;">
            <div class="logo">Supervisor's Dashboard</div>
        </a>
        <div class="welcome" style="text-transform:uppercase;">Welcome, <?php echo htmlspecialchars($username); ?></div>
        <div class="burger" id="burger">&#9776;</div>
    </header>
    <nav class="left-navbar" id="left-navbar">
        <a href="supervisor_dashboard.php">Dashboard</a>
        <a href="message_center.php">Message Center</a>
        <a href="../profile.php">Profile Management</a>
        <a href="review_attendance.php">Approve Attendance</a>
        <a href="submitted_logbooks.php">Interns Logbooks</a>
        <a href="../logout.php">Logout</a>
    </nav>

    <main>
        <h2>Mark Logbook</h2>
        <?php if (isset($message)) : ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <ul>
            <?php
            $result = $conn->query("SELECT l.id, l.subject, l.logbook_path, l.submission_date, l.grade, u.username
                FROM logbook_submissions l
                JOIN users u ON l.user_id = u.user_id
                ORDER BY l.submission_date DESC");

            while ($row = $result->fetch_assoc()) {
                echo "<li>";
                echo "<strong>Intern:</strong> " . htmlspecialchars($row['username']) . "<br>";
                echo "<strong>Subject:</strong> " . htmlspecialchars($row['subject']) . "<br>";
                echo "<strong>File:</strong> <a href='" . htmlspecialchars($row['logbook_path']) . "'>View Logbook</a><br>";
                echo "<strong>Submitted on:</strong> " . htmlspecialchars($row['submission_date']) . "<br>";

                if ($row['grade'] === null) {
                    echo "<form action='submitted_logbooks.php' method='post' style='display:inline;'>";
                    echo "<input type='hidden' name='submission_id' value='" . $row['id'] . "'>";
                    echo "<label for='grade'>Grade:</label>";
                    echo "<input type='number' name='grade' min='0' max='100' required>";
                    echo "<button type='submit'>Submit Grade</button>";
                    echo "</form>";
                } else {
                    echo "<strong>Grade:</strong> " . htmlspecialchars($row['grade']);
                }

                echo "</li>";
            }
            ?>
        </ul>
    </main>

    <script src="../script/supervisor_dashboard.js"></script>
</body>

</html>