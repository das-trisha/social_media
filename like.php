<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
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
    if(isset($_GET['post_id'])){
        $post_id = $_GET['post_id'];
    }
    $get_likes = mysqli_query($conn,"SELECT likes,added_by FROM posts WHERE id = '$post_id'");
    $row = mysqli_fetch_array($get_likes);
    $total_likes = $row['likes'];
    $user_liked = $row['added_by'];

    $user_details_query = mysqli_query($conn,"SELECT * FROM social_media WHERE username = '$user_liked'");
    $row = mysqli_fetch_array($user_details_query);
    $total_user_likes =  $row['num_likes'];
    //like button
    if(isset($_POST['like_button'])){
        $total_likes++;
        $query = mysqli_query($conn,"UPDATE posts SET likes ='$total_likes' WHERE id = '$post_id'");
        $total_user_likes++;
        $user_likes = mysqli_query($conn,"UPDATE social_media SET num_likes ='$total_user_likes' WHERE username = '$user_liked'");
        //$insert_user = mysqli_query($conn,"DELETE FROM likes WHERE username = '$userLoggedIn' AND post_id = '$post_id'");
        $insert_user = mysqli_query($conn,"INSERT INTO likes VALUES('','$userLoggedIn','$post_id')");
        //insert notification
     }
     //unlike button
     if(isset($_POST['unlike_button'])){
        $total_likes--;
        $query = mysqli_query($conn,"UPDATE posts SET likes ='$total_likes' WHERE id = '$post_id'");
        $total_user_likes--;
        $user_likes = mysqli_query($conn,"UPDATE social_media SET num_likes ='$total_user_likes' WHERE username = '$user_liked'");
        $insert_user = mysqli_query($conn,"DELETE FROM likes WHERE username = '$userLoggedIn' AND post_id = '$post_id'");

        //insert notification
     }
    //check for previous likes
    $check_query = mysqli_query($conn,"SELECT * FROM likes WHERE username ='$userLoggedIn' AND post_id='$post_id'");
    $num_rows = mysqli_num_rows($check_query);
    if($num_rows > 0){
        echo '<form action="like.php?post_id='.$post_id.'" method = "POST">
        <input type= "submit" class ="comment_like" name= "unlike_button" value ="Unlike">
        <div class="like_value">
            '.$total_likes.' Likes
        </div>
    </form>
    ';
    }else{
        echo '<form action="like.php?post_id='.$post_id.'" method = "POST">
            <input type= "submit" class ="comment_like" name= "like_button" value ="Like">
            <div class="like_value">
                '.$total_likes.' Likes
            </div>
        </form>
      ';
    }
    ?>
</body>
</html>