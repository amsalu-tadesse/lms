<img src="public/uploads/lms.png" >
 

## LMS
This platform enables students to see list of available courses, register online and access course contents after registration. After passing all required criterias and exams, the system generates certificates for students. Admins are able to assign courses to instructors, and follow the course statuses. while instructors are able to streamline videos to answer student questions, regarding the posted course contents. 

## Steps of Installation

- git clone https://github.com/AmsaluGit/lms.git
- cd lms
- update .env according to your database credentials
- composer install
- create database 'lms' in your local machine.
- import the database/database.sql into your database
- bin/console symfony serve
- got http://127.0.0.1:8000 as shown in your terminal.

## How to use the system
- Login with username = admin.admin and password=123456789
- Admin contains all priviledges. You can login as an Instructor or student to see from their perspectives.

