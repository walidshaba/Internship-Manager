<?php
      session_start();
      include('../db_connect.php');
      if ($_SESSION['admin'] == "") {
        $que = mysqli_query($con, "select * from admin where  user_name='" . $_SESSION['admin'] . "'");
        $res = mysqli_fetch_array($que);
        $_SESSION = $res;
      } ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Internship Manager</title>
    <link rel="stylesheet" href="css/style.css" />
  </head>

  <body>
    <nav>
      <h1>Dashboard</h1>
      <div class="container">
        <div class="name-panel">
          <h2>
            admin
            <!-- <?php echo $_SESSION['admin'] ?> -->
          </h2>
          <div class="img">
            <h2><a href="">logout</a></h2>
          </div>
        </div>
        <div class="burger"></div>
        <ul class="menu">
          <li><a href="registerintern.php">Register Intern</a></li>
          <li><a href="registersupervisor.php">Register Supervisor </a></li>
          <li><a href="assignsupervisor.php">Assign Supervisor</a></li>
          <li><a href="registerorganization.php">Register Organization</a></li>
        </ul>
      </div>
    </nav>
  </body>
</html>