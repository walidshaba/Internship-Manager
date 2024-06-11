<?php
session_start();
require '../conn/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['assign'])) {
    $intern_id = $_POST['intern_id'];
    $supervisor_id = $_POST['supervisor_id'];

    $sql = "INSERT INTO assignments (intern_id, supervisor_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $intern_id, $supervisor_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "<script>alert('Intern assigned to supervisor successfully!')</script>";
    } else {
        $_SESSION['error'] = "<script>\"Error assigning intern: \"</script>" . $stmt->error;
    }
    header("Location: assign_interns.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $assignment_id = $_POST['assignment_id'];

    $sql = "DELETE FROM assignments WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $assignment_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "<script>alert(\"Pairing deleted successfully!\")</script>";
    } else {
        $_SESSION['error'] = "<script>alert(\"Error deleting pairing: \") </script>" . $stmt->error;
    }
    header("Location: assign_interns.php");
    exit();
}

// Fetch interns, supervisors, and assignments
$interns_result = $conn->query("SELECT * FROM users WHERE role = 'intern' AND user_id NOT IN (SELECT intern_id FROM assignments)");
$supervisors_result = $conn->query("SELECT * FROM users WHERE role = 'supervisor'");
$assignments_result = $conn->query("
    SELECT a.id, i.username as intern, s.username as supervisor 
    FROM assignments a 
    JOIN users i ON a.intern_id = i.user_id 
    JOIN users s ON a.supervisor_id = s.user_id
");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Interns to Supervisors</title>
    <link rel="stylesheet" href="../css/admin_panel_styles.css">
    <link rel="stylesheet" href="../css/assign_interns_styles.css">
    <style>
        table {
            position: relative;
            align-self: center;
            width: 50%;
        }

        th,
        tr,
        td {
            padding: 10px, 20px;
            text-align: center;
        }

        .message {
            background-color: #dff0d8;
            color: #3c763d;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #d6e9c6;
            border-radius: 4px;
        }

        .error {
            background-color: #f2dede;
            color: #a94442;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #ebccd1;
            border-radius: 4px;
        }
    </style>
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


            <form action="assign_interns.php" method="POST">
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
                <button type="submit" name="assign">Assign</button>
            </form>



        </div>
        <div style=" width:100%; display:flex; flex-direction: column;">
            <h2>Current Intern-Supervisor Pairings</h2>
            <table>
                <thead>
                    <tr style="text-transform: uppercase;">
                        <th>Intern</th>
                        <th>Supervisor</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody style="text-transform: uppercase;">
                    <?php while ($assignment = $assignments_result->fetch_assoc()) : ?>
                        <tr bgcolor="grey">
                            <td style=><?php echo htmlspecialchars($assignment['intern']); ?></td>
                            <td style=><?php echo htmlspecialchars($assignment['supervisor']); ?></td>
                            <td>
                                <form action="assign_interns.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="assignment_id" value="<?php echo $assignment['id']; ?>">
                                    <button type="submit" name="delete">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>