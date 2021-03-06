USE buyBook;

CREATE TABLE users (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT KEY,
  type ENUM('member','admin') NOT NULL,
  username VARCHAR(30) NOT NULL,
  email VARCHAR(80) NOT NULL,
  pass VARBINARY(32) DEFAULT NULL,
  first_name VARCHAR(20) NOT NULL,
  last_name VARCHAR(40) NOT NULL,
  date_expires DATE NOT NULL
);

CREATE TABLE pdfs (
  pdf_id INT UNSIGNED NOT NULL AUTO_INCREMENT KEY,
  
title VARCHAR(30) NOT NULL,

file_path  VARCHAR(104) NOT NULL
);

