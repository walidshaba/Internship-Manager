<?php
include 'navbar.php';
// session_start();
require '../conn/db_connection.php';

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
    <title>Admin Panel</title>
    <link rel="stylesheet" href="../css/admin_panel_styles.css">
</head>

<body>
    <div class="sidebar">
        <h2>Welcome, <?php echo htmlspecialchars($username); ?></h2>
        <a href="../logout.php" class="logout">Logout</a>
        <h3>User Management</h3>
        <ul>
            <li><a href="add_user.php">Add User</a></li>
            <li><a href="edit_user.php">Edit User</a></li>
            <li><a href="delete_user.php">Delete User</a></li>
            <li><a href="reset_password.php">Reset Password</a></li>
            <li><a href="update_contact_info.php">Update Contact Information</a></li>
        </ul>
        <h3>System Configuration</h3>
        <ul>
            <li><a href="assign_interns.php">Assign Interns to Supervisors</a></li>
            <li><a href="communication_preferences.php">Communication Preferences</a></li>
            <li><a href="grading_criteria.php">Grading Criteria</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="container">
            <h1>Admin Panel</h1>
        </div>
    </div>
</body>

</html>