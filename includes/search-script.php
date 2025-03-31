<?php
session_start();
include('includes/config.php'); // Include your database connection

if (isset($_GET['query'])) {
    $query = '%' . htmlspecialchars($_GET['query']) . '%';

    // Query to search for cars by name or brand
    $sql = "SELECT VehiclesTitle AS name FROM tblvehicles WHERE VehiclesTitle LIKE :query 
            UNION 
            SELECT BrandName AS name FROM tblbrands WHERE BrandName LIKE :query";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':query', $query, PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return results as JSON
    echo json_encode($results);
}
?>