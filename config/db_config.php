<?php
require_once("./include/database_util.php");

$localhosts = array('127.0.0.1', "::1");

//if running from localhost use local db, otherwise use public site db
if(!in_array($_SERVER['REMOTE_ADDR'], $localhosts)){
    $host = 'somehostname.com';
        $user = 'dbuser';
        $pass = 'dbpass';
        $name = 'dbname';
}
else {
        $host = '127.0.0.1';
        $user = 'localdbuser';
        $pass = 'localdbpass';
        $name = 'localdbdbname';
}


$DB = new Database($host, $user, $pass, $name); 
?>