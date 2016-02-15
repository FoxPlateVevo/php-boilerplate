create database boilerplate_db;
drop database boilerplate_db;

use boilerplate_db;

create table news(
	id int primary key auto_increment, 
    title varchar(500) not null,
    description mediumtext,
    date_create date,
    status varchar(50)
);