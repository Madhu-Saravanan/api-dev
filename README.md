### API Development Course by LAHTP

To get started, clone this repository to a proper document root. For XAMPP, this is `htdocs`. For private apache setup, its upto you how you configiure.

Right outside the document root, create a file called `env.json` and keep the contents of the file similar to the following. 

```
{
	"database": "apis",
	"username": "root",
	"password": "password",
	"server": "localhost"
}
```

This will be called by the API functions to get the database connection. 

This project is under development.

Database schema 

```
CREATE TABLE `apis`.`auth` 
(	`id` INT NOT NULL AUTO_INCREMENT , 
	`username` VARCHAR(45) NOT NULL , 
	`password` VARCHAR(512) NULL DEFAULT NULL , 
	`email` VARCHAR(45) NULL DEFAULT NULL ,
	`active` INT NULL DEFAULT NULL , 
	`token` VARCHAR(512) NOT NULL , 
	PRIMARY KEY (`id`), 
	UNIQUE `username` (`username`)) 
ENGINE = InnoDB;


ALTER TABLE `auth` ADD `signup_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `token`;

```