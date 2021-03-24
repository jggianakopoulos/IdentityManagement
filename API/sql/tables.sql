
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

create table developer (
	developer_id int not null auto_increment,
	client_id varchar(50),
	client_secret varchar(50),
	email varchar(100) not null unique,
	password VARBINARY(64) not null,
    company varchar(100) default '',
    primary key (developer_id)
);

create table authtoken (
    authtoken_id int primary key auto_increment,
    developer_id int not null,
    user_id int not null,
    token varchar(50) default '',
    foreign key (user_id) references user(user_id)
    foreign key (developer_id) references developer(developer_id)
);

create table accesstoken (
    accesstoken_id int primary key auto_increment,
    user_id int not null,
    token varchar(50) default '',
    foreign key (user_id) references user(user_id)
);