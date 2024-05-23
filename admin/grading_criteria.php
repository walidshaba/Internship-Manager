<?php
session_start();
require '../conn/db_connection.php';
// include 'navbar.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grading Criteria</title>
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
            <h1>Grading Criteria</h1>
            <form action="save_grading_criteria.php" method="POST">
                <div class="form-group">
                    <label for="criteria1">Criteria 1:</label>
                    <input type="text" id="criteria1" name="criteria1" required>
                </div>
                <div class="form-group">
                    <label for="criteria2">Criteria 2:</label>
                    <input type="text" id="criteria2" name="criteria2" required>
                </div>
                <div class="form-group">
                    <label for="criteria3">Criteria 3:</label>
                    <input type="text" id="criteria3" name="criteria3" required>
                </div>
                <button type="submit">Save Criteria</button>
            </form>
        </div>
    </div>
</body>

</html>