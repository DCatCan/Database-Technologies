<?php
$id=isset($_GET['id']) ? $_GET['id'] : die('ERROR: ID not found'); //Aquire the ID

include 'connection.php'; //Init the connection

try { 
    $query = "UPDATE borrowed_resources SET return_date=current_date WHERE borrowed_resource_id = :id"; // Insert your DELETE query here
    $stmt = $con->prepare($query);
    $stmt->bindParam(':id', $id); //Binding the ID for the query

    if($stmt->execute()){
        header('Location: borrow.php'); //Redirecting back to the main page
    }else{
        die('Could not return'); //Something went wrong
    }
}

catch(PDOException $exception){
    die('ERROR: ' . $exception->getMessage());
}
?>