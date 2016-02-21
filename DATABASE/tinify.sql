
CREATE DATABASE tinify CHARACTER SET utf8 COLLATE utf8_bin;
use tinify;


CREATE TABLE users
(
	user_id int AUTO_INCREMENT PRIMARY KEY,
	username varchar(32) NOT NULL,
	password varchar(255) NOT NULL,
	name varchar(64) NOT NULL,
	email varchar(255) NOT NULL,
	registered_on timestamp default CURRENT_TIMESTAMP,
	is_registered bit(1) default 1,
	is_active bit(1) default 1,
	ip varchar(255),
	device_id varchar(255),
	UNIQUE(username, email)
);


CREATE TABLE sess
(
	ses_id varchar(255),
	user_id int NOT NULL,
	time_created timestamp NOT NULL default CURRENT_TIMESTAMP,
	time_updated timestamp,
	time_expire timestamp, -- NOT NULL on update DATE_SUB(NOW(), INTERVAL 24 HOUR)
	ip varchar(255),
	device_id varchar(255),
	PRIMARY KEY (ses_id),
	CONSTRAINT fk_sess_user_id FOREIGN KEY (user_id) REFERENCES users(user_id)
		ON DELETE RESTRICT 
		ON UPDATE CASCADE
);

CREATE TABLE contact
(
	contact_id int AUTO_INCREMENT PRIMARY KEY,
	message text,
	time_recv timestamp NOT NULL default CURRENT_TIMESTAMP,
	email varchar(255) NOT NULL,
	ip varchar(255),
	device_id varchar(255)
);

CREATE TABLE short
(
	short_id int,
	short_code varchar(20) NOT NULL,
	user_id int NOT NULL,
	time_created timestamp NOT NULL default CURRENT_TIMESTAMP,
	url_to text not null,
	ip varchar(255),
	device_id varchar(255),
	PRIMARY KEY (short_id),
	UNIQUE (short_code),
	CONSTRAINT fk_short_user_id FOREIGN KEY (user_id) REFERENCES users(user_id)
		ON DELETE RESTRICT 
		ON UPDATE CASCADE
);

CREATE TABLE visit
(
	visit_id int AUTO_INCREMENT,
	short_id int not null,
	time_visited timestamp NOT NULL default CURRENT_TIMESTAMP,
	ip varchar(255) not null,
	country varchar(255) default '(not set)',
	referer text,
	useragent varchar(255),
	PRIMARY KEY (visit_id),
	CONSTRAINT fk_visit_short_id FOREIGN KEY (short_id) REFERENCES short(short_id)
		ON DELETE RESTRICT 
		ON UPDATE CASCADE
);



--		DEFAULT (PUBLIC) USER
INSERT INTO `users` (`user_id`, `username`, `password`, `name`, `email`, `registered_on`, `is_registered`, `is_active`, `ip`, `device_id`) VALUES (1, 'Someone', 'NoPass-1', 'Someone', 'info@tinify.co', '2000-01-01 12:00:01', b'1', b'1', NULL, NULL);


--		THIS ARE DEMO DATA, PLEASE INSERT ip2nation.sql DATA FOR COUNTRY RECOGNITION BY IP, 
--		OR HEAD TO THEIR WEBSITE http://www.ip2nation.com/ FOR THE LATEST VERSION


CREATE TABLE ip2nation (
  ip int(11) unsigned NOT NULL default '0',
  country char(2) NOT NULL default '',
  KEY ip (ip)
);
      
CREATE TABLE ip2nationCountries (
  code varchar(4) NOT NULL default '',
  iso_code_2 varchar(2) NOT NULL default '',
  iso_code_3 varchar(3) default '',
  iso_country varchar(255) NOT NULL default '',
  country varchar(255) NOT NULL default '',
  lat float NOT NULL default '0',
  lon float NOT NULL default '0',  
  PRIMARY KEY  (code),
  KEY code (code)
);

INSERT INTO ip2nation (ip, country) VALUES(0, 'notset');
INSERT INTO ip2nationCountries (code, iso_code_2, iso_code_3, iso_country, country, lat, lon) VALUES('notset', '', '', '', 'Not Set', 0, 0);





