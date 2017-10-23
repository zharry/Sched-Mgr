<?php

	require_once('../config.php');
	$DATA = $_GET;
	
	// Fetch Actions
	$action = $DATA["action"];
	$return["action"] = $action;
	
	if ($action == "getDate") {
		$date = $DATA["date"];		
		$sql = "SELECT * FROM sched WHERE date='{$date}';";
		$res = mysqli_query($conn, $sql);
		if (mysqli_num_rows($res) > 0) {
			while($row = mysqli_fetch_assoc($res)) {
				$return["data"][] = $row;
			}
		} else {
			$return["error"] = "No results!";
		}
	} else if ($action == "getAllHours") {
		echo "Test";
		$sql = "SELECT * FROM sched;";
		$res = mysqli_query($conn, $sql);
		if (mysqli_num_rows($res) > 0) {
			while($row = mysqli_fetch_assoc($res)) {
				$totalHours[$row["name"]] += $row["bonus"];
				if ($row["done"] != 0) {
					if ($row["period"] == "morning") {
						$totalHours[$row["name"]] += 0.5;
					} else if ($row["period"] == "lunch") {
						$totalHours[$row["name"]] += 0.5;
					} else {
						$totalHours[$row["name"]] += 1;
					}
				}
			}
			$return["data"] = $totalHours;
		} else {
			$return["error"] = mysqli_error($conn);
		}
	} else if ($action == "getHours") {
		$name = $DATA["name"];
		$return["name"] = $name;
		$sql = "SELECT * FROM sched WHERE name='{$name}';";
		$res = mysqli_query($conn, $sql);
		if (mysqli_num_rows($res) > 0) {
			$totalHours = 0;
			while($row = mysqli_fetch_assoc($res)) {
				$totalHours += $row["bonus"];
				if ($row["done"] != 0) {
					if ($row["period"] == "morning") {
						$totalHours += 0.5;
					} else if ($row["period"] == "lunch") {
						$totalHours += 0.5;
					} else {
						$totalHours += 1;
					}
				}
			}
			$return["hours"] = $totalHours;
		} else {
			$return["hours"] = -1;
		}
	} else if ($action == "completeShift") {
		$id = $DATA["id"];
		$sql = "UPDATE sched SET done = 1 WHERE id = {$id};";
		if (mysqli_query($conn, $sql)) {
			$return["success"] = "";
		} else {
			$return["error"] = mysqli_error($conn);
		}
	} else if ($action == "addHours") {
		$id = $DATA["id"];
		$hours = $DATA["hours"];
		$sql = "UPDATE sched SET bonus = {$hours} WHERE id = {$id};";
		if (mysqli_query($conn, $sql)) {
			$return["success"] = "";
		} else {
			$return["error"] = mysqli_error($conn);
		}
	} else if ($action == "getBonus") {
		$id = $DATA["id"];
		$sql = "SELECT * FROM sched WHERE id='{$id}';";
		$res = mysqli_query($conn, $sql);
		if (mysqli_num_rows($res) == 1) {
			while($row = mysqli_fetch_assoc($res))
				$return["bonus"] = $row["bonus"];
		} else {
			$return["error"] = "No Student with ID ".$id;
		}
	} else {
		$return["error"] = "Invalid Action";
	}
	
	// Return Data
	echo json_encode($return);

?>