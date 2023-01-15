<?php
   include 'connection.php';
   if(isset($_SESSION['username'])){
      $userLoggedIn = $_SESSION['username'];
      $user_details_query = mysqli_query($conn,"SELECT * FROM social_media WHERE username ='$userLoggedIn'");
      $user = mysqli_fetch_array($user_details_query);
   }
   else{
    header("Location:http://localhost/SocialMedia/register.php");
   }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="top_bar">
      <div class="logo">
        <a href="index.php">Swirlfeed</a>
      </div>
      <nav>
      <a href="<?php echo $userLoggedIn;?>">
        <?php echo $user['first_name'];?>
      </a>
        <a href="#">Home</a>
        <a href="#">messeges</a>
        <a href="#">settings</a>
        <a href="logout.php">Logout</a>
      </nav>
    </div>
    <div class="wrapper">
 