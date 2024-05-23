<?php
session_start();
require '../conn/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $intern_id = $_POST['intern_id'];
    $supervisor_id = $_POST['supervisor_id'];

    $sql = "INSERT INTO assignments (intern_id, supervisor_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $intern_id, $supervisor_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Intern assigned to supervisor successfully!";
    } else {
        $_SESSION['error'] = "Error assigning intern: " . $stmt->error;
    }
    header("Location: assign_interns.php");
    exit();
}
