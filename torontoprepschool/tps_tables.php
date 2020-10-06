<?

UPDATE users 
SET 
    email = 'jrb1@google.com'
WHERE
    id = 6;

// Hostinger info
// Database
u368368182_tps
// User
u368368182_TPSadmin
// Password
TpS2020!1

CREATE DATABASE tps;

CREATE TABLE users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(50) NOT NULL UNIQUE,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    token VARCHAR(15) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    user_type VARCHAR(15)
);

// How to set admin
UPDATE users
SET user_type='admin'
WHERE id = 4;

CREATE USER 'tps_admin'@'localhost' IDENTIFIED BY 'tps2020';
GRANT ALL ON tps.* to 'tps_admin'@'localhost';

// Delete table data
TRUNCATE TABLE application; 
TRUNCATE TABLE reports; 
TRUNCATE TABLE courses; 
TRUNCATE TABLE resides; 
TRUNCATE TABLE schools; 
TRUNCATE TABLE guardian; 
TRUNCATE TABLE siblings; 
TRUNCATE TABLE corr; 
TRUNCATE TABLE parentstmt;
TRUNCATE TABLE candidatestmt;
TRUNCATE TABLE teacherref;
TRUNCATE TABLE communityref;

// Drop tables
DROP TABLE application; 
DROP TABLE reports; 
DROP TABLE courses; 
DROP TABLE resides; 
DROP TABLE schools; 
DROP TABLE guardian; 
DROP TABLE siblings; 
DROP TABLE corr; 
DROP TABLE parentstmt;
DROP TABLE candidatestmt;
DROP TABLE teacherref;
DROP TABLE communityref;

// Display table vertically for viewing in terminal
SELECT * FROM application\G

// Reference users as parent table (foreign key)
CREATE TABLE application (
	id INT NOT NULL PRIMARY KEY, 
	can_surname VARCHAR(50),
	can_name VARCHAR(50),
	can_prename VARCHAR(50),
	can_address VARCHAR(500),
	can_homeph VARCHAR(20),
	can_cellph VARCHAR(20),
	can_birth DATE NOT NULL,
	can_gender VARCHAR(10),
	can_image VARCHAR(100),
	can_birthcert VARCHAR(100),
	can_entry VARCHAR(20),
	can_canres VARCHAR(5),
	can_cit VARCHAR(20),
	can_entrydate VARCHAR(50),
	can_lang VARCHAR(20),
	can_school VARCHAR(100) NOT NULL,
    FOREIGN KEY (id) REFERENCES users(id)
);

CREATE TABLE reports (
	id INT NOT NULL, 
	report_num INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	report_name VARCHAR(100) NOT NULL,
	FOREIGN KEY (id) REFERENCES users(id)
);
/* Prefvent duplicates
CREATE UNIQUE INDEX report_id_name 
	ON reports(id,report_name);
*/

CREATE TABLE courses (
	id INT NOT NULL, 
	course_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	course_num VARCHAR (100),
	FOREIGN KEY (id) REFERENCES users(id)
);
/* Prefvent duplicates
CREATE UNIQUE INDEX courses_id_name 
	ON courses(id, course_num);
*/

CREATE TABLE resides (
	id INT NOT NULL, 
	resides_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	resides_with VARCHAR (100),
	FOREIGN KEY (id) REFERENCES users(id)
);
/* Prefvent duplicates
CREATE UNIQUE INDEX resides_id_name 
	ON resides(id, resides_with);
*/

CREATE TABLE schools (
	id INT NOT NULL, 
	school_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	school_name VARCHAR (100),
	FOREIGN KEY (id) REFERENCES users(id)
);
/* Prefvent duplicates
CREATE UNIQUE INDEX school_id_name 
	ON schools(id, school_name);
*/

CREATE TABLE guardian (
	id INT NOT NULL, 
	gar_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	gar_rel VARCHAR (50),
	gar_surname VARCHAR (50),
	gar_name VARCHAR (50),
	gar_address VARCHAR (200),
	gar_city VARCHAR (50),
	gar_postal VARCHAR (50),
	gar_homeph VARCHAR (50),
	gar_cellph VARCHAR (50),
	gar_workph VARCHAR (50),
	gar_occ VARCHAR (100),
	gar_employer VARCHAR (50),
	FOREIGN KEY (id) REFERENCES users(id)
);
/* Prefvent duplicates
CREATE UNIQUE INDEX guardian_id_name 
	ON guardian(id, gar_rel, gar_surname, gar_name);
*/

CREATE TABLE siblings (
	id INT NOT NULL, 
	sibling_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	sibling_name VARCHAR (100),
	sibling_age VARCHAR (15), 
	sibling_school VARCHAR (100),
	FOREIGN KEY (id) REFERENCES users(id)
);
/* Prefvent duplicates
CREATE UNIQUE INDEX sibling_id_name 
	ON siblings(id, sibling_name, sibling_age, sibling_school);
*/

