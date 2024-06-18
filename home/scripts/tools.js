document.getElementById("settingsButton").addEventListener("click", function() {
    document.getElementById("popupContainer").style.display = "flex";
});

function closePopup() {
    document.getElementById("popupContainer").style.display = "none";
}

function showMessage(message, type) {
    const messageBar = document.getElementById("messageBar");
    messageBar.textContent = message;
    messageBar.className = type;
    messageBar.classList.remove("hidden");
    setTimeout(() => {
        messageBar.classList.add("hidden");
    }, 3000);
}

document.getElementById("addProbeForm").addEventListener("submit", function(event) {
    event.preventDefault();

    let probeIp = document.getElementById("probeIp").value;
    let probeHostname = document.getElementById("probeHostname").value;
    let probeClientVersion = document.getElementById("probeClientVersion").value;

    if (!probeIp || !probeHostname || !probeClientVersion) {
        showMessage("All fields are required", "error");
        return;
    }

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "add_probe.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
            if (this.responseText.includes("Error")) {
                showMessage(this.responseText, "error");
            } else {
                showMessage("Probe added successfully", "success");
                location.reload();
            }
        }
    };
    xhr.send("probe_ip=" + probeIp + "&probe_hostname=" + probeHostname + "&probe_client_version=" + probeClientVersion);
});

document.getElementById("removeProbeForm").addEventListener("submit", function(event) {
    event.preventDefault();

    let probeIp = document.getElementById("probeIpToRemove").value;

    if (!probeIp) {
        showMessage("Probe IP is required", "error");
        return;
    }

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "remove_probe.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
            if (this.responseText.includes("Error")) {
                showMessage(this.responseText, "error");
            } else {
                showMessage("Probe removed successfully", "success");
                location.reload();
            }
        }
    };
    xhr.send("probe_ip=" + probeIp);
});

// Function to poll probe status
function pollProbes() {
    const statuses = document.querySelectorAll('.status');
    statuses.forEach(status => {
        const ip = status.getAttribute('data-ip');
        fetch(`http://${ip}:53597/health`)
            .then(response => response.json())
            .then(data => {
                status.textContent = "Online";
                status.style.color = "#00ff00";
            })
            .catch(error => {
                status.textContent = "Offline";
                status.style.color = "#ff0000";
            });
    });
}

// Poll probes every 5 seconds
setInterval(pollProbes, 5000);

// Initial poll
pollProbes();
