<?php
	
	require_once('connection.php');
	date_default_timezone_set ("America/Toronto");
	$null = NULL;
	
	// Grab Current Users data
	$sql = "SELECT * FROM lib_users";
	$result = mysqli_query($conn, $sql);
	$userStatus = array();
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$userStatus[$row["name"]] = $row;
		}
	}
	// Update timetable if nesseary
	if (!empty($_POST)) {
		if ($_POST["action"] == "start") {
			$startTime = date("Y-m-d H:i:s");
			$stmt = mysqli_prepare($conn, "UPDATE lib_users SET start = ? WHERE name = ?");
			mysqli_stmt_bind_param($stmt, "ss", $startTime, $_POST["person"]);
			mysqli_stmt_execute($stmt);
		} else if ($_POST["action"] == "end") {
			$startTime = strtotime($userStatus[$_POST["person"]]["start"]);
			$endTime = strtotime(date("Y-m-d H:i:s"));
			$delta = round(($endTime - $startTime) / 60);
			$newTotal = $userStatus[$_POST["person"]]["total"] + $delta;
			$stmt = mysqli_prepare($conn, "UPDATE lib_users SET start = ?, total = ? WHERE name = ?");
			mysqli_stmt_bind_param($stmt, "sis", $null, $newTotal, $_POST["person"]);
			mysqli_stmt_execute($stmt);
		}
	}
	
	// Fetch current timetable and re-check users table for changes
	$sql = "SELECT * FROM lib_schedule";
	$result = mysqli_query($conn, $sql);
	$curSched = array();
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$curSched[$row["timeslot"]] = $row;
		}
	}
	$sql = "SELECT * FROM lib_users";
	$result = mysqli_query($conn, $sql);
	$userStatus = array();
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$userStatus[$row["name"]] = $row;
		}
	}
	
	$iter = 0;
	function createSlot($data, &$iter, $userStatus) {
		$return = "";
		$people = explode(",",$data);
		foreach ($people as $person) {
			
			$accumHours = round($userStatus[$person]["total"] / 60, 2);
			$startText="";
			$start = "";
			$end = "disabled";
			if (!is_null($userStatus[$person]["start"])) {
				$start = "disabled";
				$end = "";
				$startText = "Shift started at: " . $userStatus[$person]["start"];
			}
			
			
			$return.= "<button type='button' class='btn btn-basic btn-lg' data-toggle='modal' data-target='#{$iter}-{$person}'>{$person}</button>
			<div id='{$iter}-{$person}' class='modal fade' role='dialog'>
			  <div class='modal-dialog'>
				<div class='modal-content'>
				  <div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal'>&times;</button>
					<h4 class='modal-title'>{$person}</h4><small>{$iter}</small>
				  </div>
				  <div class='modal-body'>
					<br/><form action='' method='post'>
						<input type='hidden' name='action' value='start'>
						<input type='hidden' name='person' value='{$person}'>
						<input class='btn btn-basic btn-lg' type='submit' value='Start Shift' ".$start.">
					</form><br/>".$startText."
					<hr/>
					<br/><form action='' method='post'>
						<input type='hidden' name='action' value='end'>
						<input type='hidden' name='person' value='{$person}'>
						<input class='btn btn-basic btn-lg' type='submit' value='End Shift' ".$end.">
					</form><br/>
					<hr/>
					<h3>Total Accumulative Hours:</h3>
					<h4>{$accumHours}</h4>
				  </div>
				  <div class='modal-footer'>
					<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
				  </div>
				</div>
			  </div>
			</div>";
			$iter += 1;
		}
		return $return;
	}
	
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Library - Schedule Manager</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
	<div id="topbar">
		<h3>
			Schedule Manager
		</h3>
	</div>
	<form action="" method="post">
	<table id="content" border="1">
		<tr class="displayRow firstRow">
			<td class="displayCol firstCol">
			</td>
			<td class="displayCol">
				Monday
			</td>
			<td class="displayCol">
				Tuesday
			</td>
			<td class="displayCol">
				Wednesday
			</td>
			<td class="displayCol">
				Thursday
			</td>
			<td class="displayCol">
				Friday
			</td>
		</tr>
		<tr class="displayRow">
			<td class="displayCol firstCol">
				Morning
			</td>
			<td class="displayCol">
				<?=createSlot($curSched["m"]["monday"], $iter, $userStatus)?>
			</td>
			<td class="displayCol">
				<?=createSlot($curSched["m"]["tuesday"], $iter, $userStatus)?>
			</td>
			<td class="displayCol">
				<?=createSlot($curSched["m"]["wednesday"], $iter, $userStatus)?>
			</td>
			<td class="displayCol">
				<?=createSlot($curSched["m"]["thursday"], $iter, $userStatus)?>
			</td>
			<td class="displayCol">
				<?=createSlot($curSched["m"]["friday"], $iter, $userStatus)?>
			</td>
		</tr>
		<tr class="displayRow">
			<td class="displayCol firstCol">
				Lunch
			</td>
			<td class="displayCol">
				<?=createSlot($curSched["l"]["monday"], $iter, $userStatus)?>
			</td>
			<td class="displayCol">
				<?=createSlot($curSched["l"]["tuesday"], $iter, $userStatus)?>
			</td>
			<td class="displayCol">
				<?=createSlot($curSched["l"]["wednesday"], $iter, $userStatus)?>
			</td>
			<td class="displayCol">
				<?=createSlot($curSched["l"]["thursday"], $iter, $userStatus)?>
			</td>
			<td class="displayCol">
				<?=createSlot($curSched["l"]["friday"], $iter, $userStatus)?>
			</td>
		</tr>
		<tr class="displayRow">
			<td class="displayCol firstCol">
			Afterschool
			</td>
			<td class="displayCol">
				<?=createSlot($curSched["a"]["monday"], $iter, $userStatus)?>
			</td>
			<td class="displayCol">
				<?=createSlot($curSched["a"]["tuesday"], $iter, $userStatus)?>
			</td>
			<td class="displayCol">
				<?=createSlot($curSched["a"]["wednesday"], $iter, $userStatus)?>
			</td>
			<td class="displayCol">
				<?=createSlot($curSched["a"]["thursday"], $iter, $userStatus)?>
			</td>
			<td class="displayCol">
				<?=createSlot($curSched["a"]["friday"], $iter, $userStatus)?>
			</td>
		</tr>
	</table>
	<div id="footer">
	</div>
	</form>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
    <script src="script.js"></script>
  </body>
</html>