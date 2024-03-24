<!--Here is some styling HTML you don't need to pay attention to-->
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
<style>
.container {margin: auto; align-content: center;}
.table-fix {box-shadow: 0px 0px 5px 1px; display: table; }
</style>
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

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <table class='table table-hover table-responsive table-bordered'>
        <tr>
            <td>Search</td>
            <td><input type='text' name='keyword' class='form-control' /></td>
        </tr>
    </table>
</form>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <table class='table table-hover table-responsive table-bordered'>
        <tr>
            <td>Create New Tag</td>
            <td><input type='text' name='new_key' class='form-control' placeholder="Type"/></td>
            <td><input type='text' name='new_value' class='form-control' placeholder="Value"/></td>
            <td><input type='submit' value='Create' class='btn btn-primary'/></td>
        </tr>
    </table>
</form>

<?php


include 'connection.php'; //Init a connection

$query = "SELECT * FROM tags WHERE LOWER(key) LIKE LOWER(:keyword) OR LOWER(value) LIKE LOWER(:keyword) ORDER BY key"; // Put query fetching data from table here

$stmt = $con->prepare($query);
$keyword= isset($_POST['keyword']) ? $_POST['keyword'] : ''; //Is there any data sent from the form?

$keyword = "%".$keyword."%";
$stmt->bindParam(':keyword', $keyword);

$stmt->execute();

$num = $stmt->rowCount(); //Aquire number of rows

if($num>0){ //Is there any data/rows?
    echo "<table class='table table-responsive table-fix table-bordered'><thead class='thead-light'>";
    echo "<tr>";
        echo "<th>Type</th>"; // Rename, add or remove columns as you like.
        echo "<th>Value</th>";
        echo "<th>Funktioner</th>";
		//echo "<th>code</th>";
    echo "</tr>";
while ($rad = $stmt->fetch(PDO::FETCH_ASSOC)){ //Fetches data
    extract($rad);
    echo "<tr>";
		
		// Here is the data added to the table
        echo "<td>{$key}</td>"; //Rename, add or remove columns as you like
		    echo "<td>{$value}</td>";
		echo "<td>";
		
		//Here are the buttons for update, delete and read.
    echo "<a href='deleteTag.php?id={$tag_id}' class='btn btn-danger'>Delete</a>";// Replace with ID-variable, to make the buttons work
		echo "</td>";
    echo "</tr>";
}
echo "</table>";    
}
else{
	echo "<h1> Search gave no result </h1>";
}
  if($_POST && isset($_POST['new_key']) && isset($_POST['new_value'])){
    $query = "INSERT INTO tags (key, value) VALUES (:key, :value)";

    $stmt = $con->prepare($query);

    $new_key = htmlspecialchars(strip_tags($_POST['new_key']));
    $new_value = htmlspecialchars(strip_tags($_POST['new_value']));

    $stmt->bindParam(":key", $new_key);
    $stmt->bindParam(":value", $new_value);    


    if($stmt->execute()){//Executes and check if correctly executed
      echo "<div class='alert alert-success'>Record was updated.</div>";
    }else{
      echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
  }
  }
?>

</div>
</body>
</html>