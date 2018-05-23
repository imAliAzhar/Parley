<?php 
session_start();

require_once('connectvars.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('Error 01 : '. $dbc->connect_error);


if(isset($_POST['submit']))
{
    $topic = $_POST['topic'];

    $query = "SELECT * FROM topic WHERE topic_name = '$topic'";
    $data = mysqli_query($dbc, $query) or die('Error 02 : ');

    if($data->num_rows == 0)
    {
         $query = "INSERT INTO topic(topic_name) VALUES('$topic')";
         mysqli_query($dbc, $query) or die('Error 02 : ');

         echo '<p>Topic created successfully. Ask a <a href="question.php">question</a>.</p>';
    }
    else
         echo '<p>Topic already exists. Ask a <a href="question.php">question</a>.</p>';  
}
else
{
?>
<html>
<head>
  <title>Question</title>
</head>
<body>
<form  method ="post"  action ="<?php echo $_POST['PHP_SELF'];?>">
<lable for "question"> Topic:</label>
<input type ="text" id="topic" name ="topic"/><br/>
<input type ="submit" name ="submit"/>
</form>
</body>
</html>

<?php
}
?>