<?php
session_start();
require '../conn/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone_number = $_POST['phone_number'];

    $sql = "UPDATE users SET username = ?, email = ?, role = ?, first_name = ?, last_name = ?, phone_number = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $username, $email, $role, $first_name, $last_name, $phone_number, $user_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "User added successfully!";
        echo "<script>
        
                    alert('Changes Updated');
                    window.location.href = 'edit_user.php';
           
              </script>";
    } else {
        $_SESSION['error'] = "Error adding user: " . $stmt->error;
        echo "<script>
                setTimeout(function() {
                    alert('" . $_SESSION['error'] . "');
                    window.location.href = 'edit_user.php';
                }, 2000); // 2 seconds delay
              </script>";
    }
    exit();
}
