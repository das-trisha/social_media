<?php
   include 'header.php';
   include 'user.php';
   include 'post.php';

   if(isset($_GET['profile_username'])){
      $username = $_GET['profile_username'];
      $user_details_query = mysqli_query($conn,"SELECT * FROM social_media WHERE username = '$username'");
      $user_array = mysqli_fetch_array($user_details_query);
      $num_friends = (substr_count($user_array['friend_array'],",")) - 1;
  }
?>
   
      <div class="profile_left"> 
            <img src="<?php echo $user_array['profile_pic'];?>">
                  <div class="profile_info">
                        <p><?php echo "Posts:" . $user_array['num_posts'];?></p>
                        <p><?php echo "Likes:" . $user_array['num_likes'];?></p>
                        <p><?php echo "Friends:" . $num_friends;?></p>
                  </div>
                  <form action="<?php echo $username;?>">
                        <?php
                             $profile_user_obj = new User($conn,$username);
                             if($profile_user_obj->isClosed()){
                              header("Location:http://localhost/SocialMedia/user_closed.php");
                             }
                             $logged_in_user_obj = new User($conn,$userLoggedIn);
                             if($userLoggedIn != $username){
                                    if($logged_in_user_obj->isFriend($username)){
                                          echo '<input type="submit" name="remove_friend" class="danger" value="Remove Friend"><br>';
                                    }else if($logged_in_user_obj->didRecieveRequest($username)){
                                          echo '<input type="submit" name="respond_request" class="warning" value="Respond to Request"><br>';
                                    }else if($logged_in_user_obj->didSendRequest($username)){
                                          echo '<input type="submit" name="" class="warning" value="Request Sent"><br>';
                                    }else{
                                          echo '<input type="submit" name="add_friend" class=success" value="Add Friend"><br>';
                                    }
                             }


                        ?>
                  </form>
      </div>
      <div class="main_column column"> 
            <?php echo $username;?>
      </div>
</body>
</html>