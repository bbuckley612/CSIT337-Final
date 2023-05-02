-- CREATE DATABASE MSU_Movies
DROP DATABASE IF EXISTS PayPartner;
CREATE DATABASE PayPartner;
USE PayPartner;


-- CREATE TABLE users;
CREATE TABLE users (
id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
first VARCHAR(50) NOT NULL,
last VARCHAR(50) NOT NULL,
email VARCHAR(255) NOT NULL UNIQUE,
hash VARCHAR(150) NOT NULL,
cookie VARCHAR(150),
created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
updated DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
enabled BIT(1) DEFAULT 1
);

-- CREATE TABLE accounts;
CREATE TABLE accounts (
id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
user_id INT(11) NOT NULL,
routing_num VARCHAR(50) DEFAULT NULL,
account_num VARCHAR(50) DEFAULT NULL,
name VARCHAR(150) NOT NULL,
priority INT(2) DEFAULT 0,
created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
updated DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
enabled BIT(1) DEFAULT 1,
FOREIGN KEY (user_id) REFERENCES users(id)
);

-- CREATE TABLE transactions;
CREATE TABLE transactions (
id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
user_id INT(11) NOT NULL,
recipient_id INT(11) NOT NULL,
amount DECIMAL(10,2) NOT NULL,
description VARCHAR(150) NOT NULL,
created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (user_id) REFERENCES users(id),
FOREIGN KEY (recipient_id) REFERENCES accounts(id)
);

-- CREATE TABLE invoices;
CREATE TABLE invoices (
id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
user_id INT(11) NOT NULL,
recipient_id INT(11) NOT NULL,
amount DECIMAL(10,2) NOT NULL,
description VARCHAR(150) NOT NULL,
status INT(1) NOT NULL,
created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
updated DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (user_id) REFERENCES users(id),
FOREIGN KEY (recipient_id) REFERENCES accounts(id)
);

-- Default user1
INSERT INTO `users` (`first`, `last`, `email`, `hash`)
VALUES ('Alice', 'Smith', 'alice@example.com', '$2y$10$oEaMUDzFJ3unxY9B5qRHouq7wZ3cueKkGAtyfqWIpvwaVb65R/lwK');
INSERT INTO `accounts` (`user_id`, `name`, `priority`) 
VALUES ('1', 'Alice\'s PayPartner Balance', '1');

-- Default user2
INSERT INTO `users` (`first`, `last`, `email`, `hash`)
VALUES ('Bob', 'Lee', 'bob@example.com', '$2y$10$oEaMUDzFJ3unxY9B5qRHouq7wZ3cueKkGAtyfqWIpvwaVb65R/lwK');
INSERT INTO `accounts` (`user_id`, `name`, `priority`) 
VALUES ('2', 'Bob\'s PayPartner Balance', '1');

-- create the users and grant priveleges to those users
GRANT SELECT, INSERT, DELETE, UPDATE
ON PayPartner.*
TO paypartner@localhost
IDENTIFIED BY 'E5oNd4dGXtPfEy7kArox';

GRANT SELECT
ON Users
TO paypartner@localhost
IDENTIFIED BY 'E5oNd4dGXtPfEy7kArox';