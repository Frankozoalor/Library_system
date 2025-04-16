<?php

session_start();
require_once 'config.php';

if(isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

      $checkEmail = $conn->prepare("SELECT email FROM users WHERE email = ?");
      $checkEmail->bind_param("s", $email);
      $checkEmail->execute();
      $checkEmailresult = $checkEmail->get_result();
    if($checkEmailresult->num_rows > 0){
        $_SESSION['register_error'] = 'Email is already registered!' .$conn->error;
         echo "Error:" . $conn->error;
        $_SESSION['active_form'] = 'register';
    } else {
        $insert = $conn->prepare("INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)");
        $insert->bind_param("ssss", $name, $email, $password, $role);
        $insert->execute();
        $insert->close();
    }
    $checkEmailresult->close();
    header("Location: index.php");
    exit();
}

if(isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
   $login = $conn->prepare("SELECT * FROM users WHERE email = ?");
   $login->bind_param("s",$email);
   $login->execute();
   $result = $login->get_result();
    if ($result -> num_rows > 0){
        $user = $result -> fetch_assoc();
        if(password_verify($password, $user['password'])){
             $_SESSION['user_id'] = $user['id']; 
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            if($user['role'] == 'admin' ){
                header('Location: admin/admin.php');
            } else {
                header('Location: users/user_dash.php');
            }
            exit();
        }
    }

    $_SESSION['login_error'] = 'Incorrect email or password';
    $_SESSION['active_form'] = 'login';
    header('Location: index.php');
    exit();
}
?>