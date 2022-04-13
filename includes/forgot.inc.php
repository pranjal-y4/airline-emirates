<?php
require_once 'dbh.inc.php' ;
if(isset($_POST['reset'])){
    $otp = $_POST['otp'];
    $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
    $email1 = $_POST['email1'];

    $email1 = $_POST['email1'];
    if(!empty($otp)){
        $stored_otp = file_get_contents('../otp.txt');
        if($otp == $stored_otp){
            echo $sql = "UPDATE `users` SET `usersPwd`='".$password."' WHERE `usersEmail` = '".$email1."'";
            $result = mysqli_query($conn, $sql);
            if($result){
                header("location:../login.php?status=reset");
				exit();
            }else{
                echo 'Unable to reset the password';
            }
        }

    }else{
        echo '<h1>Try Again</h1>';
    }
}