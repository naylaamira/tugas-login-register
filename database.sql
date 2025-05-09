
CREATE database TUGASREGISTER;

USE TUGASREGISTER;

CREATE TABLE Users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    email VARCHAR(50),
    password VARCHAR (50),
    role VARCHAR(50),
    created_at TIMESTAMP
);

INSERT INTO Users(id, username, email, password, role, created_at)
VALUES(1, 'admin', 'admin@gmail.com', 'admin123', 'admin', NOW());
