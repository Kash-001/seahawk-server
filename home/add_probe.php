<?php
$servername = "localhost";
$dbname = "SeahawkDB";
$dbusername = "SeahawkAdmin";
$dbpassword = "admSeahawk1!";

$probe_ip = $_POST["probe_ip"];
$probe_hostname = $_POST["probe_hostname"];
$probe_client_version = $_POST["probe_client_version"];
$date_added = date('Y-m-d');

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("INSERT INTO active_probes (probe_ip, probe_hostname, probe_client_version, date_added) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $probe_ip, $probe_hostname, $probe_client_version, $date_added);

if ($stmt->execute()) {
    echo "Probe added successfully";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>