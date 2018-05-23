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

 require_once('connectvars.php');

 $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('Error 01 : '); ?>

<!DOCTYPE html>
<html>
<title>PARLEY</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
<link rel="stylesheet" href="http://www.w3schools.com/lib/w3-theme-blue-grey.css">
<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Open+Sans'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


<style>
html,body,h1,h2,h3,h4,h5 {font-family: "Open Sans", sans-serif}

</style>
<body class="w3-theme-l5">

<!-- Navbar -->
<div class="w3-top">
 <ul class="w3-navbar w3-theme-d2 w3-left-align w3-large">
  <li class="w3-hide-medium w3-hide-large w3-opennav w3-right">
    <a class="w3-padding-large w3-hover-white w3-large w3-theme-d2" href="javascript:void(0);" onclick="openNav()"><i class="fa fa-bars"></i></a>
  </li>
  <li><a href="#" class="w3-padding-large w3-theme-d4"><i class="fa fa-home w3-margin-right"></i>Parley</a></li>
  <li class="w3-hide-small"><a href="#" class="w3-padding-large w3-hover-white" title="News"><i class="fa fa-globe"></i></a></li>
  <li class="w3-hide-small"><a href="#" class="w3-padding-large w3-hover-white" title="Account Settings"><i class="fa fa-user"></i></a></li>
  <li class="w3-hide-small"><a href="#" class="w3-padding-large w3-hover-white" title="Messages"><i class="fa fa-envelope"></i></a></li>
  <li class="w3-hide-small w3-dropdown-hover">
    <a href="#" class="w3-padding-large w3-hover-white" title="Notifications"><i class="fa fa-bell"></i><span class="w3-badge w3-right w3-small w3-green">3</span></a>     
    <div class="w3-dropdown-content w3-white w3-card-4">
      <a href="#">Not configured yet</a>
      <a href="#">This one neither</a>
      <a href="#">Nor this one</a>
    </div>
  </li>
  <li class="w3-hide-small w3-right"><a href=" <?php if(isset($_SESSION['user_id'])) echo 'logout.php'; else  echo 'signup.php'?>" class="w3-padding-large w3-hover-white"><?php if(isset($_SESSION['user_id'])) echo 'Log out'; else  echo 'Sign up'?></li>
 </ul>
</div>

<!-- Navbar on small screens -->
<div id="navDemo" class="w3-hide w3-hide-large w3-hide-medium w3-top" style="margin-top:51px">
  <ul class="w3-navbar w3-left-align w3-large w3-theme">
    <li><a class="w3-padding-large" href="#">Link 1</a></li>
    <li><a class="w3-padding-large" href="#">Link 2</a></li>
    <li><a class="w3-padding-large" href="#">Link 3</a></li>
    <li><a class="w3-padding-large" href="#">My Profile</a></li>
  </ul>
</div>

