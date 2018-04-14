<?php

require_once '../../../includes/settings.php';
require_once MYSQLROOT;

$return = array(
    "action" => "getShift", // Check if a specific shift is completed
    "code" => 1, // Default to failure, see settings.php
    "data" => "",
    "post_params" => $_POST
);

// Check data
if (isset($_POST["Description"]) && isset($_POST["Name"])) {
    // Process action
    $desc = $_POST["Description"];
    $name = $_POST["Name"];
	
	// Check to see if the shift exists
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, 'SELECT * FROM `shifts` WHERE `name` = ? AND `description` = ?;')) {
		mysqli_stmt_bind_param($stmt, "ss", $name, $desc);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_store_result($stmt);
		
		// Entry Exists
		if (mysqli_stmt_num_rows($stmt) == 1) {
			$return["data"] = true;
		} else {
			$return["data"] = false;
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