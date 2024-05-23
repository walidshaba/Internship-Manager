<?php
session_start();
require '../conn/db_connection.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Communication Preferences</title>
    <link rel="stylesheet" href="../css/admin_panel_styles.css">
</head>

<body>
    <div class="sidebar">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
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
            <h1>Communication Preferences</h1>
            <form action="save_communication_preferences.php" method="POST">
                <div class="form-group">
                    <label for="email_notifications">Email Notifications:</label>
                    <input type="checkbox" id="email_notifications" name="email_notifications">
                </div>
                <div class="form-group">
                    <label for="sms_notifications">SMS Notifications:</label>
                    <input type="checkbox" id="sms_notifications" name="sms_notifications">
                </div>
                <div class="form-group">
                    <label for="notification_frequency">Notification Frequency:</label>
                    <select id="notification_frequency" name="notification_frequency">
                        <option value="immediate">Immediate</option>
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                    </select>
                </div>
                <button type="submit">Save Preferences</button>
            </form>
        </div>
    </div>
</body>

</html>