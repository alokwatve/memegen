create user 'memegenuser'@'localhost' identified by 'memegen_passwd' ;
create database memegendb;
grant all on memegendb.* to 'memegenuser'@'localhost' 
