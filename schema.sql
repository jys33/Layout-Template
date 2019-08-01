CREATE DATABASE app DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
DROP TABLE IF EXISTS user;
DROP TABLE IF EXISTS post;
DROP TABLE IF EXISTS forgot_password;

USE app;

CREATE TABLE user (
	user_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	last_name VARCHAR(50) NOT NULL,
	first_name VARCHAR(50) NOT NULL,
	user_name VARCHAR(50) NOT NULL,
	user_email VARCHAR(50) NOT NULL,
	password VARCHAR(255) NOT NULL,
	activation CHAR(32) NOT NULL,
	created_on DATETIME NOT NULL,
	last_modified_on DATETIME NOT NULL,
    deleted TINYINT(1) NOT NULL DEFAULT 0,
    UNIQUE KEY user_email_unique (user_email),
    UNIQUE KEY user_name_unique (user_name)
) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;

CREATE TABLE post (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	author_id INT UNSIGNED NOT NULL,
	created_on INT(10) NOT NULL,
	last_modified_on DATETIME NOT NULL,
	title VARCHAR(255) NOT NULL,
	body TEXT NOT NULL,
	FOREIGN KEY (author_id) REFERENCES user (user_id)
) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;

CREATE TABLE forgot_password (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	user_id INT UNSIGNED NOT NULL,
	reset_key CHAR(32) NOT NULL,
	time INT(11) NOT NULL,
	status VARCHAR(7) NOT NULL,
	created_on DATETIME NOT NULL, 
	modified DATETIME NOT NULL
) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;