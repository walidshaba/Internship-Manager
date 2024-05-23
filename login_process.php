<?php
session_start();
include 'conn/db_connection.php';

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE username='$username'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])) {
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $row['role'];

        // Redirect based on user's role
        switch ($_SESSION['role']) {
            case 'intern':
                header("Location: intern/intern_dashboard.php");
                break;
            case 'supervisor':
                header("Location: supervisor/supervisor_dashboard.php");
                break;
            case 'admin':
                header("Location: admin/admin_panel.php");
                break;
            default:
                echo "Invalid role";
        }
    } else {

        echo "<script>
                    alert('Incorrect password');
                    window.location.href = 'login.php';

              </script>";
        exit();
    }
} else {
    echo "<script>
    alert('User not found!');
    window.location.href = 'login.php';
    </script>";
}


$conn->close();
