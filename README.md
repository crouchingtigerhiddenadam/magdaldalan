# Magdaldalan
A super lightweight chat program in PHP.  
Currently in very early stages of development.

## Database Setup

### Create Database
```
CREATE DATABASE magdaldalan;

CREATE USER 'magdaldalan'@'localhost' IDENTIFIED BY 'password';

GRANT ALL PRIVILEGES ON `magdaldalan`.* TO 'magdaldalan'@'localhost';

FLUSH PRIVILEGES;

USE magdaldalan;

CREATE TABLE message (
    id int UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id int NOT NULL,
    content nvarchar(2000) NOT NULL,
    creation_datetime_utc datetime NOT NULL,
    read_datetime_utc datetime NULL
);
```

### Clear Messages Table
```
USE magdaldalan;

TRUNCATE TABLE message;
```