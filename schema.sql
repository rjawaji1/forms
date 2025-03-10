CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT NOW(),
    updated_at DATETIME NOT NULL DEFAULT NOW()
);

INSERT INTO users (email, password) VALUES ('rjawaji@icloud.com', SHA('password'));

SELECT * FROM users WHERE email='rjawaji@icloud.com' AND password=SHA('password');