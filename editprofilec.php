<?php
  session_start();

  // If the session vars aren't set, try to set them with a cookie
  if (!isset($_SESSION['user_id'])) {
    if (isset($_COOKIE['user_id']) && isset($_COOKIE['username'])) {
      $_SESSION['user_id'] = $_COOKIE['user_id'];
      $_SESSION['username'] = $_COOKIE['username'];
    }
  }
  
if(empty($_SESSION['user_id']))
{
  $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/login.php';
  header('Location: ' . $home_url);
}
    
?>

<?php
  require_once('appvars.php');
  require_once('connectvars.php');

  // Connect to the database
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('Cant connect to db');

  if (isset($_POST['submit'])) 
  {
    // Grab the profile data from the POST
    $firstname = mysqli_real_escape_string($dbc, trim($_POST['firstname']));
    $lastname = mysqli_real_escape_string($dbc, trim($_POST['lastname']));
    $gender = mysqli_real_escape_string($dbc, trim($_POST['gender']));
    $birthdate = mysqli_real_escape_string($dbc, trim($_POST['birthdate']));
    $city = mysqli_real_escape_string($dbc, trim($_POST['city']));
    $country = mysqli_real_escape_string($dbc, trim($_POST['country']));
    $old_picture = mysqli_real_escape_string($dbc, trim($_POST['old_picture']));
    $new_picture = mysqli_real_escape_string($dbc, trim($_FILES['new_picture']['name']));
    $new_picture_type = $_FILES['new_picture']['type'];
    $new_picture_size = $_FILES['new_picture']['size']; 
    list($new_picture_width, $new_picture_height) = getimagesize($_FILES['new_picture']['tmp_name']);
    $error = false;

    // Validate and move the uploaded picture file, if necessary
    if (!empty($new_picture)) {
      if ((($new_picture_type == 'image/gif') || ($new_picture_type == 'image/jpeg') || ($new_picture_type == 'image/pjpeg') ||
        ($new_picture_type == 'image/png')) && ($new_picture_size > 0) && ($new_picture_size <= MM_MAXFILESIZE) &&
        ($new_picture_width <= MM_MAXIMGWIDTH) && ($new_picture_height <= MM_MAXIMGHEIGHT)) {
        if ($_FILES['file']['error'] == 0) {
          // Move the file to the target upload folder
          $target = MM_UPLOADPATH . basename($new_picture);
          if (move_uploaded_file($_FILES['new_picture']['tmp_name'], $target)) {
            // The new picture file move was successful, now make sure any old picture is deleted
            if (!empty($old_picture) && ($old_picture != $new_picture)) {
              @unlink(MM_UPLOADPATH . $old_picture);
            }
          }
          else {
            // The new picture file move failed, so delete the temporary file and set the error flag
            @unlink($_FILES['new_picture']['tmp_name']);
            $error = true;
            echo '<p class="error">Sorry, there was a problem uploading your picture.</p>';
          }
        }
      }
      else {
        // The new picture file is not valid, so delete the temporary file and set the error flag
        @unlink($_FILES['new_picture']['tmp_name']);
        $error = true;
        echo '<p class="error">Your picture must be a GIF, JPEG, or PNG image file no greater than ' . (MM_MAXFILESIZE / 1024) .
          ' KB and ' . MM_MAXIMGWIDTH . 'x' . MM_MAXIMGHEIGHT . ' pixels in size.</p>';
      }
    }

    // Update the profile data in the database
    if (!$error) {
      if (!empty($firstname) && !empty($lastname) && !empty($gender) &&  !empty($city) && !empty($country)) {
        // Only set the picture column if there is a new picture
        if (!empty($new_picture)) {
          $query = "UPDATE user SET firstname = '$firstname', lastname = '$lastname', gender = '$gender', " .
            " birthdate = '$birthdate', city = '$city', country = '$country', picture = '$new_picture' WHERE user_id = '" . $_SESSION['user_id'] . "'";
        }
        else {
          $query = "UPDATE userr SET firstname = '$firstname', lastname = '$lastname', gender = '$gender', " .
            " birthdate = '$birthdate', city = '$city', country = '$country' WHERE user_id = '" . $_SESSION['user_id'] . "'";
        }
        mysqli_query($dbc, $query);

        // Confirm success with the user
        // echo '<p>Your profile has been successfully updated. Would you like to <a href="viewprofile.php">view your profile</a>?</p>';

        mysqli_close($dbc);
        exit();
      }
      else {
        $error = 'You must enter all of the profile data (the picture is optional)';
      }
    }
  } // End of check for form submission
  else {
    // Grab the profile data from the database
    $query = "SELECT * FROM user WHERE user_id = '" . $_SESSION['user_id'] . "'";
    $data = mysqli_query($dbc, $query) or die('error 01');
    $row = mysqli_fetch_array($data);

    if ($row != NULL) {
      $firstname = $row['firstname'];
      $lastname = $row['lastname'];
      $gender = $row['gender'];
      $birthdate = $row['birthdate'];
      $city = $row['city'];
      $country = $row['country'];
      $old_picture = $row['picture'];
    }
    else {
      $error = 'There was a problem accessing your profile';
    }
  }

  mysqli_close($dbc);

  if(empty($_SESSION['user_id']))
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
      <h2>Edit Profile</h2>
    <form class="login-form" action="editprofilec.php" method="post">
       <input type="text" placeholder="Enter First Name" id="firstname" name="firstname" value="<?php if (!empty($firstname)) echo $firstname; ?>" />
      <input type="text" placeholder="Enter Last Name" id="lastname" name="lastname" value="<?php if (!empty($lastname)) echo $lastname; ?>" />
      <!--<label for="gender">Select Gender: </label>-->
      <select id="gender" name="gender">
      <option value="M" <?php if (!empty($gender) && $gender == 'M') echo 'selected = "selected"'; ?>>Male</option>
      <option value="F" <?php if (!empty($gender) && $gender == 'F') echo 'selected = "selected"'; ?>>Female</option>
      </select>
      <input type="date" id="birthdate" name="birthdate" value="<?php if (!empty($birthdate)) echo $birthdate; ?>" />
      <input type="text" placeholder="Enter City" id="city" name="city" value="<?php if (!empty($city)) echo $city; ?>" />
      <input type="text" placeholder="Enter Country" id="country" name="country" value="<?php if (!empty($country)) echo $country; ?>" />
       <!--<label for="new_picture">Picture:</label>-->
      <input type="file" id="new_picture" name="new_picture" />
      <?php if (!empty($old_picture)) {
        echo '<img class="profile" src="' . MM_UPLOADPATH . $old_picture . '" alt="Profile Picture" />';
      } ?>
      

      <input type ="submit" id="but" style="background: #4CAF50;" name ="submit" value="Done"/>

    </form>
    <!--<p class="message">Already registered? <a href="login.php">Sign In</a></p>-->
    <p class="message" style="color: red;"><?php if(isset($error)) echo $error; ?></p>
  </div>
</div>
  </body>
</html>








