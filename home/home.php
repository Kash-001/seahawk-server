<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: ../errors/unauthenticated.html");
    exit();
}

$servername = "localhost";
$dbname = "SeahawkDB";
$dbusername = "SeahawkAdmin";
$dbpassword = "admSeahawk1!";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT probe_ip, probe_hostname, probe_client_version, date_added FROM active_probes";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/tools.css">
    <title>Panel - SeaHawks Inc.</title>
</head>
<body>
    <div id="messageBar" class="hidden"></div>
    <img src="../deps/media/settings-icon.png" id="settingsButton" class="settings-button" alt="Settings">
    <h1>Seahawks Harvesters</h1>
    <table>
        <tr>
            <th>Probe IP</th>
            <th>Hostname</th>
            <th>Client Version</th>
            <th>Date Added</th>
            <th>Status</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["probe_ip"] . "</td>";
                echo "<td>" . $row["probe_hostname"] . "</td>";
                echo "<td>" . $row["probe_client_version"] . "</td>";
                echo "<td>" . $row["date_added"] . "</td>";
                echo "<td class='status' data-ip='" . $row["probe_ip"] . "'>Checking...</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No active probes found</td></tr>";
        }
        $conn->close();
        ?>
    </table>

    <div id="toolsContainer">
        <div id="popupContainer">
            <div id="popupContent">
                <span class="close" onclick="closePopup()">&times;</span>
                <div class="form-group">
                    <h2>Add Probe</h2>
                    <form id="addProbeForm">
                        <label for="probeIp">Probe IP:</label>
                        <input type="text" id="probeIp" name="probeIp" required>

                        <label for="probeHostname">Probe Hostname:</label>
                        <input type="text" id="probeHostname" name="probeHostname" required>

                        <label for="probeClientVersion">Probe Client Version:</label>
                        <input type="text" id="probeClientVersion" name="probeClientVersion" required>

                        <button type="submit">Add Probe</button>
                    </form>
                </div>
                <div class="form-group">
                    <h2>Remove Probe</h2>
                    <form id="removeProbeForm">
                        <label for="probeIpToRemove">Probe IP:</label>
                        <input type="text" id="probeIpToRemove" name="probeIpToRemove" required>

                        <button type="submit">Remove Probe</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="scripts/tools.js"></script>
</body>
</html>
