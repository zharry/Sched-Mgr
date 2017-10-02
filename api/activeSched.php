<?php

	require_once('../config.php');
	$DATA = $_GET;
	
	// Fetch Actions
	$action = $DATA["action"];
	$return["action"] = $action;
	
	// Process Actions
	if ($action == "fetch") {
		$sql = "SELECT * FROM active_sched;";
		$res = mysqli_query($conn, $sql);
		if (mysqli_num_rows($res) > 0) {
			while($row = mysqli_fetch_assoc($res)) {
				$return[$row["date"]] = $row;
			}
		} else {
			$return["error"] = "No results!";
		}
	} else if ($action == "set") {
		$sql = "";
		foreach (array("MON", "TUE", "WED", "THU", "FRI") as $day) {
			foreach (array("morning", "lunch", "afternoon") as $time) {
				$sql .= "UPDATE active_sched SET {$time}='". $DATA[$day."-".$time] ."' WHERE date='{$day}';";
			}
		}
		if (mysqli_multi_query($conn, $sql)) {
			$return["success"] = $sql;
		} else {
			$return["error"] = "Update Failed! ".mysqli_error($conn);
		}
	} else if ($action == "push") {
		// Check to see if data already exists
		$dateCheck = new DateTime();
		date_modify($dateCheck, "next week monday");	
		$dateCheck = date_format($dateCheck , "Y-m-d");	
		$sqlCheck = "SELECT * FROM sched WHERE date='{$dateCheck}';";
		$resCheck = mysqli_query($conn, $sqlCheck);
		if (mysqli_num_rows($resCheck) > 0) {
			$return["error"] = "Next week already scheduled!";
		} else {
			// Insert New Data
			$sql = "SELECT * FROM active_sched;";
			$res = mysqli_query($conn, $sql);
			if (mysqli_num_rows($res) > 0) {
				$error = "";
				while($row = mysqli_fetch_assoc($res)) {
					// Fetch Data
					$dateTimeObj = new DateTime();
					if (isset($DATA["customTime"])) {
						date_modify($dateTimeObj, $DATA["customTime"]."".$row["date"]);
					} else {
						date_modify($dateTimeObj, "next week ".$row["date"]);
					}
					$date = date_format($dateTimeObj, "Y-m-d");
					$morningPeriod = explode(", ",$row["morning"]);
					$lunchPeriod = explode(", ",$row["lunch"]);
					$afternoonPeriod = explode(", ",$row["afternoon"]);
					
					// Insert Data
					foreach ($morningPeriod as $name) {
						if ($name != "") {
							$insert = "INSERT INTO sched (name, date, period) VALUES ('{$name}', '{$date}', 'morning');";
							if (!mysqli_query($conn, $insert)) {
								$error = mysqli_error($conn);
							}
						}
					}
					foreach ($lunchPeriod as $name) {
						if ($name != "") {
							$insert = "INSERT INTO sched (name, date, period) VALUES ('{$name}', '{$date}', 'lunch');";
							if (!mysqli_query($conn, $insert)) {
								$error = mysqli_error($conn);
							}
						}
					}
					foreach ($afternoonPeriod as $name) {
						if ($name != "") {
							$insert = "INSERT INTO sched (name, date, period) VALUES ('{$name}', '{$date}', 'afternoon');";
							if (!mysqli_query($conn, $insert)) {
								$error = mysqli_error($conn);
							}
						}
					}
				}
				if ($error != "") {
					$return["error"] = "Insertion Failed! ".$error;
				} else {
					$return["success"] = "";
				}
			} else {
				$return["error"] = "No Active Schedule";
			}
		}
	} else {
		$return["error"] = "Invalid Action";
	}
	
	// Return Data
	echo json_encode($return);
?>