
create database chat
DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

use chat;

create table user
(
	id 		int 		not null primary key auto_increment,
	nome 	varchar(20) not null,
	email 	varchar(20) not null unique
);

create table state
(
	id int not null primary key auto_increment,
	id_user int not null,
	id_con
);

create table timeline
(
	id 		int 			not null primary key auto_increment,
	id_user int 			not null,
	message text 			not null,
	date 	datetime 		not null,
	constraint `fk_user_id` foreign key (id_user) references user(id)
);

create table message
(
	id 				int 		not null primary key auto_increment,
	date_inicial 	datetime 	not null
);

create table message_user
(	
	id_message 		int 			not null primary key,
	id_recipient 	int 			not null,
	id_sender 		int 			not null,
	message 		text 			not null,
	date_message 	datetime 		not null,
	constraint `fk_user_id_sender` 		foreign key (id_sender) 	references user(id),
	constraint `fk_user_id_recipient` 	foreign key (id_recipient) 	references user(id),
	constraint `fk_message_id` 			foreign key (id_message) 	references user(id)
);

insert into user (nome, email) values ('Giovani','g@gmail.com');
insert into user (nome, email) values ('Bruno Rodrigues','b@gmail.com');
insert into user (nome, email) values ('Mauro','m@gmail.com');

SELECT * FROM timeline;
delete from timeline;
truncate timeline;

select * from user;
select * from timeline order by date desc;

