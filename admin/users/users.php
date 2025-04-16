
<?php
 require '../../config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Page</title>
    <link rel= "stylesheet" href="users.css"> 
  <!-- <link rel= "stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"> -->
</head>
<body>
  <div class="navbar">
    <div class="logo">📚 Admin Panel</div>
    <div class="links">
      <a href="/Library_system/admin/admin.php">Admin Dashboard</a>
      <a href="../../logout.php">Logout</a>
    </div>
  </div>
    <div class = "table-container">
        <h2>User Management </h2>
        <a class="user-button" href="add_user.php" role="button">Add Users</a>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                     <th>Name</th>
                      <th>Email</th>
                      <th>Role</th>
</tr>
</thead>
<tbody>
    <?php
    $sql = "SELECT * FROM users";
    $sql_prepare = $conn->prepare($sql);
    $sql_prepare->execute();
    $result = $sql_prepare->get_result();
    $sql_prepare->close();

    if(!$result) {
        die("Invalid query:".$conn->error);
    }
    while($row = $result->fetch_assoc()){
        echo"
<tr>
        <td>$row[id]</td>
        <td>$row[name]</td>
        <td>$row[email]</td>
        <td>$row[role]</td>
          <td>
            <a class='edit-button' href='/library_system/admin/users/edit.php?id=$row[id]'>Edit</a>
            <a class ='delete-button ' href='/library_system/admin/users/delete.php?id=$row[id]'>Delete</a>
</td>
</tr>
";
    }
    ?>
</tbody>
</table>
        <br>

    </div>
</body>
</html>