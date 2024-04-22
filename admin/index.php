<?php
session_start();
require('../db_connect.php');
?>


<?php
if (isset($_POST['submit'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $sql = "SELECT * FROM admin WHERE user_name='$username' AND password='$password'";
  $que = mysqli_query($conn, $sql);

  if (mysqli_num_rows($que) === 1) {
    $row = mysqli_fetch_assoc($que);
    if ($row['user_name'] === $username && $row['password'] === $password) {
      $_SESSION['admin'] = $row['user_name'];
      header('location:admindashboard.php');
    } else {
      $err = "<font color='red'>Invalid login details</font>";
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Internship Manager</title>
</head>

<body>
  <nav>
    <h1>Internship Manager</h1>
  </nav>
  <form action="index.php" method="post">
    <div class="input-box">
      <label for="">Username</label>
      <input type="text" placeholder="Username" name="username" required />
    </div>
    <div class="input-box">
      <label for="">Password</label>
      <input type="password" placeholder="assword" name="password" required />
    </div>
    <div class="submit-botton">
      <input type="submit" value="Login" name="submit" />
    </div>
  </form>
</body>

</html>