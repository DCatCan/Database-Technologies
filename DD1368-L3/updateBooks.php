<!--Here is some styling HTML you don't need to pay attention to-->
<!DOCTYPE HTML>
<html>
<head>
    <title>Update books</title>
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
            <h1>Update bookinfo</h1>
        </div>
     
<!--Styling HTML ends and the real work begins below-->	 
<?php
		
$id=isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.'); //The parameter value from the click is aquired
 
include 'connection.php'; //Init the connection
 
try { //Aquire the already existing data
    $query = "SELECT * 
        FROM resources rs
        LEFT JOIN taggings ts ON rs.resource_id=ts.resource_id
        LEFT JOIN tags t ON ts.tag_id=t.tag_id
        WHERE rs.resource_id = :id
    "; // Put query fetching data from table here
    $stmt = $con->prepare( $query );
    $stmt->bindParam(':id', $id, PDO::PARAM_INT); //Bind the ID for the query

    $stmt->execute(); //Execute query

    $info = array();
    $tags = array();
    $physicals = array();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        if(array_key_exists($key, $info)){
            $key = $key."2";
        }
        if(!in_array($physical_resource_id, $physicals, true)){
            array_push($physicals, $physical_resource_id);
        }
        $info[$key] = $value;
        $tags[$key] = $tag_id;
    }
}
 
catch(PDOException $exception){ //In case of error
    die('ERROR: ' . $exception->getMessage());
}
?>
 
<?php
 
 if($_POST){ //Has the form been submitted?
      
     try{
        $query1 = "UPDATE resources 
                    SET title=:title, media_type=:media
                    WHERE resource_id= :id"; //Put your query for updating data here
        $stmt1 = $con->prepare($query1);
        
        $title = htmlspecialchars(strip_tags($_POST['title']));
        $media_type = htmlspecialchars(strip_tags($_POST['media_type']));

        $stmt1->bindParam(':title', $title);
        $stmt1->bindParam(':media', $media_type);
        $stmt1->bindParam(':id', $id);

        if($stmt1->execute()){//Executes and check if correctly executed
            echo "<div class='alert alert-success'>Record was updated.</div>";
        }else{
            echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
        }

        $to_delete = array();

        foreach(array_keys($_POST) as $el){
            if(substr($el,0, 4) === "del_"){
                if(is_numeric($_POST[$el])){
                    array_push($to_delete, (int) $_POST[$el]);
                }
            }
        }

        if(count($to_delete) > 0){

            $query1 = "DELETE FROM taggings WHERE resource_id=:id AND tag_id IN (".implode(',', $to_delete).")";

            $stmt1 = $con->prepare($query1);

            $stmt1->bindParam(':id', $id);

            if($stmt1->execute()){//Executes and check if correctly executed
                echo "<div class='alert alert-success'>Record was updated.</div>";
            }else{
                echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
            }
        }

        if(isset($_POST['add'])){
            $query1 = "INSERT INTO taggings (resource_id, tag_id) values (:id, :tag_id)";

            $stmt1 = $con->prepare($query1);

            $stmt1->bindParam(':id', $id);
            $stmt1->bindParam(':tag_id', $_POST['add']);

            if($stmt1->execute()){//Executes and check if correctly executed
                echo "<div class='alert alert-success'>Record was updated.</div>";
            }else{
                echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
            }
        }
     }
      
     catch(PDOException $exception){ //In case of error
         die('ERROR: ' . $exception->getMessage());
     }
 }
 ?>
 
<!-- The HTML-Form. Rename, add or remove columns for your update here -->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}");?>" method="post" id="form">
    <table class='table table-hover table-responsive table-bordered'>
        <input type='hidden' name='id' value="<?php echo htmlspecialchars($id, ENT_QUOTES);  ?>" class='form-control'/>
    <tr>
        <th>Typ</th>
        <th>Namn</th>
        <th>Ta bort?</th>
    </tr>
    <tr>
        <td>Name</td>
        <td><input type="text" name="title" value="<?php echo htmlspecialchars($title, ENT_QUOTES);  ?>" class='form-control'/></td>
        <td></td>
    </tr>
    <tr>
        <td>Media Type</td>
        <td><input type="text" name="media_type" value="<?php echo htmlspecialchars(ucfirst($media_type), ENT_QUOTES);  ?>" class='form-control'/></td>
        <td></td>
    </tr>
	
    <?php 
        foreach(array_keys($info) as $key){
            if($key != "title" && $key != ""){
                echo "<tr>
                <td>".htmlspecialchars(ucfirst($key), ENT_QUOTES)."</td>
                    <td>
                        <input type=\"text\" name=\"".htmlspecialchars($key)."\" value=\"".htmlspecialchars($info[$key], ENT_QUOTES)."\" class='form-control' readonly/>"
                    ."</td>
                <td>
                    <input type=\"checkbox\" name='del_".htmlspecialchars($key)."' value='".htmlspecialchars($tags[$key])."' class='form-control' >
                </td>
            </tr>";
            }
        };
    ?>

    <tr>
        <td>
        <select onchange="window.location.search='id=<?=$id?>&key='+this.value">
            <?php 
                $query = "SELECT DISTINCT key FROM tags";
                $stmt = $con->prepare($query);
                if(!$stmt->execute()){
                    echo "Error";
                }

                while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    echo "<option value='".$row["key"]."'>".$row["key"]."</option>";
                }
            ?>
        </select></td>
        <td><?php 
            $keyy=isset($_GET['key']) ? $_GET['key'] : "";
            
            if($keyy !== ""){
                $query = "SELECT DISTINCT tag_id, value FROM tags WHERE key=:key";
                $stmt = $con->prepare($query);
                $stmt->bindParam(":key", $keyy);
                if(!$stmt->execute()){
                    echo "Error";
                }
                echo "<select name='add' form='form'>";
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    echo "<option value='".$row["tag_id"]."'>".$row["value"]."</option>";
                }
                echo "</select>";
            }

        ?></td>
        <td></td>
    </tr>
    <tr>
		
        <tr>
            <td></td>
            <td>
                <input type='submit' value='Save Changes' class='btn btn-primary' />
                <a href='books.php' class='btn btn-danger'>Back to read products</a>
            </td>
        </tr>
    </table>
</form>
    </div>
</body>
</html>