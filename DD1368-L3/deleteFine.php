<?php
$id=isset($_GET['id']) ? $_GET['id'] : die('ERROR: ID not found'); //Aquire the ID

include 'connection.php'; //Init the connection

try {
    $query = "DELETE FROM fines WHERE fine_id = :id"; // Insert your DELETE query here
    $stmt = $con->prepare($query);
    $stmt->bindParam(':id', $id); //Binding the ID for the query

    if($stmt->execute()){
        header('Location: deleteFine.php'); //Redirecting back to the main page
    }else{
        die('Could not remove'); //Something went wrong
    }
}

catch(PDOException $exception){
    die('ERROR: ' . $exception->getMessage());
}
?>
