<?php
	
	require_once('connection.php');

	// Update timetable is nesseary
	if (!empty($_POST)) {
		// Update DB for Schedule changes
		$stmt = mysqli_prepare($conn, "UPDATE lib_schedule SET monday = ?, tuesday = ?, wednesday = ?, thursday = ?, friday = ? WHERE timeslot = 'm'");
		mysqli_stmt_bind_param($stmt, "sssss", $_POST["M-M"], $_POST["M-T"], $_POST["M-W"], $_POST["M-Th"], $_POST["M-F"]);
		mysqli_stmt_execute($stmt);
		$stmt = mysqli_prepare($conn, "UPDATE lib_schedule SET monday = ?, tuesday = ?, wednesday = ?, thursday = ?, friday = ? WHERE timeslot = 'l'");
		mysqli_stmt_bind_param($stmt, "sssss", $_POST["L-M"], $_POST["L-T"], $_POST["L-W"], $_POST["L-Th"], $_POST["L-F"]);
		mysqli_stmt_execute($stmt);
		$stmt = mysqli_prepare($conn, "UPDATE lib_schedule SET monday = ?, tuesday = ?, wednesday = ?, thursday = ?, friday = ? WHERE timeslot = 'a'");
		mysqli_stmt_bind_param($stmt, "sssss", $_POST["A-M"], $_POST["A-T"], $_POST["A-W"], $_POST["A-Th"], $_POST["A-F"]);
		mysqli_stmt_execute($stmt);
		
		// Update Users selection
		$everyone = array_unique(explode(",",$_POST["M-M"].",".$_POST["M-T"].",".$_POST["M-W"].",".$_POST["M-Th"].",".$_POST["M-F"].",".$_POST["L-M"].",".$_POST["L-T"].",".$_POST["L-W"].",".$_POST["L-Th"].",".$_POST["L-F"].",".$_POST["A-M"].",".$_POST["A-T"].",".$_POST["A-W"].",".$_POST["A-Th"].",".$_POST["A-F"]));
		foreach ($everyone as $val) {
			$stmt = mysqli_prepare($conn, "INSERT IGNORE INTO lib_users (name) VALUES (?)");
			mysqli_stmt_bind_param($stmt, "s", $val);
			mysqli_stmt_execute($stmt);
		}
	}
	
	// Fetch current timetable
	$sql = "SELECT * FROM lib_schedule";
	$result = mysqli_query($conn, $sql);
	$curSched = array();
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$curSched[$row["timeslot"]] = $row;
		}
	}
	
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

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
				<textarea class="schedArea" name="M-M"><?=$curSched["m"]["monday"]?></textarea>
			</td>
			<td class="displayCol">
				<textarea class="schedArea" name="M-T"><?=$curSched["m"]["tuesday"]?></textarea>
			</td>
			<td class="displayCol">
				<textarea class="schedArea" name="M-W"><?=$curSched["m"]["wednesday"]?></textarea>
			</td>
			<td class="displayCol">
				<textarea class="schedArea" name="M-Th"><?=$curSched["m"]["thursday"]?></textarea>
			</td>
			<td class="displayCol">
				<textarea class="schedArea" name="M-F"><?=$curSched["m"]["friday"]?></textarea>
			</td>
		</tr>
		<tr class="displayRow">
			<td class="displayCol firstCol">
				Lunch
			</td>
			<td class="displayCol">
				<textarea class="schedArea" name="L-M"><?=$curSched["l"]["monday"]?></textarea>
			</td>
			<td class="displayCol">
				<textarea class="schedArea" name="L-T"><?=$curSched["l"]["tuesday"]?></textarea>
			</td>
			<td class="displayCol">
				<textarea class="schedArea" name="L-W"><?=$curSched["l"]["wednesday"]?></textarea>
			</td>
			<td class="displayCol">
				<textarea class="schedArea" name="L-Th"><?=$curSched["l"]["thursday"]?></textarea>
			</td>
			<td class="displayCol">
				<textarea class="schedArea" name="L-F"><?=$curSched["l"]["friday"]?></textarea>
			</td>
		</tr>
		<tr class="displayRow">
			<td class="displayCol firstCol">
			Afterschool
			</td>
			<td class="displayCol">
				<textarea class="schedArea" name="A-M"><?=$curSched["a"]["monday"]?></textarea>
			</td>
			<td class="displayCol">
				<textarea class="schedArea" name="A-T"><?=$curSched["a"]["tuesday"]?></textarea>
			</td>
			<td class="displayCol">
				<textarea class="schedArea" name="A-W"><?=$curSched["a"]["wednesday"]?></textarea>
			</td>
			<td class="displayCol">
				<textarea class="schedArea" name="A-Th"><?=$curSched["a"]["thursday"]?></textarea>
			</td>
			<td class="displayCol">
				<textarea class="schedArea" name="A-F"><?=$curSched["a"]["friday"]?></textarea>
			</td>
		</tr>
	</table>
	<div id="footer">
		<input id="saveButton" type="submit" value="Save Timetable">
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