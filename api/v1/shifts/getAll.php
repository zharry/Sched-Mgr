<?php

require_once '../../../includes/settings.php';
require_once MYSQLROOT;

$return = array(
    "action" => "getAll", // Get all hours for everyone in the database
    "code" => 1, // Default to failure, see settings.php
    "data" => "",
    "post_params" => $_POST
);

// Check data, none in this case
if (true) {
    // Process action
	$query = "SELECT * FROM `shifts`;";
	$return["data"] = array();
	
	// Query
	$res = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_assoc($res))
		$return["data"][$row["name"]] = isset($return["data"][$row["name"]]) ? $return["data"][$row["name"]] + (double)$row["hours"] : (double)$row["hours"];
    
    // Return successful
    $return["code"] = 0;
}

// Error Handling
if ($return["code"] != 0)
    $return["data"] = ERRCODE[$return["code"]];


// Return JSON Data
echo json_encode($return);

?>