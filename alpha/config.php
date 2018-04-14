<?php
	$whitelist = array( '127.0.0.1','::1' );
	if (in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
		$conn = mysqli_connect("localhost", "root", "", "newmgr");
	} else {
		require_once('/etc/mysql-creds/mysql-creds.php');
		$conn = mysqli_connect($project_sm["host"], $project_sm["user"], $project_sm["pass"], $project_sm["data"]);
	}
	
    if (!$conn) {
        die("Error establishing database connection!");
    }
?>