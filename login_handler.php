<?php
//session_start();
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
    if(isset($_POST['login_button'])){
     $error_array = array();
     $email = filter_var($_POST['log_email'], FILTER_SANITIZE_EMAIL); //sanitize email
     $_SESSION['log_email'] = $email; //store email
     $password = md5($_POST['log_password']); //get password
     $check_database_query = mysqli_query($conn,"SELECT *  from social_media WHERE email= '$email' AND password ='$password'");
     $check_login_query = mysqli_num_rows($check_database_query);
        if($check_login_query == 1){
            $row = mysqli_fetch_array($check_database_query);
            $username = $row['username'];
            $user_closed_query = mysqli_query($conn,"SELECT * from social_media WHERE email= '$email' AND user_closed ='yes'");
            $check_login_query = mysqli_num_rows($user_closed_query);
            if($user_closed_query == 1){
                $reopen_account =  mysqli_query($conn,"UPDATE social_media SET user_closed ='no' WHERE email= '$email'");
            }
            //username is getting stored here for index page
            $_SESSION['username'] = $username;

            header("Location:http://localhost/SocialMedia/index.php");
        }else{
            array_push($error_array,"Email or Password was incorrect!");
        }
    }
?>     