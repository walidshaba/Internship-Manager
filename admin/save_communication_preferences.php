<?php
session_start();
require '../conn/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email_notifications = isset($_POST['email_notifications']) ? 1 : 0;
    $sms_notifications = isset($_POST['sms_notifications']) ? 1 : 0;
    $notification_frequency = $_POST['notification_frequency'];
    $admin_id = $_SESSION['user_id'];

    $sql = "UPDATE users SET email_notifications = ?, sms_notifications = ?, notification_frequency = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisi", $email_notifications, $sms_notifications, $notification_frequency, $admin_id);

    if ($stmt->execute()) {
        header("Location: communication_preferences.php?success=1");
        exit();
    } else {
        echo "Error updating communication preferences: " . $conn->error;
    }
}
