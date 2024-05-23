<?php
session_start();
require '../conn/db_connection.php';

// Logic for updating system settings

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Example setting: Notification preferences
    $notification_pref = $_POST['notification_pref'];

    // Update system settings in the database
    $sql = "UPDATE system_settings SET notification_pref = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $notification_pref);

    if ($stmt->execute()) {
        echo "System settings updated successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Retrieve current system settings
$sql = "SELECT * FROM system_settings";
$result = $conn->query($sql);
$settings = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Settings</title>
    <link rel="stylesheet" href="../css/system_settings_styles.css">
</head>

<body>
    <div class="container">
        <h2>System Settings</h2>
        <form action="system_settings.php" method="post">
            <label for="notification_pref">Notification Preferences</label>
            <input type="text" id="notification_pref" name="notification_pref" value="<?php echo htmlspecialchars($settings['notification_pref']); ?>" required>

            <button type="submit">Update Settings</button>
        </form>
    </div>
</body>

</html>