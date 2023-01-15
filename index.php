<?php
   include 'header.php';
   include 'user.php';
   include 'post.php';
   if(isset($_POST['post'])){
      $post = new Post($conn,$userLoggedIn);
      $post->submitPost($_POST['post_text'],'none');
      header("Location:http://localhost/SocialMedia/index.php");
   }
?>
   <div class="user_details coloumn">
      <a href="<?php echo $userLoggedIn;?>"> <img src="<?php echo $user['profile_pic'];?>"></a>
      <div class="user_details_left_right">
         <a href="<?php echo $userLoggedIn;?>"> <?php 
            echo $user['first_name']." ".$user['last_name'];
         ?></a><br>
         <?php 
            echo "Posts:" ." ".$user['num_posts']."<br>";
            echo "Likes:" ." ".$user['num_likes'];
         ?>
      </div>
   </div>
   <div class="main_column column"> 
      <form class ="post_form" action="index.php" METHOD="POST">
         <textarea name="post_text" id="post_text" placeholder="Got something to say?" cols="30" rows="10"></textarea>
         <input type="submit" value="Post" id="post_button" name="post"><hr>
      </form>
      <?php
         $post = new Post($conn,$userLoggedIn);
         $post->loadPostsFriends();
      ?>
   </div>
</body>
</html>