<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$user = "root";
$password = "";
$database = "library_database";

$conn = new mysqli($host, $user, $password, $database);

if($conn -> connect_error){
    die("connection failed:".$conn -> connect_error);
} else {
    //echo "Connected sucessfully!";
}
?>