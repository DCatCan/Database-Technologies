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

<!--Styling HTML ends and the real work begins below-->

<?php


include 'connection.php'; //Init a connection

$query = "SELECT DISTINCT rs.resource_id, title FROM resources rs
          LEFT JOIN taggings ts ON rs.resource_id=ts.resource_id
          LEFT JOIN tags t ON ts.tag_id=t.tag_id
          WHERE LOWER(title) LIKE LOWER(:keyword)
          OR LOWER(value) LIKE LOWER(:keyword)
          ORDER BY title"; // Put query fetching data from table here

$stmt = $con->prepare($query);
$keyword= isset($_POST['keyword']) ? $_POST['keyword'] : ''; //Is there any data sent from the form?

$keyword = "%".$keyword."%";
$stmt->bindParam(':keyword', $keyword);

$stmt->execute();

$num = $stmt->rowCount(); //Aquire number of rows

if($num>0){ //Is there any data/rows?
    echo "<table class='table table-responsive table-fix table-bordered'><thead class='thead-light'>";
    echo "<tr>";
        echo "<th>Title</th>"; // Rename, add or remove columns as you like.
		//echo "<th>code</th>";
    echo "</tr>";
while ($rad = $stmt->fetch(PDO::FETCH_ASSOC)){ //Fetches data
    extract($rad);
    echo "<tr>";

		// Here is the data added to the table
        echo "<td>{$title}</td>"; //Rename, add or remove columns as you like
		//echo "<td>{$code}</td>";
		echo "<td>";

		//Here are the buttons for update, delete and read.
		echo "<a href='readBooks.php?id={$resource_id}'class='btn btn-info m-r-1em'>Read</a>"; // Replace with ID-variable, to make the buttons work
		echo "<a href='updateBooks.php?id={$resource_id}' class='btn btn-primary m-r-1em'>Update</a>";// Replace with ID-variable, to make the buttons work
		echo "<a href='deleteBooks.php?id={$resource_id}' class='btn btn-danger'>Delete</a>";// Replace with ID-variable, to make the buttons work
    echo "<td style='width:8em'>";
    echo "<a href='borrow_book.php?id={$resource_id}' class='btn btn-info m-r-1em'>Loan</a>";// Replace with ID-variable, to make the buttons work

    echo "</td>";
		echo "</td>";
    echo "</tr>";
}
echo "</table>";
}
else{
	echo "<h1> Search gave no result </h1>";
}
?>
</div>
</body>
</html>
