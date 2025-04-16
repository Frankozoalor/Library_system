<?php
require '../../config.php';

if(isset($_GET["id"])) {
    $id = $_GET["id"];

  $sql = "DELETE FROM users WHERE id= ?";
  $sql_prepare =$conn->prepare($sql);
  $sql_prepare->bind_param("i", $id);
  $sql_prepare->execute();
  $sql_prepare->close();
}

   header("location: /library_system/admin/users/users.php");
    exit();
?>