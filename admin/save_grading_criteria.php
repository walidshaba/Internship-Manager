<?php
session_start();
require '../conn/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $criteria1 = $_POST['criteria1'];
    $criteria2 = $_POST['criteria2'];
    $criteria3 = $_POST['criteria3'];
    $admin_id = $_SESSION['user_id'];

    $sql = "UPDATE grading_criteria SET criteria1 = ?, criteria2 = ?, criteria3 = ? WHERE admin_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $criteria1, $criteria2, $criteria3, $admin_id);

    if ($stmt->execute()) {
        header("Location: grading_criteria.php?success=1");
        exit();
    } else {
        echo "Error updating grading criteria: " . $conn->error;
    }
}
