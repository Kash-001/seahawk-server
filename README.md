**SEAHAWK SERVER DEPLOY**

- git clone into folder > /var/www/
- creat apache2 vhost
- paste apache2 config in it

**MACHINE CONFIGURATION**

```sh
sudo apt update
sudo apt upgrade -y

sudo apt install apache2 -y

sudo apt install php8.1 libapache2-mod-php8.1 php8.1-mysql php8.1-cli php8.1-curl php8.1-cgi php8.1-gd php8.1-mbstring php8.1-xml php8.1-zip -y

sudo apt install mysql-server -y

sudo apt install mariadb-server mariadb-client -y

sudo mysql_secure_installation

sudo mysql -u root -p

sudo apt install phpmyadmin -y

sudo systemctl restart apache2
```

**MYSQL CONFIGURATION**

```sql
CREATE DATABASE SeahawkDB;

CREATE USER 'SeahawkAdmin'@'localhost' IDENTIFIED BY 'admSeahawk1!';

GRANT ALL PRIVILEGES ON SeahawkDB.* TO 'SeahawkAdmin'@'localhost';

USE SeahawkDB;

CREATE TABLE active_probes (
    probe_ip VARCHAR(15) NOT NULL,
    probe_hostname VARCHAR(255) NOT NULL,
    probe_client_version VARCHAR(10) NOT NULL,
    date_added DATE NOT NULL,
    PRIMARY KEY (probe_ip)
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

FLUSH PRIVILEGES;
EXIT;
```
