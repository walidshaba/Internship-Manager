`<?php
session_start();
require '../conn/db_connection.php';
// include 'navbar.php';

// Fetch users to edit
$users_result = $conn->query("SELECT * FROM users");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="../css/admin_panel_styles.css">
    <link rel="stylesheet" href="../css/edit_user_styles.css">
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
            <h1>Edit User</h1>
            <form action="edit_user_process.php" method="POST">
                <div class="form-group">
                    <label for="user_id">Select User:</label>
                    <select id="user_id" name="user_id" required>
                        <?php while ($user = $users_result->fetch_assoc()) : ?>
                            <option value="<?php echo $user['user_id']; ?>">
                                <?php echo htmlspecialchars($user['username']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="role">Role:</label>
                    <select id="role" name="role" required>
                        <option value="intern">Intern</option>
                        <option value="supervisor">Supervisor</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="first_name">First Name:</label>
                    <input type="text" id="first_name" name="first_name" required>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name:</label>
                    <input type="text" id="last_name" name="last_name" required>
                </div>
                <div class="form-group">
                    <label for="phone_number">Phone Number:</label>
                    <input type="text" id="phone_number" name="phone_number" required>
                </div>
                <button type="submit">Update User</button>
            </form>
        </div>
    </div>
</body>

</html>