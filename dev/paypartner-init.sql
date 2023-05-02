-- CREATE DATABASE MSU_Movies
DROP DATABASE IF EXISTS PayPartner;
CREATE DATABASE PayPartner;
USE PayPartner;


-- CREATE TABLE users;
CREATE TABLE users (
uid INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
first varchar(30) NOT NULL,
last varchar(30) NOT NULL,
email varchar(50) NOT NULL UNIQUE,
hash varchar(150) NOT NULL,
cookie varchar(50),
created DATE NOT NULL DEFAULT CURRENT_TIMESTAMP,
updated DATE NOT NULL DEFAULT CURRENT_TIMESTAMP,
enabled BIT(1) DEFAULT 1
);

-- CREATE TABLE transactions;
CREATE TABLE transactions (
id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
uid INT(11) NOT NULL,
sender INT(11) NOT NULL,
isSenderPeer BIT(1),
recipient INT(11) NOT NULL,
isRecipientPeer BIT(1),
amount FLOAT(7,2) NOT NULL,
description varchar(150) NOT NULL,
created DATE NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- CREATE TABLE invoices;
CREATE TABLE invoices (
id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
uid INT(11) NOT NULL,
sender INT(11) NOT NULL,
recipient INT(11) NOT NULL,
amount FLOAT(7,2) NOT NULL,
desc varchar(150) NOT NULL,
created DATE NOT NULL DEFAULT CURRENT_TIMESTAMP,
updated DATE NOT NULL DEFAULT CURRENT_TIMESTAMP,
status INT(1)
);


-- create the users and grant priveleges to those users
GRANT SELECT, INSERT, DELETE, UPDATE
ON PayPartner.*
TO paypartner@localhost
IDENTIFIED BY 'E5oNd4dGXtPfEy7kArox';

GRANT SELECT
ON Users
TO paypartner@localhost
IDENTIFIED BY 'E5oNd4dGXtPfEy7kArox';