<?php
session_start();
require '../conn/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];

    $sql = "DELETE FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "User added successfully!";
        echo "<script>
        
                    alert('User Deleted');
                    window.location.href = 'delete_user.php';
           
              </script>";
    } else {
        $_SESSION['error'] = "Error adding user: " . $stmt->error;
        echo "<script>
                setTimeout(function() {
                    alert('" . $_SESSION['error'] . "');
                    window.location.href = 'delete_user.php';
                }, 2000); // 2 seconds delay
              </script>";
    }
    exit();
}
