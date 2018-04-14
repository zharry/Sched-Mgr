<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<title>Volunteer Manager</title>
		<script src="https://use.fontawesome.com/bfcae67805.js"></script>
		<script src="src/script.js"></script>
		<link rel="stylesheet" type="text/css" href="src/style.css">
		<link rel="stylesheet" type="text/css" href="src/calendar.css">
		<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
	</head>

	<body onload="loadActiveSched();">
	<div id="loaderContainer">
		<div id="loader"></div>
	</div>
	<div id="header">
		Library Volunteer Schedule
	</div>
	<div id="body" class="container">
	<br/>
	<br/>
	<table style="margin: auto;" id="displayOnlySched">
		<tr>
			<td class="timeSlotDate">
				Mon
			</td>
			<td>
				<div class="container">
					<div class="row"><i class="col-lg-5">Morning:</i><p class="col-lg-7 timeSlot" id="mon-morn"></p></div>
					<div class="row"><i class="col-lg-5">Lunch:</i><p class="col-lg-7 timeSlot" id="mon-lunch"></p></div>
					<div class="row"><i class="col-lg-5">Afterschool:</i><p class="col-lg-7 timeSlot" id="mon-after"></p></div>
				</div>
			</td>
		</tr>
		<tr>
			<td class="timeSlotDate">
				Tue
			</td>
			<td>
				<div class="container">
					<div class="row"><i class="col-lg-5">Morning: </i><p class="col-lg-7 timeSlot" id="tue-morn"></p></div>
					<div class="row"><i class="col-lg-5">Lunch: </i><p class="col-lg-7 timeSlot" id="tue-lunch"></p></div>
					<div class="row"><i class="col-lg-5">Afterschool: </i><p class="col-lg-7 timeSlot" id="tue-after"></p></div>
				</div>
			</td>
		</tr>
		<tr>
			<td class="timeSlotDate">
				Wed
			</td>
			<td>
				<div class="container">
					<div class="row"><i class="col-lg-5">Morning: </i><p class="col-lg-7 timeSlot" id="wed-morn"></p></div>
					<div class="row"><i class="col-lg-5">Lunch: </i><p class="col-lg-7 timeSlot" id="wed-lunch"></p></div>
					<div class="row"><i class="col-lg-5">Afterschool: </i><p class="col-lg-7 timeSlot" id="wed-after"></p></div>
				</div>
			</td>
		</tr>
		<tr>
			<td class="timeSlotDate">
				Thu
			</td>
			<td>
				<div class="container">
					<div class="row"><i class="col-lg-5">Morning: </i><p class="col-lg-7 timeSlot" id="thu-morn"></p></div>
					<div class="row"><i class="col-lg-5">Lunch: </i><p class="col-lg-7 timeSlot" id="thu-lunch"></p></div>
					<div class="row"><i class="col-lg-5">Afterschool: </i><p class="col-lg-7 timeSlot" id="thu-after"></p></div>
				</div>
			</td>
		</tr>
		<tr>
			<td class="timeSlotDate">
				Fri
			</td>
			<td>
				<div class="container">
					<div class="row"><i class="col-lg-5">Morning: </i><p class="col-lg-7 timeSlot" id="fri-morn"></p></div>
					<div class="row"><i class="col-lg-5">Lunch: </i><p class="col-lg-7 timeSlot" id="fri-lunch"></p></div>
					<div class="row"><i class="col-lg-5">Afterschool: </i><p class="col-lg-7 timeSlot" id="fri-after"></p></div>
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: center;">
			<br/>
			<button type="button" class="btn" onclick="location.href='admin.php'">Admin Panel</button>
			<br/>
			<br/>
			</td>
		</tr>
	</table>
	</div>
	
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
	
	</body>

</html>