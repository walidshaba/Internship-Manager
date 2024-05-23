<?php
session_start();
require '../conn/db_connection.php';

// Check if the user is logged in and is an intern
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'intern') {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submission_id'])) {
    $submission_id = $_POST['submission_id'];

    // Get the logbook path to delete the file
    $stmt = $conn->prepare("SELECT logbook_path FROM logbook_submissions WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $submission_id, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        // Delete the file from the server
        if (file_exists($row['logbook_path'])) {
            unlink($row['logbook_path']);
        }

        // Delete the record from the database
        $stmt = $conn->prepare("DELETE FROM logbook_submissions WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $submission_id, $_SESSION['user_id']);

        if ($stmt->execute()) {
            header('Location: submit_update.php?success=1');
        } else {
            header('Location: submit_update.php?error=' . urlencode($stmt->error));
        }
    } else {
        header('Location: submit_update.php?error=' . urlencode('Submission not found.'));
    }
} else {
    header('Location: submit_update.php');
}
