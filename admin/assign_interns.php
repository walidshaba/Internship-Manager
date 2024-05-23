<?php
session_start();
require '../conn/db_connection.php';
// include 'navbar.php';

// Fetch interns and supervisors
$interns_result = $conn->query("SELECT * FROM users WHERE role = 'intern'");
$supervisors_result = $conn->query("SELECT * FROM users WHERE role = 'supervisor'");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Interns to Supervisors</title>
    <link rel="stylesheet" href="../css/admin_panel_styles.css">
    <link rel="stylesheet" href="../css/assign_interns_styles.css">
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
            <h1>Assign Interns to Supervisors</h1>
            <form action="assign_interns_process.php" method="POST">
                <div class="form-group">
                    <label for="intern_id">Select Intern:</label>
                    <select id="intern_id" name="intern_id" required>
                        <?php while ($intern = $interns_result->fetch_assoc()) : ?>
                            <option value="<?php echo $intern['user_id']; ?>">
                                <?php echo htmlspecialchars($intern['username']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="supervisor_id">Select Supervisor:</label>
                    <select id="supervisor_id" name="supervisor_id" required>
                        <?php while ($supervisor = $supervisors_result->fetch_assoc()) : ?>
                            <option value="<?php echo $supervisor['user_id']; ?>">
                                <?php echo htmlspecialchars($supervisor['username']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit">Assign</button>
            </form>
        </div>
    </div>
</body>

</html>