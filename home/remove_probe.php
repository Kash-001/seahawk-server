<?php
$servername = "localhost";
$dbname = "SeahawkDB";
$dbusername = "SeahawkAdmin";
$dbpassword = "admSeahawk1!";

$probe_ip = $_POST["probe_ip"];

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("DELETE FROM active_probes WHERE probe_ip = ?");
$stmt->bind_param("s", $probe_ip);

if ($stmt->execute()) {
    echo "Probe removed successfully";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>