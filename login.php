<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $expectedUsername = "root";
    $expectedPassword = "root";

    if ($username == $expectedUsername && $password == $expectedPassword) {
        $_SESSION["username"] = $username;
        header("Location: home/home.php");
        exit();
    } else {
        header("Location: errors/bad_credentials.html");
        exit(); 
    }
}
?>