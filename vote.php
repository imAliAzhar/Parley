<?php 
session_start();

 // If the session vars aren't set, try to set them with a cookie
if (!isset($_SESSION['user_id'])) 
{
  if (isset($_COOKIE['user_id']) && isset($_COOKIE['username'])) 
  {
    $_SESSION['user_id'] = $_COOKIE['user_id'];
    $_SESSION['username'] = $_COOKIE['username'];
  }
}

require_once('connectvars.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('Error 01 : '. $dbc->connect_error);

$question_id = $_GET['question_id'];
$user_id = $_SESSION['user_id'];
$answer_id =$_GET['answer_id'];

if(isset($_SESSION['user_id']))
{
    if(!empty($_GET['question_id']))
    {
        

        $query_vote = "SELECT vote FROM voting_question WHERE question_id = $question_id AND user_id = '$user_id'";
        $data_vote = mysqli_query($dbc,$query_vote) or die('Error 02 : ');
        $row_vote =  mysqli_fetch_array($data_vote);
        
        $vote = $row_vote['vote'];
        

        if(!isset($row_vote['vote']))
        {
             $query = "INSERT INTO voting_question VALUES('$user_id', '$question_id', '1')";
             echo"hey";
            mysqli_query($dbc,$query);

        }
            
        else if($vote == 1)
        {
             $query = "UPDATE voting_question SET vote = -1 WHERE question_id = $question_id AND user_id = '$user_id'";
             mysqli_query($dbc,$query) or die('Error 3 : ');
            echo 'downvoted';
        }
         else if($vote == -1)
        {
             $query = "UPDATE voting_question SET vote = 1 WHERE question_id = $question_id AND user_id = '$user_id'";
             mysqli_query($dbc,$query) or die('Error 3 : ');
             echo 'upvoted';
        }
         $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
    header('Location: ' . $home_url);

    }
    else if(!empty($_GET['answer_id']))
    {

    
        $query_vote = "SELECT vote FROM voting_answer WHERE answer_id = $answer_id AND user_id = '$user_id'";
        $data_vote = mysqli_query($dbc,$query_vote) or die('Error 02 : ');
        $row_vote =  mysqli_fetch_array($data_vote);
        
        $vote = $row_vote['vote'];
        

        if(!isset($row_vote['vote']))
        {
             $query = "INSERT INTO voting_answer VALUES('$user_id', '$answer_id', '1')";
             
            mysqli_query($dbc,$query);

        }
            
        else if($vote == 1)
        {
             $query = "UPDATE voting_answer SET vote = -1 WHERE answer_id = $answer_id AND user_id = '$user_id'";
             mysqli_query($dbc,$query) or die('Error 3 : ');
            echo 'downvoted';
        }
         else if($vote == -1)
        {
             $query = "UPDATE voting_answer SET vote = 1 WHERE answer_id = $answer_id AND user_id = '$user_id'";
             mysqli_query($dbc,$query) or die('Error 3 : ');
             echo 'upvoted';
        }

    }
}
