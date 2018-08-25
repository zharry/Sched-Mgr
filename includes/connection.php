<?php

require_once 'settings.php';

$whitelist = array( '127.0.0.1','::1' );
if (in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
	$conn = mysqli_connect("localhost", "root", "", "schedmgr-new");
} else {
	$conn = mysqli_connect(getenv('MYSQL_HOST'), getenv('MYSQL_USER_SCHEDMGR'), getenv('MYSQL_PASS_SCHEDMGR'), "schedmgr");
}

if (!$conn) {
	die("Error establishing database connection!");
}
	
?>