<?php
 require '../config.php';

session_start();
#echo "User ID: " . ($_SESSION['user_id'] ?? 'Session not set');
if(!isset($_SESSION['user_id'])){
 echo "You must be logged in to borrow books";
 exit();
}

$update_penalty = $conn->prepare("
UPDATE borrowed_books SET penalty = DATEDIFF(CURDATE(), return_date) * 1.00
WHERE user_id = ?
AND return_date < CURDATE()
AND penalty = 0
");

$update_penalty->bind_param("i", $_SESSION['user_id']);
$update_penalty->execute();
$update_penalty->close();
  ?>
<!DOCTYPE html>
<html lang="en">
 <head>
  <meta charset="UTF-8" />
  <meta
   name="viewport"
   content="width=device-width, initial-scale=1.0" />
  <title>User Dashboard</title>
  <link
   rel="stylesheet"
   href="style.css" />
  <script src="https://kit.fontawesome.com/4f30a2558a.js"></script>
 </head>
 <body>
  <section id="menu">
   <div class="logo">
    <img
     src="img/user.png"
     alt="" />
   <h2><span><?= $_SESSION['name']; ?></span></h2>
   </div>
   <div class="items">
    <li>
     <i
      class="fa fa-tachometer"
      aria-hidden="true"></i
     ><a href="#">Dashboard</a>
    </li>
     <li>
     <i
     class="fa fa-book" aria-hidden="true"></i
     ><a href="#borrowed-books">Borrowed Books</a>
    </li>
     <li>
     <i
      class="fa fa-book" aria-hidden="true"></i
     ><a href="#available-books">View Books</a>
    </li>
     <li>
     <i
    class="fa fa-sign-out" aria-hidden="true"></i
     ><a href="../logout.php">Logout</a>
    </li>
   </div>
  </section>

  <section id = "interface">
    <div class="navigation">
        <div class="n1">
            <div>
                <i id="menu-btn" class="fa fa-bars"></i>
            </div>
        <form method="GET" action="">
        <div class="search">
            <!--<i class="fa fa-search" ></i>-->
            <input type="text" name="search" placeholder="Search Books"/>
            <button type="submit">Search</button>
            </div>
           </form>
            </div>
        </div>

<div class="board" id="available-books">
    <h3>Avaliable Books</h3>
    <table width="90%">
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
<tbody id = "book-table-body">
</tbody>
</table>
<script>
    <?php
    $search = isset($_GET['search']) ? $_GET['search'] : "";

    if(!empty($search)){
        $search = $conn->real_escape_string($search);
        $sql =  "SELECT * FROM books WHERE title LIKE '%$search%' OR author LIKE '%$search%' OR genre LIKE '%$search%'";
    }
    else{
        $sql = "SELECT * FROM books";
    }
    $result = $conn -> query($sql);
    $books=[];

    if($result) {
   while($row = $result->fetch_assoc()){
    $books[] = $row;
   }
    } else{
        die("Invalid query".$conn->error);
    }
?>
const books = <?php echo json_encode($books); ?>

function displayBooks(){
    const tablebody = document.querySelector("#book-table-body");

    books.forEach(book => {
        const row = document.createElement('tr');

      row.innerHTML = `
            <td>${book.id}</td>
            <td>${book.title}</td>
            <td>${book.author}</td>
            <td>${book.genre}</td>
            <td>${book.isbn}</td>
            <td>${book.quantity}</td>
            <td>${book.status}</td>
            <td>${book.added_date}</td>
            <td><button class="borrow-btn" data-book-id="${book.id}">Borrow</button></td>
        `;
        tablebody.appendChild(row);
    })
}
displayBooks();
</script>
<?php

if(isset($_GET['borrow_id']) && !isset($_GET['book_id'])){
    $books_id = $_GET['borrow_id'];
    $user_id = $_SESSION['user_id'];

    
    $check_limit = $conn->prepare("SELECT COUNT(*) as total_borrowed FROM borrowed_books WHERE user_id = ?");
    $check_limit->bind_param("i", $user_id);
    $check_limit->execute();
    $limit_result = $check_limit->get_result();
    $limit_data = $limit_result->fetch_assoc();
    $check_limit->close();

    if($limit_data['total_borrowed'] >= 3){
        error_log("User has reached borrowing limit");
        echo "You have reached the borrowing limit (3 books). Return a book before borrowing another.";
        exit();
    }
     
    $check_sql = $conn->prepare("SELECT quantity FROM books WHERE id = ?");
    $check_sql->bind_param("i", $books_id);
    $check_sql->execute();
    $check_result = $check_sql->get_result();
    $book = $check_result->fetch_assoc();
    $check_sql->close();
  

    if($book['quantity'] > 0){
        $update_sql = $conn->prepare("UPDATE books SET quantity =  quantity - 1 WHERE id = ?");
        $update_sql->bind_param("i",$books_id);
        $update_sql->execute();
        $update_sql->close();

        $borrow_sql = $conn->prepare("INSERT INTO borrowed_books (book_id, user_id) VALUES (?, ?)");
        $borrow_sql->bind_param("ii", $books_id, $user_id);
        if($borrow_sql->execute()){
            echo "Book borrowed successfully";
        } else {
            echo "Error borrowing book:".$borrow_sql->error;
        }
        $borrow_sql->close();
    } else {
        echo "Book out of stock";
    }

}
 ############# RETURN FUNCITIONALITY ###########################

if(isset($_GET["borrow_id"]) && isset($_GET["book_id"])) {
    $borrow_id = $_GET["borrow_id"];
    $book_id = $_GET["book_id"];
#echo "The Borrow ID is: " . $borrow_id;
#echo "The book ID is: " . $book_id;
 $update_sql = "UPDATE books SET quantity =  quantity + 1 WHERE id = ?";
 $update = $conn->prepare($update_sql);
 $update->bind_param("i",$book_id);

 if($update->execute()){
    echo "Book returned successfully";
 } else {
    echo "Error updating book quantity: " . $update->error;
 }
 $update->close();

  $sql = "DELETE FROM borrowed_books WHERE id=?";
  $sql_delete = $conn->prepare($sql);
  $sql_delete->bind_param("i",$borrow_id);

  if($sql_delete -> execute()){
     echo "Record deleted successfully";
  } else{
    echo "Error deleting borrowed record: " . $sql_delete->error;
  }
$sql_delete->close();
}

$sql = "SELECT borrowed_books.id AS borrow_id,
               borrowed_books.book_id, 
               books.title,
               books.author,
               books.genre,
               books.isbn,
               borrowed_books.borrow_date,
               borrowed_books.return_date,
               borrowed_books.penalty
        FROM borrowed_books
        JOIN books ON borrowed_books.book_id = books.id
         WHERE borrowed_books.user_id = ?";
       

$sql_select = $conn->prepare($sql);
$sql_select->bind_param("i", $_SESSION['user_id']);
$sql_select->execute();
$result = $sql_select->get_result();


 /**
  * if ($result->num_rows > 0) {
   * echo "Records found: " . $result->num_rows;
*} else {
   * echo "No records found for this user"; 
*}
  **/
 
?>


</div>
<div class="board" id="borrowed-books">
    <h3>Borrowed Books</h3>
    <table width="90%">
            <thead>
                <tr>
                     <th>Title</th>
                      <th>Author</th>
                      <th>Genre</th>
                      <th>ISBN</th>
                      <th>Borrow Date</th>
                      <th>Return Date</th>
                       <th>PENALTY</th>
                      <th>Action</th> 
</tr>
</thead>
<tbody>
    <?php
    while($row = $result->fetch_assoc()){
        echo"
<tr>
        <td>$row[title]</td>
        <td>$row[author]</td>
        <td>$row[genre]</td>
        <td>$row[isbn]</td>
        <td>$row[borrow_date]</td>
        <td>$row[return_date]</td>
        <td>$row[penalty]</td>
          <td>
             <button class='return-btn' return-borrow-id='{$row['borrow_id']}' return-book-id='{$row['book_id']}'>Return</button>
</td>
</tr>
";
    }
    ?>
</tbody>
</table>
</div>
</section>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Borrow Button
    document.querySelectorAll(".borrow-btn").forEach(button => {
        button.addEventListener('click', function() {
            let bookId = this.getAttribute("data-book-id");

            fetch("user_dash.php?borrow_id=" + bookId, {
                method: "GET",
            })
            .then(response => response.text())  
            .then(data => {
              let lines = data.split('\n');
                alert(lines[104]);
                location.reload(); 
            })  
             .catch(error => {
                console.error("Error borrowing book:", error);
                alert("Error borrowing book");
             
            });
             
         
        });
    });
})

    // Return Button
    document.querySelectorAll(".return-btn").forEach(button => {
        button.addEventListener("click", function() {
            let borrowId = this.getAttribute("return-borrow-id");
            let bookId = this.getAttribute("return-book-id");

            fetch(`user_dash.php?borrow_id=${borrowId}&book_id=${bookId}`, {
                method: "GET"
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error("Failed to return book.");
                }
               return alert("Returned book sucessfully");
            })
            .then(data => {
             location.reload(); 
            })
            .catch(error => {
                console.error("Error returning book:", error);
                alert("Error returning book.");
            });
        });
    });

</script>
 </body>
</html>


