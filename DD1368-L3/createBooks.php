<!--Here is some styling HTML you don't need to pay attention to-->
<!DOCTYPE html>
<html>
<head>
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

<!--Styling HTML ends and the real work begins below-->
<?php

include 'connection.php'; //Init a connection

if($_POST){
 
    try{
        $query = "INSERT INTO resources(title, media_type) VALUES (:title, 'book') RETURNING resource_id"; // Put query inserting data to table here

        $stmt = $con->prepare($query); // prepare query for execution
        $title=htmlspecialchars(strip_tags($_POST['title'])); //Rename, add or remove columns as you like
 
        $stmt->bindParam(':title', $title); //Binding parameters for the query
	
        if($stmt->execute()){ //Executes and check if correctly executed

            echo "<div class='alert alert-success'>Record was saved.</div>";
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $id = $row['resource_id'];
            //echo $id;
        }else{
            echo "<div class='alert alert-danger'>Unable to save record.</div>";
        }

    }
    catch(PDOException $exception){ //In case of error
        die('ERROR: ' . $exception->getMessage());
    }
}
?>
 
<!-- The HTML-Form. Rename, add or remove columns for your insert here -->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <table class='table table-hover table-responsive table-bordered'>
        <tr>
            <td>Title</td>
            <td><input type='text' name='title' class='form-control' /></td>
            <?php 
                if($_POST){
                    echo "<a href='updateBooks.php?id=".$id."' class='btn btn-danger'>Go to new book</a>";
                }
            ?>
        </tr>		
        <tr>
            <td></td>
            <td>
                <input type='submit' value='Save' class='btn btn-primary' />
                <a href='books.php' class='btn btn-danger'>Go back</a>
            </td>
        </tr>
    </table>
</form>
</body>
</html>