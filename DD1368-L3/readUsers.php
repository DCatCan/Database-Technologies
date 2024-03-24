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
            <h1>User Information</h1>
        </div>
<!--Styling HTML ends and the real work begins below-->


<?php

$id=isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.'); //The parameter value from the click is aquired

include 'connection.php';

try {
    $query = "SELECT * FROM users WHERE user_id = :id"; // Put query fetching data from table here
    $stmt = $con->prepare( $query );

    $stmt->bindParam(':id', $id); //Bind the ID for the query

    $stmt->execute(); //Execute query

    $row = $stmt->fetch(PDO::FETCH_ASSOC); //Fetchs data
    $full_name = $row['full_name'];
    $id = $row['user_id']; //Store data. Rename, add or remove columns as you like.
    $bday = $row['date_of_birth'];
	$phone_number = $row['phone_number'];
  if (empty($phone_number)) {
    $phone_number = '-';
  }
	$address = $row['address'];
	$email = $row['email'];
	$admin = $row['admin'];
  if(isset($admin)){
    $admin = 'Professor';
  } else {
    $admin = 'Student';
  }
  $department = $row['department_program'];
}


catch(PDOException $exception){ //In case of error
    die('ERROR: ' . $exception->getMessage());
}
?>
 <!-- Here is how we display our data. Rename, add or remove columns as you like-->
<table class='table table-hover table-responsive table-bordered'>
    <tr>
        <td>ID Number</td>
        <td><?php echo htmlspecialchars($id, ENT_QUOTES);  ?></td>
    </tr>

	<tr>
        <td>Full Name</td>
        <td><?php echo htmlspecialchars($full_name, ENT_QUOTES);  ?></td>
    </tr>

	<tr>
        <td>Date of Birth</td>
        <td><?php echo htmlspecialchars($bday, ENT_QUOTES);  ?></td>
    </tr>

	<tr>

        <td>Phone Number</td>
        <td><?php echo htmlspecialchars($phone_number, ENT_QUOTES);  ?></td>
    </tr>

	<tr>
        <td>Address</td>
        <td><?php echo htmlspecialchars($address, ENT_QUOTES);  ?></td>
    </tr>

    <tr>
        <td>Email</td>
        <td><?php echo htmlspecialchars($email, ENT_QUOTES);  ?></td>
    </tr>
    <tr>
        <td>Ocupation</td>
        <td><?php echo htmlspecialchars($admin, ENT_QUOTES);  ?></td>
    </tr>
    <tr>
        <td>Department Program</td>
        <td><?php echo htmlspecialchars($department, ENT_QUOTES);  ?></td>
    </tr>





    <tr>
        <td></td>
        <td>
            <a href='users.php' class='btn btn-danger'>Back to Users</a>
        </td>
    </tr>
</table>
    </div>
</body>
</html>
