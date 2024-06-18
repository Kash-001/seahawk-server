<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: ../errors/unauthenticated.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/tools.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;700&display=swap">
    <link rel="icon" type="image/x-icon" href="../deps/media/seahawks.ico">
    <title>SeaHawks Panel</title>
</head>
<body>

    <div id="content">
        <!-- Your main website content goes here -->
        <h1>SeaHawks probes manager</h1>
        <button id="toolsButton"><?php echo htmlspecialchars($_SESSION["username"]); ?> | Access probes management</button>
        <p>Probes settings to set here.</p>
    </div>

    <div id="popupContainer">
        <!-- The actual pop-up content goes here -->
        <div id="popupContent">
            <span class="close" onclick="closePopup()">&times;</span>
            <h2>Tools</h2>
    
            <!-- "Supervise" section with a textbox -->
            <div>
                <label for="superviseTextbox">Enter IP:</label>
                <input type="text" id="superviseTextbox" placeholder="Enter IP" />
                <button onclick="supervise()" style="color: green;">Supervise</button>
            </div>
    
            <!-- "Unmanage" section with a dropdown list -->
            <div>
                <label for="dropdown1">Select IP:</label>
                <select id="dropdown1"></select>
                <button onclick="unmanage()" style="color: red;">Unmanage</button>
            </div>
        </div>
    </div>


    <div id="column-list">
        <h2>Registered Clients</h2>
        <ul>
            <li><a href="#" class="button">192.168.1.1</a></li>
            <li><a href="#" class="button">192.168.1.2</a></li>
            <li><a href="#" class="button">192.168.1.3</a></li>
            <li><a href="#" class="button">192.168.1.4</a></li>
            <li><a href="#" class="button">192.168.1.5</a></li>
            <li><a href="#" class="button">192.168.1.6</a></li>
        </ul>
    </div>

    <script src="scripts/tools.js"></script>

</body>
</html>
