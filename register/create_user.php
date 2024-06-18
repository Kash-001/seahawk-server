<?php
$servername = "localhost";
$dbname = "SeahawkDB";
$dbusername = "SeahawkAdmin";
$dbpassword = "admSeahawk1!";
$token = "seahawk-adm";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $userToken = $_POST["token"];

    if ($userToken !== $token) {
        echo "Invalid token.";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);

    if ($stmt->execute()) {
        header("Location: ../index.html");
    } else {
        header("Location: ../errors/bad_token.html");
    }

    $stmt->close();
    $conn->close();
}
?>