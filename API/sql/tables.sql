
create table user (
    user_id int(11) not null auto_increment,
    first_name varchar(50) default '',
    last_name varchar(50) default '',
    email varchar(100) not null unique,
    password varbinary(64) not null,
    salt varchar(36) default null,
    locked_until datetime default null,
    primary key (user_id)
);

alter table user
    add use_password tinyint(1) default 1,
    add use_face tinyint(1) default 0,
    add use_code tinyint(1) default 0;

alter table user add face_uploaded tinyint default 0;

create table developer (
	developer_id int not null auto_increment,
	client_id varchar(50),
	client_secret varchar(50),
	email varchar(100) not null unique,
	password VARBINARY(64) not null,
    company varchar(100) default '',
    primary key (developer_id)
);

create table token (
    token_id int primary key auto_increment,
    developer_id int not null,
    user_id int not null,
    token varchar(50) default '',
    type enum('auth', 'access') default 'auth',
    permission_email tinyint default 0,
    permission_firstname tinyint default 0,
    permission_lastname tinyint default 0,
    foreign key (user_id) references user(user_id),
    foreign key (developer_id) references developer(developer_id)
);

create table face (
    face_id int primary key auto_increment,
    user_id int not null,
    path varchar(100),
    created datetime default current_timestamp,
    foreign key (user_id) references user(user_id)
);

create table logincode (
   logincode_id int primary key auto_increment,
   user_id int not null,
   code varchar(50) default '',
   created datetime default current_timestamp,
   foreign key (user_id) references user(user_id)
);