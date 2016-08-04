<?php

// Connecting information
$dbhost = 'localhost';
$dbuser = 'andsimo_andsimo';
$dbpass = 'eiein29';

$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die('Error connecting to mysql'.mysql_error());

// Database navnet
$dbname = 'andsimo_StarSonata' or die('Couldnt connect to database.');
mysql_select_db($dbname, $conn);
mysql_set_charset("UTF8", $conn);


$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die('Error connecting to mysql'.mysql_error());

// Database navnet
$dbname = 'public3';
mysql_select_db($dbname, $conn);
mysql_set_charset("UTF8", $conn);
?>