<?php

require_once '../../../includes/settings.php';
require_once MYSQLROOT;

$return = array(
    "action" => "setSchedule",
    "code" => 1,
    "data" => "",
    "post_params" => $_POST
);

// Check data
if (isset($_POST["Date"]) && isset($_POST["Period"]) && isset($_POST["Data"])) {
    // Process action
    $date = $_POST["Date"];
    $period = $_POST["Period"];
    $data = $_POST["Data"];
    
    // Check if Day and Period are correct
    if (!(in_array($date, DATEVALS) && in_array($period, PERIODVALS)))
        $return["code"] = 2;
    else {
        // Return successful
        $return["code"] = 0;
        $period = mysqli_real_escape_string($conn, $period);
        
        // Prepare MySQL Query
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, 'UPDATE `schedule` SET '.$period.' = ? WHERE `Date` = ?;')) {
            mysqli_stmt_bind_param($stmt, "ss", $data, $date);
            mysqli_stmt_execute($stmt);
            $return["data"] = "Rows affected: " . mysqli_stmt_affected_rows($stmt);
            
            // Close Query
            mysqli_stmt_close($stmt);
        } else {
            $return["code"] = 3;
        }
    }
}

// Error Handling
if ($return["code"] != 0)
    $return["data"] = ERRCODE[$return["code"]];


// Return JSON Data
echo json_encode($return);

?>