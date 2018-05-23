<?php
session_start();

require_once('connectvars.php');

  // If the session vars aren't set, try to set them with a cookie
  if (!isset($_SESSION['user_id'])) {
    if (isset($_COOKIE['user_id']) && isset($_COOKIE['username'])) {
      $_SESSION['user_id'] = $_COOKIE['user_id'];
      $_SESSION['username'] = $_COOKIE['username'];
    }
  }

$error = "";

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('Error 01 : '. $dbc->connect_error);


if(!isset($_SESSION['user_id']))
{      
    if(isset($_POST['submit']))
    {
        $username =mysqli_real_escape_string($dbc,trim($_POST['username']));
        $password =mysqli_real_escape_string($dbc,trim($_POST['password']));

        if (!empty($username) && !empty($password))
        {
            $query = "SELECT username , user_id FROM user where username = '$username' and password=SHA('$password')";
            $data = mysqli_query($dbc,$query);

            if($data->num_rows == 1) //valid username
            {
                $row =mysqli_fetch_array($data);
                $_SESSION['user_id'] =$row['user_id'];
                $_SESSION['username'] =$row['username'];
                setcookie('user_id', $row['user_id'], time() + (60 * 60 * 24 * 30));    // expires in 30 days
                setcookie('username', $row['username'], time() + (60 * 60 * 24 * 30));  // expires in 30 days
                $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
                header('Location: ' . $home_url);
            }
            else 
            {
                $error = 'Sorry this username has not been registered yet';
            }
        }
        else 
        {
            $error ="Please enter both username and password";
        }
    }
}
else
{
     $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
                header('Location: ' . $home_url);
}
?> 

<html lang="en">
  <head>
<title>Parley Signup</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8"/>
    <link rel="stylesheet" type="text/css" href="css/login.css">
  </head>
  <body>
      <div class="login-page">
  <div class="form">
      <h2>Parley - Login</h2>
    <form class="login-form" action="login.php" method="post">
       <input type="text" placeholder="Username" name="username"/>
      <input type="password" placeholder="Enter Password" name="password"/>
      <input type ="submit" id="but" style="background: #4CAF50;" name ="submit" value="Login"/>
    </form>
    <p class="message">Not registered? <a href="signup.php">Create an account</a></p>
    <p class="message" style="color: red;"><?php if(isset($error)) echo $error; ?></p>
  </div>
</div>
  </body>
</html>