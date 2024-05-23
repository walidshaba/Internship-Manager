<?php
session_start();
require '../conn/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone_number = $_POST['phone_number'];

    $sql = "INSERT INTO users (username, email, password, role, first_name, last_name, phone_number) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $username, $email, $password, $role, $first_name, $last_name, $phone_number);

    if ($stmt->execute()) {
        $_SESSION['message'] = "User added successfully!";
        echo "<script>
        
                    alert('User added successfully!');
                    window.location.href = 'add_user.php';
           
              </script>";
    } else {
        $_SESSION['error'] = "Error adding user: " . $stmt->error;
        echo "<script>
                setTimeout(function() {
                    alert('" . $_SESSION['error'] . "');
                    window.location.href = 'add_user.php';
                }, 2000); // 2 seconds delay
              </script>";
    }
}
