<?php
require '../../config.php';
$id = "";
$name = "";
$email = "";
$password = "";
$role = "";

$errorMessage = "";
$successMessage = "";

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    if(!isset($_GET["id"])){
      header("location: /library_system/admin/users/users.php");
      exit();
    }

    $id = $_GET["id"];

    $sql = "SELECT * FROM users WHERE id = ?";
    $sql_prep = $conn->prepare($sql);
    $sql_prep->bind_param("i", $id);
    $sql_prep->execute();
    $result = $sql_prep->get_result();
    $row = $result->fetch_assoc();
    $sql_prep->close();

    if(!$row) {
        header("location: /library_system/admin/users/users.php");
      exit();
    }

      $name = $row["name"];
      $email = $row["email"];
      $password = $row["password"];
      $role = $row["role"];
} else {
       
        $id = $_POST["id"];
        $name = $_POST["name"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $role = $_POST["role"];

        do {
        if(empty($name) || empty($email) || empty($password) || empty($role)) {
        $errorMessage = "All the fields are required";
        break;
        } 
        $sql = "UPDATE users SET name = ?, email = ?, password = ?,  role = ? WHERE id = ? ";
        $sql_update = $conn->prepare($sql);

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql_update->bind_param("ssssi", $name, $email,$hashed_password,$role,$id);
        $result = $sql_update->execute();
        $sql_update->close();

  if(!$result){
    $errorMessage = "Invalid query:".$conn->error;
    break;
  }

$successMessage = "User Updated Sucessfully";
} while (false);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit user</title>
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
    
        <h2>Edit user</h2>
        <form method="post">
              <input type = "hidden" name="id" value="<?php echo $id;?>">
            <div class ="row mb3">
                <label class="col-sm-s3 col-form-label">Name</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" name="name" value="<?php echo $name;?>"> 
            </div>
</div>
<div class ="row mb3">
                <label class="col-sm-s3 col-form-label">Email</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" name="email" value="<?php echo $email;?>"> 
            </div>
</div>

<div class ="row mb3">
                <label class="col-sm-s3 col-form-label">Password</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" name="password" value="<?php echo $password;?>"> 
            </div>
</div>
<br>
<div class="row mb3">
    <label class="col-sm-s3 col-form-label">Role</label>
    <div class="col-sm-2">
        <select name="role" class="form-control" required>
            <option value="">--Select Role--</option>
            <option value="user" <?php if ($role == "user") echo "selected"; ?>>User</option>
            <option value="admin" <?php if ($role == "admin") echo "selected"; ?>>Admin</option>
        </select>
    </div>
</div>

<br>
<div class ="row mb3">
                <div class="offset-sm-3 col-sm-3 d-grid">
                  <button type="submit" class="btn btn-primary">Submit</button> 
            </div>
<br>
            <div class ="col-sm-3 d-grid">
                <a class="btn btn-outline-primary" href="/library_system/admin/users/users.php" role="button">Back</a>
</div>
</div>
</form>
</div>
</body>
</html>