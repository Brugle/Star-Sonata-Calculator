<?php
// an example of closedb.php
// it does nothing but closing
// a mysql database connection

mysql_close($conn) or die("Could not close the database connection");
?>