<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['username']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit;
}

// Include database connection
require_once "db_connect.php";

// Fetch list of interns and supervisors
$sql = "SELECT * FROM users WHERE is_admin = 0";
$result = $conn->query($sql);
$users = $result->fetch_all(MYSQLI_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $intern_id = $_POST["intern_id"];
    $supervisor_id = $_POST["supervisor_id"];

    // Assign supervisor to intern
    $sql = "INSERT INTO interns (user_id, supervisor_id) VALUES ('$intern_id', '$supervisor_id')";
    if ($conn->query($sql) === TRUE) {
        echo "Supervisor assigned successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Assign Supervisor</title>
</head>

<body>
    <h2>Assign Supervisor to Intern</h2>
    <form method="post">
        <label for="intern_id">Select Intern:</label>
        <select name="intern_id" id="intern_id">
            <?php foreach ($users as $user) : ?>
                <option value="<?php echo $user['id']; ?>"><?php echo $user['username']; ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>
        <label for="supervisor_id">Select Supervisor:</label>
        <select name="supervisor_id" id="supervisor_id">
            <?php foreach ($users as $user) : ?>
                <option value="<?php echo $user['id']; ?>"><?php echo $user['username']; ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>
        <input type="submit" value="Assign Supervisor">
    </form>
</body>

</html>