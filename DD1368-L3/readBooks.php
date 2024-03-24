<!--Here is some styling HTML you don't need to pay attention to-->
<!DOCTYPE HTML>
<html>
<head>
    <title>LMS Books</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
</head>
<body>

<div class="container">
<div class="page-header">
        <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
  <a class="navbar-brand" href="books.php">Books</a>
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" href="createBooks.php">Add Book</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="users.php">Users</a> <!--Insert your own php-file here -->
    </li>
	<li class="nav-item">
      <a class="nav-link" href="borrow.php">Borrows</a> <!--Insert your own php-file here -->
    </li>
    <li class="nav-item">
      <a class="nav-link" href="tags.php">Tags</a> <!--Insert your own php-file here -->
    </li>
  </ul>
</nav>
</div>
</div>
    <div class="container">
        <div class="page-header">
            <h1>Book information</h1>
        </div>
<!--Styling HTML ends and the real work begins below-->

         
<?php

$id=isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.'); //The parameter value from the click is aquired
 
include 'connection.php';
 
try {
    $query = "SELECT * 
        FROM resources rs
        JOIN taggings ts ON rs.resource_id=ts.resource_id
        JOIN tags t ON ts.tag_id=t.tag_id
        WHERE rs.resource_id = :id
    "; // Put query fetching data from table here
    $stmt = $con->prepare( $query );
    $stmt->bindParam(':id', $id, PDO::PARAM_INT); //Bind the ID for the query

    $stmt->execute(); //Execute query
 
    //$num = $stmt->rowCount();
    $info = array();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $info[$key] = $value;
    }
    //echo implode($info);
    //$name = $row['rs.title']; //Store data. Rename, add or remove columns as you like.
}
 

catch(PDOException $exception){ //In case of error
    die('ERROR: ' . $exception->getMessage());
}
?>
 <!-- Here is how we display our data. Rename, add or remove columns as you like-->
<table class='table table-hover table-responsive table-bordered'>
    <tr>
        <td>Name</td>
        <td><?php echo htmlspecialchars($title, ENT_QUOTES);  ?></td>
    </tr>
    <tr>
        <td>Media Type</td>
        <td><?php echo htmlspecialchars(ucfirst($media_type), ENT_QUOTES);  ?></td>
    </tr>
	
    <?php 
        foreach(array_keys($info) as $key){
            echo "<tr>
                <td>".htmlspecialchars(ucfirst($key), ENT_QUOTES)."</td>
                <td>".htmlspecialchars($info[$key], ENT_QUOTES)."</td>
            </tr>";
        };
    ?>
    <tr>
        <td>Available copies</td>
        <td><?php 
            $query = "SELECT * FROM physical_resources WHERE resource_id=:id";

            $stmt = $con->prepare($query);

            $stmt->bindParam(":id", $id);

            $stmt->execute();

            $num1 = $stmt->rowCount();

            $query = "SELECT * FROM borrowed_resources br 
                    JOIN physical_resources pr ON pr.physical_resource_id=br.physical_resource_id
                    WHERE resource_id=:id";

            $stmt = $con->prepare($query);

            $stmt->bindParam(":id", $id);

            $stmt->execute();

            $num2 = $stmt->rowCount();
            $num2 = $num1 - $num2;
            echo "{$num2}/{$num1}";
        ?></td>
    </tr>
    <tr>
        <td></td>
        <td>
            <a href='books.php' class='btn btn-danger'>Back to read products</a>
        </td>
    </tr>
    
</table> 
    </div> 
</body>
</html>