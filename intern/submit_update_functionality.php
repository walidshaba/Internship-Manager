<?php
session_start();
require '../conn/db_connection.php';

// Check if the user is logged in and is an intern
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'intern') {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['logbook'])) {
    $user_id = $_SESSION['user_id'];
    $subject = $_POST['subject'];
    $logbook = $_FILES['logbook'];

    // Define the target directory and file path
    $target_dir = "../logbook_uploads/";
    $target_file = $target_dir . basename($logbook["name"]);

    // Check if the directory exists
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Move the uploaded file to the target directory
    if (move_uploaded_file($logbook["tmp_name"], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO logbook_submissions (user_id, subject, logbook_path) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $subject, $target_file);

        if ($stmt->execute()) {
            header('Location: submit_update.php?success=1');
        } else {
            header('Location: submit_update.php?error=' . urlencode($stmt->error));
        }
    } else {
        header('Location: submit_update.php?error=' . urlencode('Error moving uploaded file.'));
    }
} else {
    header('Location: submit_update.php');
}
