let ips = [];
let isPopupOpen = false;

function openPopup() {
    document.getElementById("popupContainer").style.display = "flex";

    populateDropdown();
    scanAllHosts(true);
}

function closePopup() {
    document.getElementById("popupContainer").style.display = "none";
}

function populateDropdown() {
    const dropdown1 = document.getElementById("dropdown1");
    dropdown1.innerHTML = "";

    ips.forEach(ip => {
        const option1 = document.createElement("option");
        option1.value = ip;
        option1.text = ip;
        dropdown1.add(option1);
    });
}

function supervise() {
    const superviseTextbox = document.getElementById("superviseTextbox");
    const newIP = superviseTextbox.value.trim();

    const ipAddressMatch = newIP.match(/\b(?:\d{1,3}\.){3}\d{1,3}\b/);
    const ipAddress = ipAddressMatch ? ipAddressMatch[0] : null;

    if (ipAddress && !ips.includes(ipAddress)) {
        ips.push(ipAddress);
        addIPToList(ipAddress);
        scanAllHosts(true);
    }

    superviseTextbox.value = "";
}


function unmanage() {
    const dropdown1 = document.getElementById("dropdown1");
    const selectedIP = dropdown1.value;

    ips = ips.filter(ip => ip !== selectedIP);

    removeIPFromList(selectedIP);
    scanAllHosts(true);
}
  

function addIPToList(ip) {
    const columnList = document.getElementById("column-list");
    const listItem = document.createElement("li");
    const link = document.createElement("a");
  
    link.href = "#";
    link.classList.add("button");
    link.textContent = ip;
  
    listItem.appendChild(link);
    columnList.querySelector("ul").appendChild(listItem);
  
    const option = document.createElement("option");
    option.value = ip;
    option.text = ip;
    document.getElementById("dropdown1").appendChild(option);
  }

function removeIPFromList(ip) {
    const columnList = document.getElementById("column-list");
    const listItems = columnList.querySelectorAll('ul li a.button');

    for (let i = 0; i < listItems.length; i++) {
        if (listItems[i].textContent.includes(ip)) {
            listItems[i].parentNode.parentNode.removeChild(listItems[i].parentNode);
            break;
        }
    }
}

function scanAllHosts(forceRescan) {
    const ipLinks = document.querySelectorAll("#column-list ul li a.button");
  
    ipLinks.forEach(function (ipLink) {
      const ipAddressMatch = ipLink.textContent.trim().match(/\b(?:\d{1,3}\.){3}\d{1,3}\b/);
      const ipAddress = ipAddressMatch ? ipAddressMatch[0] : null;
  
      if (ipAddress && !isPopupOpen && (forceRescan || ipLink.dataset.scanStatus !== "scanned")) {
        const url = "http://" + ipAddress + ":53597/health";
        console.log("URL:", url);
        fetch(url)
          .then(function (response) {
            return response.json();
          })
          .then(function (data) {
            console.log(`IP: ${ipAddress}, Status: ${data.status}`);
  
            if (data.status === "online") {
              ipLink.style.color = "green";
              if (!ipLink.innerHTML.includes(data.server_name)) {
                ipLink.innerHTML += `<br>${data.server_name}<br>${data.version}`;
              }
            } else {
              ipLink.style.color = "red";
            }
          })
          .catch(function (error) {
            console.error(`Error during the API request for IP ${ipAddress}:`, error);
            ipLink.style.color = "red";
          })
          .finally(function () {
            ipLink.dataset.scanStatus = "scanned";
          });
      }
    });
  
    populateDropdown();
  }

function initializeIPS() {
    const ipLinks = document.querySelectorAll("#column-list ul li a.button");
    ips = Array.from(ipLinks).map(ipLink => {
        const ipAddressMatch = ipLink.textContent.trim().match(/\b(?:\d{1,3}\.){3}\d{1,3}\b/);
        const ipAddress = ipAddressMatch ? ipAddressMatch[0] : null;
        return ipAddress;
    });
}

function displayLastScanData(event) {
  event.preventDefault();
  const selectedIPText = event.target.textContent.trim();

  let selectedIP = "";
  for (const ip of ips) {
      if (selectedIPText.includes(ip)) {
          selectedIP = ip;
          break;
      }
  }

  if (selectedIP) {
      const url = `http://${selectedIP}:53597/lastscan`;

      fetch(url)
          .then(response => response.json())
          .then(data => {
              const contentDiv = document.getElementById("content");

              const scanResultElements = contentDiv.querySelectorAll('.scan-result');
              scanResultElements.forEach(element => {
                  element.remove();
              });

              const ipHeader = document.createElement("h2");
              ipHeader.classList.add('scan-result');
              const lastScanTime = document.createElement("p");
              lastScanTime.classList.add('scan-result');
              const totalOnlineMachines = document.createElement("p");
              totalOnlineMachines.classList.add('scan-result');
              const scanResultsList = document.createElement("ul");
              scanResultsList.classList.add('scan-result');
              ipHeader.textContent = `${selectedIP} / ${data.hostname}`;
              lastScanTime.textContent = `Last scan date: ${data.last_scan_time}`;
              totalOnlineMachines.textContent = `Total online machines: ${data.clients}`;

              for (const [ip, ports] of Object.entries(data.scan_results)) {
                  const listItem = document.createElement("li");
                  listItem.textContent = `${ip}:`;

                  const portsList = document.createElement("ul");
                  const portsArray = ports.split(" ");

                  portsArray.forEach(port => {
                      const portItem = document.createElement("li");
                      portItem.textContent = port;
                      portsList.appendChild(portItem);
                  });

                  listItem.appendChild(portsList);
                  scanResultsList.appendChild(listItem);
              }

              contentDiv.appendChild(ipHeader);
              contentDiv.appendChild(lastScanTime);
              contentDiv.appendChild(totalOnlineMachines);
              contentDiv.appendChild(scanResultsList);
          })
          .catch(error => {
              console.error(`Error during the API request for IP ${selectedIP}:`, error);
          });
  } else {
      console.error("Could not find a matching IP address in the 'ips' array.");
  }
}

document.getElementById("toolsButton").addEventListener("click", openPopup);

setInterval(function () {
    scanAllHosts(false);
    populateDropdown();
}, 3000);

const ipLinks = document.querySelectorAll("#column-list ul li a.button");
ipLinks.forEach(ipLink => {
    ipLink.addEventListener("click", displayLastScanData);
});

window.addEventListener("load", function() {
    initializeIPS();
});
