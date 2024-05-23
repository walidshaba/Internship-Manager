<?php
session_start();
require '../conn/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];

    $sql = "UPDATE users SET email = ?, phone_number = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $email, $phone_number, $user_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Contact information updated successfully!";
    } else {
        $_SESSION['error'] = "Error updating contact information: " . $stmt->error;
    }
    header("Location: update_contact_info.php");
    exit();
}
