<?php

require_once '../../../includes/settings.php';
require_once MYSQLROOT;

$return = array(
    "action" => "getSchedule",
    "code" => 1, // Default to failure, see settings.php
    "data" => "",
    "post_params" => $_POST
);

// Check data, none in this case
if (true) {
    // Return successful
    $return["code"] = 0; 
    
	// Check if getting Schedule for just today
    $query = "SELECT * FROM `schedule`";
    if (isset($_POST["JustToday"])) {
        $date = substr(date("l")."", 0, 3);
        if ($date == "Sun" || $date == "Sat")
            $date = "Mon";
        if (in_array($_POST["JustToday"], DATEVALS)) {
            $date = $_POST["JustToday"];
        }
        $query .= " WHERE `Date` = '{$date}'";
        
        $return["calculated_date"] = $date;
        $return["action"] = "getScheduleToday";
    }
    $query .= ";";
    
	// Query and Return;
    $res = mysqli_query($conn, $query);
    if (mysqli_num_rows($res) == 1) {
        $return["data"] = mysqli_fetch_assoc($res);
    } else {
        $return["data"] = array();
        while ($row = mysqli_fetch_assoc($res))
            $return["data"][$row["id"]] = $row;
    }
   
}

// Error Handling
if ($return["code"] != 0)
    $return["data"] = ERRCODE[$return["code"]];


// Return JSON Data
echo json_encode($return);

?>