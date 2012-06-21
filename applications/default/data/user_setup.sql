-- Setup users table for testing user management
CREATE TABLE users
(
	id 		INTEGER 	UNSIGNED NOT NULL AUTO_INCREMENT,
	user 		VARCHAR(128) 	NOT NULL,
	password	VARCHAR(128) 	NOT NULL,
	email 		VARCHAR(128) 	NOT NULL,
	type 		INTEGER 	UNSIGNED NOT NULL,

	PRIMARY KEY (id)
);




