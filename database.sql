CREATE database TUGASREGISTER;

USE TUGASREGISTER;

CREATE TABLE Users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    email VARCHAR(50),
    password VARCHAR (255),
    role VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO Users (username, email, password, role, created_at)
VALUES ('admin', 'admin@gmail.com', 'admin123', 'admin', NOW());
