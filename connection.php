<?php
	require_once('/etc/mysql-creds/mysql-creds.php');
	//$conn = mysqli_connect("localhost", "root", "", "schedmgr");

	$conn = mysqli_connect($project_sm["host"], $project_sm["user"], $project_sm["pass"], $project_sm["data"]);
	
    if (!$conn) {
        die("Error establishing database connection!");
    }
?>