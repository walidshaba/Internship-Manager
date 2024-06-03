<?php
session_start();
require '../conn/db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $date = $_POST['date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $verification_method = $_POST['verification_method'];
    $photo = isset($_FILES['photo']) ? $_FILES['photo'] : null;

    // Start transaction
    $conn->begin_transaction();

    // Insert attendance record
    $stmt = $conn->prepare("INSERT INTO attendance (user_id, date, start_time, end_time, verification_method) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $user_id, $date, $start_time, $end_time, $verification_method);

    if ($stmt->execute()) {
        $attendance_id = $stmt->insert_id; // Get the auto-incremented attendance_id

        if ($verification_method == 'Photo' && $photo) {
            // Define the target directory and file path
            $target_dir = "../uploads/"; // Adjust path as needed
            $target_file = $target_dir . basename($photo["name"]);

            // Check if the directory exists
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            // Move the uploaded file to the target directory
            if (move_uploaded_file($photo["tmp_name"], $target_file)) {
                $photo_stmt = $conn->prepare("INSERT INTO photo_uploads (attendance_id, photo_path) VALUES (?, ?)");
                $photo_stmt->bind_param("is", $attendance_id, $target_file);

                if ($photo_stmt->execute()) {
                    echo "Attendance and photo uploaded successfully!";
                } else {
                    echo "Error uploading photo: " . $photo_stmt->error;
                    $conn->rollback();
                    exit();
                }
            } else {
                echo "Error moving uploaded file.";
                $conn->rollback();
                exit();
            }
        }
        $conn->commit();
    } else {
        echo "Error logging attendance: " . $stmt->error;
        $conn->rollback();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Attendance and Upload Photo</title>
    <link rel="stylesheet" href="../css/intern_dashboard_styles.css">
    <link rel="stylesheet" href="../css/log_attendance.css">
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
        <a href="submit_update.php">Submit Update</a>
        <a href="message_center.php">Message Center</a>
        <a href="../profile.php">Profile Management</a>
        <a href="log_attendance.php">Log Attendance</a>
        <a href="../logout.php">Logout</a>
    </nav>
    <main>
        <div class="container">
            <form action="log_attendance.php" method="post" enctype="multipart/form-data">
                <label for="date">Date:</label>
                <input type="date" id="date" name="date" required />

                <label for="start_time">Start Time:</label>
                <input type="time" id="start_time" name="start_time" required />

                <label for="end_time">End Time:</label>
                <input type="time" id="end_time" name="end_time" required />

                <label for="verification_method">Verification Method:</label>
                <select id="verification_method" name="verification_method" onchange="togglePhotoUpload(this.value)" required>
                    <option value="Photo">Photo Upload</option>
                    <option value="IP" disabled>IP Address</option>
                </select>

                <div id="photoUpload" style="display: none;">
                    <label for="photo">Upload Photo:</label>
                    <input type="file" id="photo" name="photo" accept="image/*" />
                </div>

                <button type="submit">Log Attendance & Upload Photo</button>
            </form>
        </div>
    </main>
    <script>
        function togglePhotoUpload(value) {
            const photoUpload = document.getElementById('photoUpload');
            photoUpload.style.display = value === 'Photo' ? 'block' : 'none';
        }

        // Ensure the correct state of photo upload field on page load
        document.addEventListener("DOMContentLoaded", function() {
            const verificationMethod = document.getElementById('verification_method').value;
            togglePhotoUpload(verificationMethod);
        });
    </script>
    <script src="../script/intern_dashboard.js"></script>
</body>

</html>