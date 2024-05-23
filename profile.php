<?php
session_start();
require 'conn/db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process the profile update form
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];

    // Update user data
    $sql_update = "UPDATE users SET first_name = ?, last_name = ?, email = ?, phone_number = ? WHERE user_id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssssi", $first_name, $last_name, $email, $phone_number, $user_id);

    if ($stmt_update->execute()) {
        $message = "Profile updated successfully!";
        // Update session email
        $_SESSION['email'] = $email;
    } else {
        $message = "Error updating profile: " . $stmt_update->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Management</title>
    <link rel="stylesheet" href="css/profile_styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/bc26123954.js" crossorigin="anonymous"></script>
</head>

<body>
    <nav>
        <a href="javascript:history.back()">
            <i class="fas fa-arrow-left"></i>
        </a>
    </nav>
    <div class="container">
        <h2>Manage Your Profile</h2>
        <?php if (isset($message)) {
            echo "<p class='message'>$message</p>";
        } ?>
        <form action="profile.php" method="post">
            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>

            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <label for="phone_number">Phone Number</label>
            <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>" required>

            <button type="submit">Update Profile</button>
        </form>
    </div>
</body>

</html>