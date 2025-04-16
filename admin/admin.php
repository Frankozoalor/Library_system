<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link
   rel="stylesheet"
   href="admin.css" />
  <link
   href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap"
   rel="stylesheet" />
</head>
<body>

  <div class="navbar">
    <div class="logo">📚 Admin Panel</div>
    <div class="links">
      <a href="/Library_system/">Home</a>
      <a href="../logout.php">Logout</a>
    </div>
  </div>

  <div class="main">
    <a href = "/library_system/admin/users/users.php">
    <div class="box">
      <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" alt="User Icon">
      <h3>User Management</h3>
      <p>Add, edit, and manage user accounts</p>
    </div>
</a>

<a href = "/library_system/admin/books/books.php">
    <div class="box">
      <img src="https://cdn-icons-png.flaticon.com/512/3342/3342137.png" alt="Books Icon">
      <h3>Book Management</h3>
      <p>View, edit, and manage book records</p>
    </div>
</a>
  </div>

</body>
</html>
