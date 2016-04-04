<?php

// This file defines the database parameters.
$DBHOST = 'localhost';
$DBNAME = 'memegendb';
$DBUSER = 'memegenuser';
$DBPASSWD = 'memegen_passwd';

$MEMEINFO = 'MemeInfo';
$MEMETAGS = 'MemeTags';
$MEMETEMPLATES = 'MemeTemplates';
$TEMPLATETAGS = 'TemplateTags';

$INSERT_MEMEINFO_QUERY = 'INSERT INTO MemeInfo (filename, templatename) VALUES (%s, %s) ';

$memeDbConn = mysql_connect($DBHOST, $DBUSER, $DBPASSWD)
    or die('Could not connect: ' . mysql_error());
mysql_select_db($DBNAME) or die('Could not select database');

?>

