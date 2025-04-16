
<?php
  require '../../config.php';
/*
session_start(); 
if(!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}
*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel= "stylesheet" href="books.css"> 
</head>
<body>
      <div class="navbar">
    <div class="logo">📚 Admin Panel</div>
    <div class="links">
      <a href="/Library_system/admin/admin.php">Admin Dashboard</a>
      <a href="../../logout.php">Logout</a>
    </div>
  </div>
    <div class = "book-container">
        <h2>Books Management </h2>
        <a class="book-button" href="add_books.php" role="button">Add Books</a>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                     <th>Title</th>
                      <th>Author</th>
                      <th>Genre</th>
                      <th>ISBN</th>
                      <th>Quantity</th>
                      <th>Status</th>
                      <th>Added date</th> 
</tr>
</thead>
<tbody>
    <?php
    $sql = "SELECT * FROM books";
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
        <td>$row[title]</td>
        <td>$row[author]</td>
        <td>$row[genre]</td>
        <td>$row[isbn]</td>
        <td>$row[quantity]</td>
        <td>$row[status]</td>
        <td>$row[added_date]</td>
          <td>
            <a class='edit-button' href='/library_system/admin/books/edit.php?id=$row[id]'>Edit</a>
            <a class ='delete-button' href='/library_system/admin/books/delete.php?id=$row[id]'>Delete</a>
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