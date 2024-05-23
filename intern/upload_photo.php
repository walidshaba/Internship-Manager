<?php
session_start();
require '../conn/db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    $attendance_id = $_POST['attendance_id'];
    $photo = $_FILES['photo'];

    // Define the target directory and file path
    $target_dir = "../uploads/"; // Adjust path as needed
    $target_file = $target_dir . basename($photo["name"]);

    // Check if the directory exists
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Move the uploaded file to the target directory
    if (move_uploaded_file($photo["tmp_name"], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO photo_uploads (attendance_id, photo_path) VALUES (?, ?)");
        $stmt->bind_param("is", $attendance_id, $target_file);

        if ($stmt->execute()) {
            echo "Photo uploaded successfully!";
        } else {
            echo "Error uploading photo: " . $stmt->error;
        }
    } else {
        echo "Error moving uploaded file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Photo</title>
    <link rel="stylesheet" href="../css/intern_dashboard_styles.css">
    <link rel="stylesheet" href="../css/upload_photo.css">
</head>

<body>
    <header>
        <a href="intern_dashboard.php" style="color: #fff; text-decoration:none;">
            <div class="logo">Intern Dashboard</div>
        </a>
        <div class="welcome">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></div>
        <div class="burger" id="burger">&#9776;</div>
    </header>
    <nav class="left-navbar" id="left-navbar">
        <!-- <a href="intern_overview.php">Overview</a> -->
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
            <form action="upload_photo.php" method="post" enctype="multipart/form-data">
                <label for="attendance_id">Attendance ID:</label>
                <input type="number" id="attendance_id" name="attendance_id" required />

                <label for="photo">Upload Photo:</label>
                <input type="file" id="photo" name="photo" accept="image/*" required />

                <button type="submit">Upload Photo</button>
            </form>
        </div>
    </main>
    <script src="../script/intern_dashboard.js"></script>
</body>

</html>