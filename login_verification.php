<?php
session_start();
include('includes/config.php');
include('includes/connection.php');

if(isset($_GET['token'])){
    $token = $_GET['token'];

    $sql = "SELECT * FROM tblusers WHERE token = :token"; 
    $query = $dbh->prepare($sql);
    $query->bindParam(':token', $token, PDO::PARAM_STR);
    $query->execute();
    $user = $query->fetch(PDO::FETCH_ASSOC); 

    if ($user) {

        

        $_SESSION['login'] = $user['EmailId']; 
        $_SESSION['fname'] = $user['FullName'];

        $usermail = $user['EmailId'];

        header("Location: index.php");
        

        $updateTokenSql = "UPDATE tblusers SET token = NULL WHERE EmailId = :email";
        $updateTokenQuery = $dbh->prepare($updateTokenSql);
        $updateTokenQuery->bindParam(':email', $usermail, PDO::PARAM_STR);
        $updateTokenQuery->execute();
        
       
    }else {
        //echo "Invalid or expired token.";
        unset($_SESSION['login']);
        unset($_SESSION['fname']);
        header("Location: index.php");
        exit;
    }

}else {
    unset($_SESSION['login']);
        unset($_SESSION['fname']);
        header("Location: index.php");
        exit;
}


?>