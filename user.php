<?php
        class User{
        private $con;
        private $user;
            public function __construct($con, $user)
            {
                $this->con = $con;
                $user_details_query = mysqli_query($con,"SELECT * FROM social_media WHERE username = '$user'");
                $this->user = mysqli_fetch_array($user_details_query);
            }
            
            public function getUsername(){
                return  $this->user['username'];
            }
            public function getNumposts(){
                $username = $this->user['username'];
                $query = mysqli_query($this->con,"SELECT num_posts FROM social_media WHERE username = '$username'");
                $row = mysqli_fetch_array($query);
                return  $row['num_posts'];
            }   
            public function getFirstAndLastName(){
                if(isset($this->user['username'])){
                    $username = $this->user['username'];
                    $query = mysqli_query($this->con,"SELECT first_name,last_name FROM social_media WHERE username = '$username'");
                    $row = mysqli_fetch_array($query);
                    return  $row['first_name'] ." ".$row['last_name'];
                }
            }
            public function getProfilepic(){
                if(isset($this->user['username'])){
                    $username = $this->user['username'];
                    $query = mysqli_query($this->con,"SELECT profile_pic FROM social_media WHERE username = '$username'");
                    $row = mysqli_fetch_array($query);
                    return  $row['profile_pic'];
                }
            }
            public function isClosed(){
                $username = $this->user['username'];
                if(isset($username)){
                    $query = mysqli_query($this->con,"SELECT user_closed FROM social_media WHERE username = '$username'");
                    $row = mysqli_fetch_array($query);
                    if($row['user_closed'] == 'yes'){
                        return true;
                    }else{
                        return false;
                    }
                }    
            }
            public function isFriend($user_to_check){
                $usernameComma = ",".$user_to_check.",";
                //echo $user_to_check;
                if((strstr($this->user['friend_array'],$usernameComma) ||$user_to_check == $this->user['username'] )){
                    return true;
                }else{
                    return false;
                }
            }
            public function didRecieveRequest($user_to){
                if(isset($this->user['username'])){
                    $user_from = $this->user['username'];
                    $check_request_query = mysqli_query($this->con,"SELECT * FROM friend_requests WHERE user_to = '$user_to' AND user_from ='$user_from'");
                    if(mysqli_num_rows($check_request_query) > 0){
                       return true;
                    }else{
                        return false;
                    }
                }
            } 
            public function didSendRequest($user_from){
                if(isset($this->user['username'])){
                    $user_to = $this->user['username'];
                    $check_request_query = mysqli_query($this->con,"SELECT * FROM friend_requests WHERE user_to = '$user_to' AND user_from ='$user_from'");
                    if(mysqli_num_rows($check_request_query) > 0){
                       return true;
                    }else{
                        return false;
                    }
                }
            }     
       }

?>