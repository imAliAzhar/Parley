<?php

require_once('connectvars.php');

$error;

if(isset($_POST['submit']))
{
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('Error 01 : '. $dbc->connect_error);

    $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
    $password1= mysqli_real_escape_string($dbc,trim($_POST['password1']));
    $password2= mysqli_real_escape_string($dbc,trim($_POST['password2']));
    $firstname = mysqli_real_escape_string($dbc, trim($_POST['firstname']));
    $lastname = mysqli_real_escape_string($dbc, trim($_POST['lastname']));

    if(!empty($username) && !empty($password1) && !empty($password2)  && !empty($firstname)  && !empty($lastname) && ($password1 == $password2))
    {
        $query = "SELECT * FROM user WHERE username = '$username'";
        $data = mysqli_query($dbc, $query);

        if ($data->num_rows == 0)  
        {
            $query = "INSERT INTO user (username, password,firstname, lastname ) VALUES ('$username', SHA('$password1'), '$firstname', '$lastname')";
            mysqli_query($dbc,$query) or die('Error 02 : ' );
        
           // echo '<p>Your new account has been successfully created. You\'re now ready to <a href="login.php">log in</a>.</p>';
            $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/login.php';
            header('Location: ' . $home_url);

            mysqli_close($dbc);
        }
        else
        { 
            $error = "Username already exists!";                // automate signing in redirect to homepage
            $username ="";
        }
    }
    else 
    {
        $error = ("You must enter all of the sign-up data, including the desired password twice.");

    }
}

mysqli_close($dbc);

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
      <h2>Parley - Signup</h2>
    <form class="login-form" action="signup.php" method="post">
       <input type="text" placeholder="Username" name="username"/>
       <input type="text" placeholder="First Name" name="firstname"/>
       <input type="text" placeholder="Last Name" name="lastname"/>
      <input type="password" placeholder="Enter Password" name="password1"/>
      <input type="password" placeholder="Re-Enter Password" name="password2"/>
      <input type ="submit" id="but" style="background: #4CAF50;" name ="submit" value="Sign up"/>
    </form>
    <p class="message">Already registered? <a href="login.php">Sign In</a></p>
    <p class="message" style="color: red;"><?php if(isset($error)) echo $error; ?></p>
  </div>
</div>
  </body>
</html>








