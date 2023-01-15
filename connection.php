<?php
ob_start();
session_start();
    $conn = mysqli_connect('localhost', 'root', '','social_media');
    //print_r($conn);
    // Check connection
    if (!$conn){
        die("Connection failed: " . mysqli_connect_error());
        echo "Failed";
    }
?>