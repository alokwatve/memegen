<?php
// phpinfo();
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

$memedb = new PDO('mysql:host=' . $DBHOST . ';dbname=' .
    $DBNAME, $DBUSER, $DBPASSWD);

?>

