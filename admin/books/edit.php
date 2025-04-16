<?php
require '../../config.php';

$id = "";
$title = "";
$author = "";
$genre = "";
$isbn = "";
$quantity = "";

$errorMessage = "";
$successMessage = "";

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    if(!isset($_GET["id"])){
      header("location: /library_system/admin/books/books.php");
      exit();
    }

    $id = $_GET["id"];

    $sql = "SELECT * FROM books WHERE id= ?";
    $sql_prep = $conn->prepare($sql);
    $sql_prep->bind_param("i", $id);
    $sql_prep->execute();
    $result = $sql_prep->get_result();
    $row = $result->fetch_assoc();
    $sql_prep->close();

    if(!$row) {
        header("location: /library_system/admin/books/books.php");
      exit();
    }

 
     $title = $row["title"];
     $author = $row["author"];
      $genre = $row["genre"];
      $isbn = $row["isbn"];
      $quantity = $row["quantity"];
} else {
       
        $id = $_POST["id"];
        $title = $_POST["title"];
        $author = $_POST["author"];
        $genre = $_POST["genre"];
        $isbn = $_POST["isbn"];
        $quantity = $_POST["quantity"];

        do {
        if(empty($title) || empty($author) || empty($genre) || empty($isbn) || empty($quantity)) {
        $errorMessage = "All the fields are required";
        break;
        } 

        $sql = "UPDATE books SET title = ?, author = ?, genre = ?,  isbn = ?, quantity = ? WHERE id = ? ";
        $sql_update = $conn->prepare($sql);
        $sql_update->bind_param("sssssi", $title, $author,$genre,$isbn, $quantity, $id);
        $result = $sql_update->execute();
        $sql_update->close();

  if(!$result){
    $errorMessage = "Invalid query:".$conn->error;
    break;
  }

$successMessage = "Books Updated Sucessfully";
//header("location: /library_system/admin/books/books.php");
//exit();
} while (false);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit books</title>
   <!-- <link rel= "stylesheet" href="../style.css"> -->
   <link rel= "stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
   <script src= "https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" ></script>
</head>
<body style = "background: #e0e0e0;">
    <div class ="container my-5">

    <?php 
    if(!empty($errorMessage)) {
        echo "
        <div class = 'alert alert-warning alert-dismissible fade show' role = 'alert'>
        <strong>$errorMessage</strong>
        <button type = 'button' class = 'btn-close' data-bs-dismiss='alert' aria-label='close'></button>
        </div>
        ";
    }

    if(!empty($successMessage)) {
        echo "
        <div class ='row mb-3'>
        <div class ='offset-sm-3 col-sm-6'>
        <div class = 'alert alert-success alert-dismissible fade show' role = 'alert'>
        <strong>$successMessage</strong>
        <button type = 'button' class = 'btn-close' data-bs-dismiss='alert' aria-label='close'></button>
        </div>
        </div>
        </div>
        ";
    }
    
    ?>
    
        <form method="post" >
            <input type = "hidden" name="id" value="<?php echo $id;?>">
            <div class ="row mb-3">
                <label class="col-sm-s3 col-form-label">Title</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" name="title" value="<?php echo $title;?>"> 
            </div>
</div> 
<div class ="row mb3">
                <label class="col-sm-s3 col-form-label">Author</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" name="author" value="<?php echo $author;?>"> 
            </div>
</div>

<div class ="row mb3">
                <label class="col-sm-s3 col-form-label">Genre</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" name="genre" value="<?php echo $genre;?>"> 
            </div>
</div>
<div class ="row mb3">
                <label class="col-sm-s3 col-form-label">ISBN</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" name="isbn" value="<?php echo $isbn;?>"> 
            </div>
</div>
<div class ="row mb3">
                <label class="col-sm-s3 col-form-label">Quantity</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" name="quantity" value="<?php echo $quantity;?>"> 
            </div>
</div>

<br>
<div class ="row mb3">
                <div class="offset-sm-3 col-sm-3 d-grid">
                  <button type="submit" class="btn btn-primary">Submit</button> 
            </div>
<br>
            <div class ="col-sm-3 d-grid">
                <a class="btn btn-outline-primary" href="/library_system/admin/books/books.php" role="button">Back</a>
</div>
</div>
</form>
</div>
</body>
</html>