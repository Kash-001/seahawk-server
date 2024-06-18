<?php
session_start();
$servername = "localhost";
$dbname = "SeahawkDB";
$dbusername = "SeahawkAdmin";
$dbpassword = "admSeahawk1!";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();

    if (password_verify($password, $hashed_password)) {
        $_SESSION["username"] = $username;
        header("Location: home/home.php");
        exit();
    } else {
        header("Location: errors/bad_credentials.html");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>