<!-- Page Container -->
<div class="w3-container w3-content" style="max-width:1400px;margin-top:80px">    
  <!-- The Grid -->
  <div class="w3-row">
    <!-- Left Column -->
    <div class="w3-col m3">
      <!-- Profile -->
      <div class="w3-card-2 w3-round w3-white">
        <div class="w3-container">
         <h4 class="w3-center">My Profile</h4>
         <?php 

         if(isset($_SESSION['user_id']))
         {
             $user_id = $_SESSION['user_id'];
             $query = "SELECT * FROM user WHERE user_id = $user_id";
             $data = mysqli_query($dbc, $query);

             $row =mysqli_fetch_array($data);

             $username = $row['username'];
             $firstname = $row['firstname'];
             $lastname = $row['lastname'];
             $city = $row['city'];
             $country = $row['country'];
             $birthday = $row['joindate'];
             $picture = $row['picture'];
             $bio = $row['bio'];
         
         ?>
         <br>
         <p class="w3-center"><img src="images/alia.png" class="w3-circle" style="height:106px;width:106px" alt="Avatar"></p>
         <br>
         <h5 class="w3-center"><?php echo $firstname . ' ' . $lastname;  ?></h5>
         <hr>
         <p><i class="fa fa-home fa-fw w3-margin-right w3-text-theme"></i> <?php if(empty($city) || empty($country)) echo '{Not set}'; else echo $city . ', ' . $country;  ?></p>
         <p><i class="fa fa-birthday-cake fa-fw w3-margin-right w3-text-theme"></i><?php if(empty($birthday)) echo '{Not set}'; else echo $birthday;  ?> </p>
         <p><i class="fa fa-pencil fa-fw w3-margin-right w3-text-theme"></i> <?php if(empty($bio)) echo '{Not set}'; else echo $bio;  ?></p>
         <p><a class="w3-center" href="editprofile.php" style="text-decoration: none">Edit profile</a></p>
         <?php 
         }
         else
         {
           echo '<p class="w3-center" >Please <a href="login.php">login</a></p>';
         }
         ?>

        </div>
      </div>
      <br>
      
      <!-- Interests --> 
      <div class="w3-card-2 w3-round w3-white w3-hide-small">
        <div class="w3-container">
          <br>
          <p>Interests</p>
          <p>
            <span class="w3-tag w3-small w3-theme-d5">News</span>
            <span class="w3-tag w3-small w3-theme-d4">W3Schools</span>
            <span class="w3-tag w3-small w3-theme-d3">Labels</span>
            <span class="w3-tag w3-small w3-theme-d2">Games</span>
            <span class="w3-tag w3-small w3-theme-d1">Friends</span>
            <span class="w3-tag w3-small w3-theme">Games</span>
            <span class="w3-tag w3-small w3-theme-l1">Friends</span>
            <span class="w3-tag w3-small w3-theme-l2">Food</span>
            <span class="w3-tag w3-small w3-theme-l3">Design</span>
            <span class="w3-tag w3-small w3-theme-l4">Art</span>
            <span class="w3-tag w3-small w3-theme-l5">Photos</span>
          </p>
        </div>
      </div>
      <br>
      
      <!-- Alert Box -->
      <!--<div class="w3-container w3-round w3-theme-l4 w3-border w3-theme-border w3-margin-bottom w3-hide-small">
        <span onclick="this.parentElement.style.display='none'" class="w3-hover-text-grey w3-closebtn">
          <i class="fa fa-remove"></i>
        </span>
        <p><strong>Hey!</strong></p>
        <p>People are looking at your profile. Find out who.</p>
      </div>-->
    
    <!-- End Left Column -->
    </div>
    
    <!-- Middle Column -->
    <div class="w3-col m7">
    
      <div class="w3-row-padding">
        <div class="w3-col m12">
          <div class="w3-card-2 w3-round w3-white">
            <div class="w3-container w3-padding">
              <h6 class="w3-opacity">What's on your mind today?</h6>
          
              <form action="question.php" method="POST">
              <input class="w3-border w3-padding" style="width:650px;"name="question" placeholder="Ask your question" ></input><br><br>
               <label for="sel1">Select Topic:</label>
                  <select name="topic_id">
                  <?php
                      $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('Error 01 : '. $dbc->connect_error);

                      $query = "SELECT * FROM topic";
                      $data = mysqli_query($dbc, $query) or die('Error 03 : ');

                      while($row = mysqli_fetch_array($data))
                      {
                          echo '<option value="'.$row['topic_id'].'" >'.$row['topic_name'].'</option>';
                      }     
                  ?>
                  </select> <?php echo "&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp"; ?>
              <button type="submit" name="submit" class="w3-btn w3-theme"><i class="fa fa-pencil"></i> Ask</button> 
              </form>
 
            </div>
          </div>
        </div>
      </div>
      
      <?php
 if(isset($_SESSION['user_id']))
{
    //echo '<p>Ask a <a href="question.php">question</a></p><hr>';

    $query = "SELECT * FROM question"; // add a ranking parameter - Ayesha

    $questions_data = mysqli_query($dbc,$query) or die('Error 02 : '.$query.'<br>'.$dbc);

    if($questions_data->num_rows > 0)
    {
        while($row = mysqli_fetch_array($questions_data))
        {
            $current_question = $row['question_id']; // used later for $_GET
            $current_description =$row['q_description'];
            $current_user =$row['user_id'];

            // query to find topic
            $query_topic = "SELECT topic_name FROM topic WHERE topic_id IN ( SELECT topic_id FROM question WHERE question_id = '$current_question' )";
            $data_topic =  mysqli_query($dbc,$query_topic) or die('Error 22 : ');
            $row_topic = mysqli_fetch_array($data_topic);
            $topic_name = $row_topic['topic_name'];

            // query to find username
            $query_user = "SELECT username FROM user WHERE user_id IN (SELECT user_id FROM question WHERE question_id = '$current_question')";
            $data_user = mysqli_query($dbc,$query_user) or die('Error 23 : ');
            $row_user = mysqli_fetch_array($data_user);
            $user_name = $row_user['username'];

            // insert upvotes

            $query = "SELECT * FROM answer WHERE question_id = $current_question";  // add a ranking parameter
            $data = mysqli_query($dbc,$query) or die('Error 04 : ');

            $row = mysqli_fetch_array($data);

            $current_answer_id = $row['answer_id'];
            $current_answerer = $row['user_id'];
            $current_a_description = $row['a_description'];

            // insert username for answerer and upvotes

            if(!empty($row))
            {
                // query to find username
                  $query_user = "SELECT username FROM user WHERE user_id IN (SELECT user_id FROM answer WHERE answer_id = '$current_answer_id')";
                  $data_user = mysqli_query($dbc,$query_user) or die('Error 23 : ');
                  $row_user = mysqli_fetch_array($data_user);
                  $user_name = $row_user['username'];
             }
            else
                 $current_a_description =  'No answers yet.';
                ?>

        <div class="w3-container w3-card-2 w3-white w3-round w3-margin"><br>
        <img src="/w3images/avatar2.png" alt="Avatar" class="w3-left w3-circle w3-margin-right" style="width:60px">
        <span class="w3-right w3-opacity"><?php echo $topic_name; ?></span>  
        <h4><?php echo  $current_description; ?></h4> <?php echo $user_name;  ?>  <br>
        <hr class="w3-clear">
        <p><?php echo  $current_a_description; ?></p>
          <div class="w3-row-padding" style="margin:0 -16px">
           
        </div>



        <form action="vote.php" method="get" >
              <input type='hidden' name='question_id' value='<?php echo "$current_question";?>'/>
              
                  <?php
                      $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('Error 01 : '. $dbc->connect_error);
                      
                   $vote_query ="SELECT * FROM voting_question WHERE user_id ='$user_id' AND question_id = '$current_question' and vote ='1' ";
                   
                   if( $vote_data =mysqli_query($dbc,$vote_query))
                       { 
                          echo "connected";
                       }
                   else echo 'Error :'.$vote_query.'<br>'.$dbc;
// ?echo mysqli_num_rows($vote_data);
   
                    if($vote_data->num_rows == 0) 
                      {  ?> 
                    
                    <button type="submit" class="w3-btn w3-theme-d1 w3-margin-bottom"><i class="fa fa-thumbs-up"></i>Upvote</button> 
                    <?php } 
                     else 
                     {
                     ?>
                   <button type="submit" class="w3-btn w3-theme-d1 w3-margin-bottom"><i class="fa fa-thumbs-down"></i>Downote</button> 
                   <?php } ?>

        </form>



         
        <button type="button" class="w3-btn w3-theme-d2 w3-margin-bottom"><i class="fa fa-comment"></i> View All </button> 
      </div>

                <?php
                                          
        } // while - $row = mysqli_fetch_array($data)
        
    } // if - $data->num_rows > 0
  

} // if - isset($_SESSION['user_id']
      ?>    
    <!-- End Middle Column -->
    </div>
    
    <!-- Right Column -->
    <div class="w3-col m2">
      <div class="w3-card-2 w3-round w3-white w3-center">
        <div class="w3-container">
          <p>Upcoming Events:</p>
          <img src="/w3images/forest.jpg" alt="Forest" style="width:100%;">
          <p><strong>Holiday</strong></p>
          <p>Friday 15:00</p>
          <p><button class="w3-btn w3-btn-block w3-theme-l4">Info</button></p>
        </div>
      </div>
      <br>
      
      <div class="w3-card-2 w3-round w3-white w3-center">
        <div class="w3-container">
          <p>Friend Request</p>
          <img src="/w3images/avatar6.png" alt="Avatar" style="width:50%"><br>
          <span>Jane Doe</span>
          <div class="w3-row w3-opacity">
            <div class="w3-half">
              <button class="w3-btn w3-green w3-btn-block w3-section" title="Accept"><i class="fa fa-check"></i></button>
            </div>
            <div class="w3-half">
              <button class="w3-btn w3-red w3-btn-block w3-section" title="Decline"><i class="fa fa-remove"></i></button>
            </div>
          </div>
        </div>
      </div>
      <br>
      
      <div class="w3-card-2 w3-round w3-white w3-padding-16 w3-center">
        <p>ADS</p>
      </div>
      <br>
      
      <div class="w3-card-2 w3-round w3-white w3-padding-32 w3-center">
        <p><i class="fa fa-bug w3-xxlarge"></i></p>
      </div>
      
    <!-- End Right Column -->
    </div>
    
  <!-- End Grid -->
  </div>
  
<!-- End Page Container -->
</div>
<br>

<!-- Footer -->
<footer class="w3-container w3-theme-d3 w3-padding-16">
  <h5>Footer</h5>
</footer>

<footer class="w3-container w3-theme-d5">
  <p>Powered by <a href="http://www.w3schools.com/w3css/default.asp" target="_blank">w3.css</a></p>
</footer>
 
<script>
// Accordion
function myFunction(id) {
    var x = document.getElementById(id);
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
        x.previousElementSibling.className += " w3-theme-d1";
    } else { 
        x.className = x.className.replace("w3-show", "");
        x.previousElementSibling.className = 
        x.previousElementSibling.className.replace(" w3-theme-d1", "");
    }
}

// Used to toggle the menu on smaller screens when clicking on the menu button
function openNav() {
    var x = document.getElementById("navDemo");
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
    } else { 
        x.className = x.className.replace(" w3-show", "");
    }
}

</script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html> 




