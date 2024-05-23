<?php
session_start();
require 'conn/db_connection.php'; // Database connection

if (!isset($_SESSION['user_id'])) {
    echo "User not logged in.";
    exit();
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Delete the user account
    $sql = "DELETE FROM Users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        // Log the user out
        session_unset();
        session_destroy();
        header("Location: goodbye.php"); // Redirect to a goodbye page
        exit();
    } else {
        echo "Error deleting account.";
    }
}
