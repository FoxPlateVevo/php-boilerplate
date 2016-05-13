create database boilerplate_db;

use boilerplate_db;

create table `new`(
    new_id      int primary key auto_increment, 
    title       varchar(500) not null,
    description mediumtext null,
    date_create date not null,
    status      varchar(50)
);