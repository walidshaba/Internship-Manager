<?php
session_start();
require '../conn/db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Fetch user data from database
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Fetch recent activities, current projects, and other data as needed
$recent_activities_stmt = $conn->prepare("SELECT * FROM activities WHERE user_id = ? ORDER BY date DESC LIMIT 5");
$recent_activities_stmt->bind_param("i", $user_id);
$recent_activities_stmt->execute();
$recent_activities = $recent_activities_stmt->get_result();

// $current_projects_stmt = $conn->prepare("SELECT * FROM projects WHERE user_id = ? AND status = 'ongoing'");
// $current_projects_stmt->bind_param("i", $user_id);
// $current_projects_stmt->execute();
// $current_projects = $current_projects_stmt->get_result();

// $upcoming_deadlines_stmt = $conn->prepare("SELECT * FROM deadlines WHERE user_id = ? AND date >= CURDATE() ORDER BY date ASC");
// $upcoming_deadlines_stmt->bind_param("i", $user_id);
// $upcoming_deadlines_stmt->execute();
// $upcoming_deadlines = $upcoming_deadlines_stmt->get_result();

// $performance_metrics_stmt = $conn->prepare("SELECT * FROM performance_metrics WHERE user_id = ?");
// $performance_metrics_stmt->bind_param("i", $user_id);
// $performance_metrics_stmt->execute();
// $performance_metrics = $performance_metrics_stmt->get_result();

// $mentor_stmt = $conn->prepare("SELECT * FROM mentors WHERE id = ?");
// $mentor_stmt->bind_param("i", $user['mentor_id']);
// $mentor_stmt->execute();
// $mentor = $mentor_stmt->get_result()->fetch_assoc();

// $announcements_stmt = $conn->prepare("SELECT * FROM announcements ORDER BY date DESC LIMIT 5");
// $announcements_stmt->execute();
// $announcements = $announcements_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intern Dashboard - Overview</title>
    <link rel="stylesheet" href="../css/intern_dashboard_styles.css">
    <link rel="stylesheet" href="../css/overview_styles.css">
</head>

<body>

    <header>
        <div class="logo">Intern Dashboard</div>
        <div class="welcome">Welcome, <?php echo htmlspecialchars($user['username']); ?></div>
        <div class="burger" id="burger">&#9776;</div>
    </header>
    <nav class="left-navbar" id="left-navbar">
        <a href="intern_overview.php">Overview</a>
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
            <section class="profile-summary">
                <img src="../uploads/<?php echo htmlspecialchars($user['profile_photo']); ?>" alt="Profile Photo">
                <h2><?php echo htmlspecialchars($user['full_name']); ?></h2>
                <p><?php echo htmlspecialchars($user['role']); ?></p>
            </section>

            <section class="current-projects">
                <h2>Current Projects</h2>
                <ul>
                    <?php while ($project = $current_projects->fetch_assoc()) : ?>
                        <li><?php echo htmlspecialchars($project['project_name']); ?> - Due: <?php echo htmlspecialchars($project['due_date']); ?></li>
                    <?php endwhile; ?>
                </ul>
            </section>

            <section class="upcoming-deadlines">
                <h2>Upcoming Deadlines & Events</h2>
                <ul>
                    <?php while ($deadline = $upcoming_deadlines->fetch_assoc()) : ?>
                        <li><?php echo htmlspecialchars($deadline['event_name']); ?> - <?php echo htmlspecialchars($deadline['date']); ?></li>
                    <?php endwhile; ?>
                </ul>
            </section>

            <section class="recent-activity">
                <h2>Recent Activity</h2>
                <ul>
                    <?php while ($activity = $recent_activities->fetch_assoc()) : ?>
                        <li><?php echo htmlspecialchars($activity['description']); ?> - <?php echo htmlspecialchars($activity['date']); ?></li>
                    <?php endwhile; ?>
                </ul>
            </section>

            <section class="performance-metrics">
                <h2>Performance Metrics</h2>
                <ul>
                    <?php while ($metric = $performance_metrics->fetch_assoc()) : ?>
                        <li><?php echo htmlspecialchars($metric['metric_name']); ?>: <?php echo htmlspecialchars($metric['value']); ?></li>
                    <?php endwhile; ?>
                </ul>
            </section>

            <section class="announcements">
                <h2>Announcements</h2>
                <ul>
                    <?php while ($announcement = $announcements->fetch_assoc()) : ?>
                        <li><?php echo htmlspecialchars($announcement['title']); ?> - <?php echo htmlspecialchars($announcement['date']); ?></li>
                    <?php endwhile; ?>
                </ul>
            </section>

            <section class="mentor-info">
                <h2>Mentor Information</h2>
                <p>Name: <?php echo htmlspecialchars($mentor['name']); ?></p>
                <p>Email: <?php echo htmlspecialchars($mentor['email']); ?></p>
            </section>

            <section class="feedback-support">
                <h2>Feedback & Support</h2>
                <form action="submit_feedback.php" method="post">
                    <label for="feedback">Your Feedback:</label>
                    <textarea id="feedback" name="feedback" required></textarea>
                    <button type="submit">Submit Feedback</button>
                </form>
            </section>
        </div>
    </main>
    <script src="../script/intern_dashboard.js"></script>
</body>

</html>