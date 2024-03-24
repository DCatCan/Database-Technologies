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
        <a class="nav-link" href="borrow.php">Add Users</a> <!--Insert your own php-file here -->
      </li>
	<li class="nav-item">
      <a class="nav-link" href="borrow.php">Borrows</a> <!--Insert your own php-file here -->
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
        $query = "INSERT INTO users(full_name, date_of_birth, phone_number, address, email, department_program)
                    VALUES (:full_name, :date_of_birth, :phone_number,:address,:email,:department_program)"; // Put query inserting data to table here

        $stmt = $con->prepare($query); // prepare query for execution

        $full_name=htmlspecialchars(strip_tags($_POST['full_name'])); //Rename, add or remove columns as you like
        $date_of_birth=htmlspecialchars(strip_tags($_POST['date_of_birth']));
        $phone_number=htmlspecialchars(strip_tags($_POST['phone_number']));
        $address=htmlspecialchars(strip_tags($_POST['address']));
        $email=htmlspecialchars(strip_tags($_POST['email']));

        $department_program=htmlspecialchars(strip_tags($_POST['department_program']));

        $stmt->bindParam(':full_name', $full_name); //Binding parameters for the query
	      $stmt->bindParam(':date_of_birth', $date_of_birth);
        $stmt->bindParam(':phone_number', $phone_number);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':email', $email);

        $stmt->bindParam(':department_program', $department_program);


        if($stmt->execute()){ //Executes and check if correctly executed

            echo "<div class='alert alert-success'>Record was saved.</div>";
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
            <td>Full Name</td>
            <td><input type='text' name='full_name' class='form-control' /></td>
        </tr>
        <tr>
            <td>Date of Birth</td>
            <td><input type='text' name='date_of_birth' class='form-control' /></td>
        </tr>
        <tr>
            <td>Phone Number</td>
            <td><input type='text' name='phone_number' class='form-control' /></td>
        </tr>
        <tr>
            <td>Address</td>
            <td><input type='text' name='address' class='form-control' /></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><input type='text' name='email' class='form-control' /></td>
        </tr>
        <tr>
            <td>Department Program</td>
            <td><input type='text' name='department_program' class='form-control' /></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type='submit' value='Save' class='btn btn-primary' />
                <a href='users.php' class='btn btn-danger'>Go back</a>
            </td>
        </tr>
    </table>
</form>
</body>
</html>
