<?php

// Set timezone to use EST
date_default_timezone_set("America/Toronto");

// Report all Errors
error_reporting(-1);

// Debug
define("DEBUG", false);

// Define constants
define("ROOTDIR", dirname(dirname(__FILE__)));
define("MYSQLROOT", ROOTDIR."/includes/connection.php");

// Date and Period Values
define("DATEVALS", array(
    "Mon", "Tue", "Wed", "Thu", "Fri"
));
define("PERIODVALS", array(
    "Morning", "Lunch", "Afternoon"
));

// API Error Codes
define("ERRCODE", array(
    "Success", // 0
    "Too few/many Arguments", // 1
    "Invalid Arguments", // 2
    "Generic MySQL Error" // 3
));

?>