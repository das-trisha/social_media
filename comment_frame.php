<?php
    include 'connection.php';
    include 'user.php';
    include 'post.php';
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
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/css/style.css">
    </head>
        <body>
           
            <script>
                function toggle(){
                    var element = document.getElementById("Comment_section");
                    if(element.style.display == "block")
                        element.style.display = "none";
                    else
                    element.style.display = "block";   
                }        
            </script>
            <?php
              //Get id of post
              if(isset($_GET['post_id'])){
                $post_id = $_GET['post_id'];
              }
              $user_query = mysqli_query($conn,"SELECT added_by,user_to FROM posts Where id ='$post_id'");
              $row = mysqli_fetch_array($user_query);
              $posted_to = $row['added_by'];

              if(isset($_POST['postComment' . $post_id])){
                $post_body = $_POST['post_body'];
                $post_body = mysqli_escape_string($conn,$post_body);
                $date_time_now = date("Y:m:d H:i:s");
                $insert_post = mysqli_query($conn,"INSERT INTO comments VALUES ('','$post_body',' $userLoggedIn','$posted_to',' $date_time_now','no',$post_id)");
                echo "<p>Comment Posted!</p>";
              }

            ?>
            <form action="comment_frame.php?post_id=<?php echo $post_id;?>" id="comment_form" name="postComment" <?php echo $post_id?> method="POST">
                <textarea name="post_body" id=""></textarea>
                <input type="submit" name="postComment<?php echo $post_id;?>" value="POST">
            </form>
            //load comments
            <?php
                $get_comments = mysqli_query($conn,"SELECT * FROM comments WHERE post_id = '$post_id' ORDER BY id ASC");
                $count = mysqli_num_rows($get_comments);
                if($count != 0){
                    while($comment = mysqli_fetch_array($get_comments)){
                        $comment_body = $comment['post_body'];
                        $posted_to = $comment['posted_to'];
                        $posted_by = $comment['posted_by'];
                        $date_added = $comment['date_added'];
                        $removed = $comment['removed'];
                        $date_time_now = date("Y-m-d H:i:s");
                        $start_date = new DateTime($date_added);//Time of post
                        $end_date =  new DateTime($date_time_now);//current time
                        $interval =    $start_date->diff($end_date); //Difference between dates
                        if($interval->y >= 1){
                            if($interval == 1){
                                $time_message = $interval->y . "year ago"; // 1 year ago
                            }else{
                                $time_message = $interval->y . "years ago"; // 1+ year ago
                            }
                        }else if($interval->m >= 1){
                            if($interval->d == 0){
                                $days = "ago";
                            }elseif($interval->d == 1){
                                $days = $interval->d ."day ago";
                            }else{
                                $days = $interval->d ."days ago";
                            }   
                            if($interval->m == 1){
                                $time_message = $interval->m . "month" .$days; 
                            }else{
                                $time_message = $interval->m . "months" .$days;
                            }
                        }
                        else if($interval->d >= 1){
                            if($interval->d == 1){
                                $time_message = "yesterday";
                            }else{
                                $time_message = $interval->d ."days ago";
                            }   
                        } 
                        else if($interval->h >= 1){  
                            if($interval->h == 1){
                                $time_message = $interval->h."hour ago";
                            }else{
                                $time_message = $interval->h ."hours ago";
                            }   
                        } 
                        else if($interval->i >= 1){  
                            if($interval->i == 1){
                                $time_message = $interval->i."minute ago";
                            }else{
                                $time_message = $interval->i ."minutes ago";
                            }   
                        }else{  
                            if($interval->s < 30){
                                $time_message = "Just now";
                            }else{
                                $time_message = $interval->s ."seconds ago";
                            }   
                        } 
                        $user_obj = new User($conn,$posted_by);
                    ?>
                    <div class="comment_section">
                        <a href="<?php echo $posted_by?>"target="_parent"><img src="<?php echo $user_obj->getProfilepic();?>" title="<?php echo $posted_by; ?>" style="float:left;" height="30"></a>
                        <a href="<?php echo $posted_by?>"target="_parent"> <b> <?php echo $user_obj->getFirstAndLastName(); ?></b></a>
                        &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $time_message ."<br>" .$comment_body;?>
                        <hr>
                    </div>
                    <?php
                    }
                }
                else{
                    echo "<center><br>No comments to show!<br></center>";
                }    
            ?>
        </body>
</html> 