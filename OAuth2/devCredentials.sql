-- noinspection SqlDialectInspectionForFile

-- noinspection SqlNoDataSourceInspectionForFile

create table developer (
	DeveloperID int autoincrement,
	ClientID varchar(50),
	ClientSecret varchar(50)
	Email varchar(255) not null,
	PasswordHash VARBINARY(64) not null,
    Company varchar(50) default null,
    primary key developerID
);

create table authToken(
    authTokenID int primary key auto_increment,
    UserID int not null,
    token varchar(50) default null,
    FOREIGN KEY (UserID) REFERENCES User(UserID)
);