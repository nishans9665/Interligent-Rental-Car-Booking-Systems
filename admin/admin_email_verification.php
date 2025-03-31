<?php
session_start();

header("Location: index.php");

include('includes/config.php');
include('includes/connection.php');

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $sql = "SELECT * FROM admin WHERE token = :token";
    $query = $dbh->prepare($sql);
    $query->bindParam(':token', $token, PDO::PARAM_STR);
    $query->execute();
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if ($user) {

        $_SESSION['alogin'] = $user["UserName"];
        header("Location: dashboard.php");

        $deleteTokenSql = "UPDATE admin SET token = NULL WHERE token = :token";
        $deleteTokenQuery = $dbh->prepare($deleteTokenSql);
        $deleteTokenQuery->bindParam(':token', $token, PDO::PARAM_STR);
        $deleteTokenQuery->execute();

        
    } else {
        echo "Invalid or expired token.";
    }
} else {
    echo "No token provided.";
}
