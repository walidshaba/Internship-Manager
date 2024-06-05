<?php
session_start();
require '../conn/db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Not logged in";
    exit();
}

// Check if the necessary POST variables are set
if (!isset($_POST['receiver_id']) || !isset($_POST['message'])) {
    echo "Invalid request";
    exit();
}

$sender_id = $_SESSION['user_id'];
$receiver_id = $_POST['receiver_id'];
$message = $_POST['message'];
$timestamp = date("Y-m-d H:i:s");

// Insert the message into the database
$stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message, timestamp) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiss", $sender_id, $receiver_id, $message, $timestamp);

if ($stmt->execute()) {
    echo "Message sent successfully";
} else {
    echo "Error sending message: " . $stmt->error;
}
