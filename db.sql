CREATE DATABASE app DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

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

INSERT INTO user (user_id, last_name, first_name, user_name, user_email, password, activation, created_on, last_modified_on) VALUES
(NULL, 'John', 'Doe', 'johndoe', 'johndoe@gmail.com', 'Abc123456/*', '139224bed64ea04b31ecc10ef84771a4' , NOW(), NOW());

CREATE TABLE forgot_password (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	user_id INT UNSIGNED NOT NULL,
	reset_key CHAR(32) NOT NULL,
	time INT(11) NOT NULL,
	status VARCHAR(7) NOT NULL,
	created DATETIME NOT NULL, 
	modified DATETIME NOT NULL
) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;