CREATE TABLE corr (
	id INT NOT NULL, 
	corr_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	sent_to VARCHAR (100),
	FOREIGN KEY (id) REFERENCES users(id)
);
/* Prefvent duplicates
CREATE UNIQUE INDEX corr_id_name 
	ON corr(id, sent_to);
*/

CREATE TABLE parentstmt (
	id INT NOT NULL PRIMARY KEY, 
	par_first VARCHAR(50) NOT NULL,
	par_surname VARCHAR(50) NOT NULL,
	par_la1 TEXT,
	par_la2 TEXT,
	par_la3 TEXT,
	par_la4 TEXT,
	par_la5 TEXT,
	asses_ans VARCHAR(10),
	asses_doc VARCHAR(100),
	par_la6 TEXT,
	par_la7 TEXT,
	par_la8 TEXT,
	par_la9 TEXT,
    FOREIGN KEY (id) REFERENCES users(id)
);

CREATE TABLE candidatestmt (
	id INT NOT NULL PRIMARY KEY, 
	stu_la1 TEXT,
	stu_la2 TEXT,
	subj1 VARCHAR(20),
	subj2 VARCHAR(20),
	subj3 VARCHAR(20),
	stu_la3 TEXT,
	stu_la4 TEXT,
	stu_la5 TEXT,
	stu_la6 TEXT,
	stu_la7 TEXT,
	stu_la8 TEXT,
	stu_la9 TEXT,
	stu_la10 TEXT,
    FOREIGN KEY (id) REFERENCES users(id)
);

CREATE TABLE referral (
	id INT NOT NULL, 
	ref_type VARCHAR(10) NOT NULL PRIMARY KEY,
	first_name VARCHAR(50) NOT NULL,
	last_name VARCHAR(50) NOT NULL,
	email VARCHAR(50) NOT NULL,
	FOREIGN KEY (id) REFERENCES users(id)
);

CREATE TABLE additional (
	id INT NOT NULL, 
	additional_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	additional_name VARCHAR(100) NOT NULL,
	FOREIGN KEY (id) REFERENCES users(id)
);
/* Prefvent duplicates
CREATE UNIQUE INDEX additional_id_name 
	ON additional(id,additional_name);
*/

CREATE TABLE teacherref (
	id INT NOT NULL PRIMARY KEY, 
	teachFirst VARCHAR(50),
  	teachLast VARCHAR(50),
  	teachSchool VARCHAR(50),
  	tq1 TEXT,
  	tq2 TEXT, 
  	tq3 TEXT,
  	tq4 TEXT, 
  	tq5 TEXT, 
  	acper VARCHAR(50),
  	grpar VARCHAR(50),
  	woralo VARCHAR(50),
  	clabeh VARCHAR(50),
  	verski VARCHAR(50),
  	wricom VARCHAR(50),
  	foldir VARCHAR(50),
  	woreff VARCHAR(50),
  	attspa VARCHAR(50),
  	init VARCHAR(50),
  	integ VARCHAR(50),
  	crithi VARCHAR(50),
  	selcon VARCHAR(50),
  	crea VARCHAR(50),
  	useof VARCHAR(50),
  	sport VARCHAR(50),
  	peerel VARCHAR(50),
  	intcur VARCHAR(50),
  	parcoo VARCHAR(50),
  	parexp VARCHAR(50),
  	homcom VARCHAR(50),
  	acapot VARCHAR(50),
	FOREIGN KEY (id) REFERENCES users(id)
);

CREATE TABLE communityref (
	id INT NOT NULL PRIMARY KEY, 
	commFirst VARCHAR(50),
  	commLast VARCHAR(50),
  	years VARCHAR(10),
  	cq1 TEXT,
  	cq2 TEXT,
  	cq3 TEXT,
  	atten VARCHAR(50),
  	depen VARCHAR(50),
  	compa VARCHAR(50),
  	hone VARCHAR(50),
  	resp VARCHAR(50),
  	respon VARCHAR(50),
  	foldire VARCHAR(50),
  	coop VARCHAR(50),
  	aenadv VARCHAR(50),
  	woreth VARCHAR(50),
  	creat VARCHAR(50),
   	leader VARCHAR(50),
  	selfcon VARCHAR(50),
  	humo VARCHAR(50),
  	socmat VARCHAR(50),
  	sports VARCHAR(50),
  	peerrel VARCHAR(50),
  	tenac VARCHAR(50),
  	atti VARCHAR(50),
  	ambi VARCHAR(50),
  	coach VARCHAR(50),
	FOREIGN KEY (id) REFERENCES users(id)
);

?>