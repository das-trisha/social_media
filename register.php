<?php

    //To store the variables values even it doesn't get validated
    
   include 'connection.php';
   include 'login_handler.php';
   
    //Declaring Variables To Prevent Errors
    $fname =""; //Firstname
    $lname ="";  //Lastname
    $em = "";    //email
    $em2 = "";    //confirm email
    $password = ""; //password
    $password2 = ""; //confirm password
    $date = ""; //Sign up date
    $error_array = array(); //Holds error messeges

    if(isset($_POST['register_button'])){
        $fname = strip_tags($_POST['reg_fname']); // strips out all html & php tags from a given string
        $fname = str_replace(' ','',$fname); //remove whitespaces
        $fname = ucfirst(strtolower($fname)); //makes the first letter in uc and rest in lc
        $_SESSION['reg_fname'] = $fname; //store the first name
    
        $lname = strip_tags($_POST['reg_lname']); // strips out all html & php tags from a given string
        $lname = str_replace(' ','',$lname); //remove whitespaces
        $lname = ucfirst(strtolower($lname)); //makes the first letter in uc and rest in lc
        $_SESSION['reg_lname'] = $lname; //store the last name

        $em = strip_tags($_POST['reg_email']); // strips out all html & php tags from a given string
        $em = str_replace(' ','',$em); //remove whitespaces
        $em = ucfirst(strtolower($em)); //makes the first letter in uc and rest in lc
        $_SESSION['reg_email'] = $em; //store the email
    
        $em2 = strip_tags($_POST['reg_email2']); // strips out all html & php tags from a given string
        $em2 = str_replace(' ','',$em2); //remove whitespaces
        $em2 = ucfirst(strtolower($em2)); //makes the first letter in uc and rest in lc
        $_SESSION['reg_email2'] = $em2; //store the email2

        $password = strip_tags($_POST['reg_password']); // strips out all html & php tags from a given string

        $password2 = strip_tags($_POST['reg_password2']); // strips out all html & php tags from a given string

        $date = ("y-m-d"); //current date
        
        if($em == $em2){
            //check if email is in valid format
            if(filter_var($em,FILTER_VALIDATE_EMAIL)){
               $em = filter_var($em,FILTER_VALIDATE_EMAIL);
                //check if email already exits
                $e_check = mysqli_query($conn,"SELECT email from social_media WHERE email='$em'");
                //print_r($e_check);
                $num_rows = mysqli_num_rows($e_check);
                //print_r($num_rows);
                if($num_rows > 0){
                    array_push($error_array,"Email alraedy in use!");
                }
         }else{
            array_push($error_array,"Invalid Format");
        }
    } 
        else{
            array_push($error_array,"Emails Don't Match");
        }
        if(strlen($fname)>25 || strlen($fname)<2){
            array_push($error_array,"Your firstname should be between 3 and 25.");
        }
        if(strlen($lname)>25 || strlen($lname)<2){
            array_push($error_array,"Your lastname should be between 3 and 25.");
        }
        if(strlen($password)>30 || strlen($password)<5){
            array_push($error_array,"Your password should be between 5 and 30.");
        }
        if($password != $password2){
            array_push($error_array,"Password doesn't match");
        }else if(preg_match('/[^A-Za-z0-9)]/',$password)){
            array_push($error_array, "Your pass will contain only english letters or numbers!");
            }
    if(empty($error_array)){
         $password = md5($password); //Encrypt pass before sending to db
        //Generating username by concatenating first name & lastname
        $username = $fname."_".$lname;
        $check_username_query = (mysqli_query($conn,"SELECT username FROM social_media WHERE username ='$username'"));
        $i=0;
        //if username exits add number to username
        while(mysqli_num_rows($check_username_query) != 0){
            $i++;
            $username =  $username ."_". $i;
            $check_username_query = mysqli_query($conn,"SELECT username FROM social_media WHERE username ='$username'");
        }   
            //profile picture assignment
            $rand = rand(1,2);
            if($rand == 1)
            $profile_pic = "assets/images/profile_pics/defaults/head_deep_blue.png";
            else if( $rand == 2)
            $profile_pic = "assets/images/profile_pics/defaults/head_turqoise.png";
            $query =  mysqli_query($conn,"INSERT INTO social_media values('','$fname','$lname','$username','$em','$password','$date','$profile_pic','0','0','no',',') ");
         array_push($error_array,"<span style = 'color:#14C800;'>You are all set! Go ahead</span>");
        //clear session variables
        $_SESSION['reg_fname'] = "";
        $_SESSION['reg_lname'] = "";
        $_SESSION['reg_email'] = "";
        $_SESSION['reg_email2'] = "";
    }
   
}
      
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="assets/css/register_style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="assets/js/register.js"></script>
</head>
<body>
    <?php
      if(isset($_POST['register_button'])){
        echo'
        <script>
        $(document).ready(function(){
            $("#first").hide();
            $("#second").show();
        });
        </script>
        ';
      }
      
    ?>
    <div class="wrapper">
        <div class="login_box">
            <div class="login_header">
                <h1>Swirlfeed!</h1>
                Login or Signup below!
            </div>
            <div class="first">
                <form action="register.php" method="POST">
                    <input type="email" name="log_email" placeholder="Email Address" value="<?php if(isset($_SESSION['log_email']))
                    {
                        echo $_SESSION['log_email'];
                    }?>" required><br>
                    <input type="password" name="log_password" placeholder="Log Password"><br>
                    <input type="submit" name="login_button"value="Login"><br>
                    <?php if(in_array("Email or Password was incorrect!", $error_array))echo "Email or Password was incorrect!";?>
                    <a href="#" id="signup" class="signup">Need an account? Register Here!</a>
                </form>
            </div>
            <div class="second">
                <form action="register.php" method="POST">
                    <input type="text" name="reg_fname" placeholder="Firstname" value="<?php if(isset($_SESSION['reg_fname']))
                    {
                        echo $_SESSION['reg_fname'];
                    }?>" required>
                    <br>
                    <?php if(in_array("Your firstname should be between 3 and 25.", $error_array))echo "Your firstname should be between 3 and 25";?>
                    <input type="text" name="reg_lname" placeholder="Lastname" value="<?php if(isset($_SESSION['reg_lname']))
                    {
                        echo $_SESSION['reg_lname'];
                    }?>" required>
                    <br>
                    <?php if(in_array("Your lastname should be between 3 and 25.",$error_array)) echo "Your lastname should be between 3 and 25.";?>
                    <input type="email" name="reg_email" placeholder="Email" value="<?php if(isset($_SESSION['reg_email']))
                    {
                        echo $_SESSION['reg_email'];
                    }?>" required>
                    <br>
                    <input type="email" name="reg_email2" placeholder="Confirm Email" value="<?php if(isset($_SESSION['reg_email2']))
                    {
                        echo $_SESSION['reg_email2'];
                    }?>" required>
                    <br>
                    <?php if(in_array("Email alraedy in use!",$error_array)) echo "Email alraedy in use!";
                    else if(in_array("Invalid Format",$error_array)) echo "Invalid Format";
                    else if(in_array("Emails Don't Match",$error_array)) echo "Emails Don't Match";?>
                    <input type="password" name="reg_password" placeholder="Password" required>
                    <br>
                    <input type="password" name="reg_password2" placeholder="Confirm Password" required>
                    <br>
                    <?php if(in_array("Your password should be between 5 and 30.",$error_array)) echo "Your password should be between 5 and 30.";
                    else if(in_array("Password doesn't match",$error_array)) echo "Password doesn't match";
                    else if(in_array("Your password should be between 5 and 30.",$error_array)) echo "Your pass will contain only english letters or numbers!";?>
                    <input type="submit" name="register_button" value="Register" required><br>
                    <?php if(in_array("<span style = 'color:#14C800;'>You are all set! Go ahead</span>",$error_array)) echo "<span style = 'color:#14C800;'>You are all set! Go ahead</span>";?>
                    <a href="#" id="signin" class="signin">Already have an account? Sign in Here!</a>
                </form>     
            </div>
            </div>
    </div>
</body>
</html>