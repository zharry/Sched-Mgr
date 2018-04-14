<?php

require_once '../../../includes/settings.php';
require_once MYSQLROOT;

$return = array(
    "action" => "getCompleted",
    "code" => 1, // Default to failure, see settings.php
    "data" => "",
    "post_params" => $_POST
);

// Check data
if (isset($_POST["Name"])) {
    // Process action
    $name = $_POST["Name"];
	
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, 'SELECT `id`,`hours`,`description` FROM `shifts` WHERE `name` = ?;')) {
		mysqli_stmt_bind_param($stmt, "s", $name);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_store_result($stmt);
		mysqli_stmt_bind_result($stmt, $id, $hours, $desc);
		
		$return["data"] = array();
		while (mysqli_stmt_fetch($stmt)) {
			$return["data"][$id] = array($hours, $desc);
		}
    
		// Return successful
		$return["code"] = 0; 
	} else {
		$return["code"] = 3;
	}
}

// Error Handling
if ($return["code"] != 0)
    $return["data"] = ERRCODE[$return["code"]];


// Return JSON Data
echo json_encode($return);

?>