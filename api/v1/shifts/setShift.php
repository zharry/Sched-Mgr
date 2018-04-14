<?php

require_once '../../../includes/settings.php';
require_once MYSQLROOT;

$return = array(
    "action" => "setShift",
    "code" => 1, // Default to failure, see settings.php
    "data" => "",
    "post_params" => $_POST
);

// WHEN NAME, DESC and HOURS IS SPECIFIED
if (isset($_POST["Description"]) && isset($_POST["Hours"]) && isset($_POST["Name"])) {
    // Process action
    $desc = $_POST["Description"];
    $hours = $_POST["Hours"];
    $name = $_POST["Name"];
	
	// Check to see if the shift exists
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, 'SELECT * FROM `shifts` WHERE `name` = ? AND `description` = ?;')) {
		mysqli_stmt_bind_param($stmt, "ss", $name, $desc);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_store_result($stmt);
		
		// Entry Already Exists
		if (mysqli_stmt_num_rows($stmt) == 1) {
			$return["action"] = "removeShift";
			// Remove Shift
			if (mysqli_stmt_prepare($stmt, 'DELETE FROM `shifts` WHERE `name` = ? AND `description` = ?;')) {
				mysqli_stmt_bind_param($stmt, "ss", $name, $desc);
				mysqli_stmt_execute($stmt);
				$return["data"] = "Rows affected: " . mysqli_stmt_affected_rows($stmt);
				$return["code"] = 0;
			} else {
				$return["code"] = 3;
			}
		// Create New Entry
		} else {
			if (mysqli_stmt_prepare($stmt, 'INSERT INTO `shifts` (`name`, `hours`, `description`) VALUES (?, ?, ?);')) {
				mysqli_stmt_bind_param($stmt, "sss", $name, $hours, $desc);
				mysqli_stmt_execute($stmt);
				$return["data"] = "Rows affected: " . mysqli_stmt_affected_rows($stmt);
				$return["code"] = 0;
			} else {
				$return["code"] = 3;
			}
		}
	} else {
		$return["code"] = 3;
	}
	mysqli_stmt_close($stmt);
// END
	
// WHEN ID IS SPECIFIED
} else if (isset($_POST["ID"])) {
	$id = $_POST["ID"];
	
	// Check to see if the shift exists
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, 'SELECT * FROM `shifts` WHERE `id` = ?;')) {
		mysqli_stmt_bind_param($stmt, "i", $id);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_store_result($stmt);
		
		if (mysqli_stmt_num_rows($stmt) == 1) {
			$return["action"] = "removeShift";
			// Remove Shift
			if (mysqli_stmt_prepare($stmt, 'DELETE FROM `shifts` WHERE `id` = ?;')) {
				mysqli_stmt_bind_param($stmt, "i", $id);
				mysqli_stmt_execute($stmt);
				$return["data"] = "Rows affected: " . mysqli_stmt_affected_rows($stmt);
				$return["code"] = 0;
			} else {
				$return["code"] = 3;
			}
		} else {
			// The ID must exist, or else it wouldn't have been query'd out in
			// the first place...
			$return["code"] = 3;
		}
	} else {
		$return["code"] = 3;
	}
	mysqli_stmt_close($stmt);
}

// Error Handling
if ($return["code"] != 0)
    $return["data"] = ERRCODE[$return["code"]];


// Return JSON Data
echo json_encode($return);

?>