<?php
session_start();
require '../conn/db_connection.php';

// Check if the user is logged in and is a supervisor
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'supervisor') {
    header('Location: ../login.php');
    exit();
}

// Handle form submission for approving/rejecting attendance
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['attendance_id']) && isset($_POST['action']) && isset($_POST['supervisor_comments'])) {
        $attendance_id = $_POST['attendance_id'];
        $action = $_POST['action']; // This should be 'approve' or 'reject'
        $status = $action === 'approve' ? 'Approved' : 'Rejected';
        $comments = $_POST['supervisor_comments'];

        // Update the attendance status and add supervisor comments
        $sql = "UPDATE attendance SET status = ?, supervisor_comments = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $status, $comments, $attendance_id);

        if ($stmt->execute()) {
            $message = "Attendance status updated successfully.";
        } else {
            $message = "Error updating attendance: " . $stmt->error;
        }
    } else {
        $message = "Invalid request.";
    }
}

// Fetch pending attendance records with associated photos and user details
$sql = "SELECT a.id, a.user_id, a.date, p.photo_path, u.username
        FROM attendance a
        JOIN photo_uploads p ON a.id = p.attendance_id
        JOIN users u ON a.user_id = u.user_id
        WHERE a.status = 'Pending'";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Attendance</title>
    <link rel="stylesheet" href="../css/supervisor_dashboard_styles.css">
    <link rel="stylesheet" href="../css/review_attendance.css">
</head>

<body>
    <header>
        <a href="supervisor_dashboard.php" style="color: #fff; text-decoration: none;">
            <div class="logo">Supervisor's Dashboard</div>
        </a>
        <div class="welcome" style="text-transform: uppercase;">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></div>
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
        <h1>Review Attendance</h1>
        <?php if (isset($message)) : ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <?php if ($result->num_rows > 0) : ?>
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Date</th>
                        <th>Photo</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['date']); ?></td>
                            <td>
                                <a href="../uploads/<?php echo htmlspecialchars($row['photo_path']); ?>"> <img src="../uploads/<?php echo htmlspecialchars($row['photo_path']); ?>" alt="Attendance Photo" style="max-width:50px;"></a>

                            </td>
                            <td>
                                <form action="review_attendance.php" method="post">
                                    <input type="hidden" name="attendance_id" value="<?php echo $row['id']; ?>">
                                    <label for="status_<?php echo $row['id']; ?>">Status:</label>
                                    <select id="status_<?php echo $row['id']; ?>" name="action">
                                        <option value="approve">Approve</option>
                                        <option value="reject">Reject</option>
                                    </select>
                                    <label for="supervisor_comments_<?php echo $row['id']; ?>">Comments:</label>
                                    <textarea id="supervisor_comments_<?php echo $row['id']; ?>" name="supervisor_comments" required></textarea>
                                    <button type="submit">Submit</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No pending attendance records.</p>
        <?php endif; ?>
    </main>
    <script src="../script/supervisor_dashboard.js"></script>
</body>

</html>