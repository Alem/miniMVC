-- Setup tests table for testing CRUD operations
CREATE TABLE tests
(
	id 		INTEGER 	UNSIGNED NOT NULL AUTO_INCREMENT,
	test 		VARCHAR(128) 	NOT NULL,

	PRIMARY KEY (id)
);
