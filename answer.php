<?php
session_start();
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
   
require_once('connectvars.php');

if(isset($_POST['submit']))
{
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('Error 01 : '. $dbc->connect_error);

    $question_id = $_POST['question_id']; //check if you need to send it again when POSTing
    $question_id = (int)$question_id;  // remove late
    $a_description = mysqli_real_escape_string($dbc, trim($_POST['answer']));
    $user_id = $_SESSION['user_id'];

    $query = "SELECT * FROM answer WHERE user_id = '$user_id' AND question_id = '$question_id'";
    $data = mysqli_query($dbc, $query) or die('Error 22 : ');

    echo $question_id;

    if($data->num_rows != 0)
    {
         $query = "UPDATE answer SET a_description='$a_description' WHERE user_id='$user_id'";

         mysqli_query($dbc, $query) or die('Error 02 : ');

        // echo 'Answer editted successfully';
    }
    else
    {
        $query = "INSERT INTO answer(user_id, question_id, a_description) VALUES('$user_id', '$question_id','$a_description')";

        mysqli_query($dbc, $query) or die('Error 02 : ');

        // echo 'New answer written successfully';
    }
 
 $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/viewanswer.php?question_id='.$question_id;
  header('Location: ' . $home_url);
   
    // echo 'Goto <a href="index.php">home page</a>';
}
// else
// {
?>
<?php 
    // $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('Error 03 : '. $dbc->connect_error);
    // $question_id = $_GET['question_id'];
    // $question_id = (int)$question_id;  // remove later

    // $query = "SELECT * FROM question WHERE question_id='$question_id'";
    // $data = mysqli_query($dbc, $query) or die('Error 03 : ');
    // $row =  mysqli_fetch_array($data);

    // echo '<b>Question:</b> ' . $row['q_description'] . '&nbsp <b>Asked by</b> ' . $row['user_id'] . '<br>'; // add username, topic
?>

<!--<form id="ask_answer" method ="post"  action ="<?php// echo $_POST['PHP_SELF'];?>">
    <fieldset>
        <legend>"<?php //echo $_SESSION['username'];?>" gives Answer</legend>
        <lable for="answer"> Answer:</label>-->

<?php 
// $current_user =  $_SESSION['user_id'];
// $query = "SELECT * FROM answer WHERE user_id = '$current_user' AND question_id = '$question_id'";
// $data = mysqli_query($dbc, $query) or die('Error 04 : ');
// $row =  mysqli_fetch_array($data);
// $answer_sticky_text = $row['a_description'];
?>
        <!--<textarea rows="4" cols="50" name="answer"id="answer" form="ask_answer" >
            <?php // echo $answer_sticky_text;  ?>
        </textarea>
    </fieldset>
    <input type ="submit" name ="submit"/>
</form>-->

<?php
// }
?>