# Magdaldalan
A super lightweight chat program in PHP designed to work with/without JavaScript enabled on the browser.  
Currently in very early stages of development.

## Clone Project

At the command prompt type:
```
mkdir ~/sources
cd ~/sources
git clone https://github.com/crouchingtigerhiddenadam/magdaldalan
```

## Database Setup

### Create Database

At the command prompt type:
```
sudo mysql -u root
```

From the MariaDB prompt type:
```
CREATE DATABASE magdaldalan;

CREATE USER 'magdaldalan'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON `magdaldalan`.* TO 'magdaldalan'@'localhost';
FLUSH PRIVILEGES;

USE magdaldalan;

CREATE TABLE attachment (
    id int UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    message_id int NULL,
    filename nvarchar(255) NOT NULL,
    store nvarchar(1024) NOT NULL
);

CREATE TABLE message (
    id int UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    sender_user_id int NOT NULL,
    recipient_user_id int NULL,
    content nvarchar(2000) NOT NULL,
    creation_datetime_utc datetime NOT NULL,
    read_datetime_utc datetime NULL
);

CREATE TABLE user (
    id int UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username nvarchar(100) NOT NULL,
    password_hash nvarchar(100) NOT NULL
);
```

### Add Users

At the command prompt type:
```
sudo mysql -u root
```

From the MariaDB prompt type:
```
INSERT INTO user(username, password_hash) VALUES('alice', PASSWORD('password123'));
INSERT INTO user(username, password_hash) VALUES('bob', PASSWORD('password456'));
```

### Clear Messages Table

At the command prompt type:
```
sudo mysql -u root
```

From the MariaDB prompt type:
```
USE magdaldalan;
TRUNCATE TABLE message;
```

## Setup NGINX

At the command prompt type:
```
sudo ln -s ~/sources/magdaldalan /var/www/html/magdaldalan
```