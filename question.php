<?php 
session_start();
?>

<?php
require_once('connectvars.php');

if(isset($_POST['submit']))
{
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('Error 01 : '. $dbc->connect_error);

    $q_description = mysqli_real_escape_string($dbc, trim($_POST['question']));
    $q_description = ucfirst($q_description);
    $topic_id = $_POST['topic_id'];
    $user_id = $_SESSION['user_id'];

    echo $q_description;

    if(!(substr($q_description, -1) == '?'))
    {
        $q_description = $q_description . '?';
    }

    $query = "INSERT INTO question(user_id, topic_id, q_description) VALUES('$user_id', '$topic_id','$q_description')";
    mysqli_query($dbc, $query) or die('Error 02 : ');

    $query = "SELECT question_id FROM question WHERE q_description = '$q_description'";
    $row = mysqli_query($dbc, $query) or die('Error 02 : ');
    

}


   $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
   header('Location: ' . $home_url);

?>