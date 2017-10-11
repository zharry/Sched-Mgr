<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<title>Volunteer Manager</title>
		<script src="https://use.fontawesome.com/bfcae67805.js"></script>
		<script src="src/script.js"></script>
		<script src="src/calendar.js"></script>
		<link rel="stylesheet" type="text/css" href="src/style.css">
		<link rel="stylesheet" type="text/css" href="src/calendar.css">
		<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
	</head>

	<body>
	<div id="loaderContainer">
		<div id="loader"></div>
	</div>
	<div id="header">
		Library Volunteer Manager
	</div>
	<div id="body" class="container">
		<div class="divcalendar">
			<div id="calendaroverallcontrols">
				<div id="userControls">
					<button type="button" class="btn btn-lg" data-toggle="modal" data-target="#studentAction"><i class="fa fa-user fa-icon" aria-hidden="true"></i></button>
				</div>
				<div id="calendarmonthcontrols">
					<a id="btnPrev" href="#" title="Previous Month"><i class="fa fa-arrow-left fa-icon" aria-hidden="true"></i></a>
					<div id="monthandyear"></div>
					<a id="btnNext" href="#" title="Next Month"><i class="fa fa-arrow-right fa-icon" aria-hidden="true"></i></a> 
				</div>
				<div id="adminControls">
					<button type="button" class="btn btn-lg" data-toggle="modal" data-target="#adminAction"><i class="fa fa-tasks fa-icon" aria-hidden="true"></i></button>
				</div>
			</div>
			<div id="divcalendartable"></div>
			<div id="modals"></div>
		</div>

		
		<!-- Student Action Modal -->
		<div id="studentAction" class="modal fade" role="dialog">
		  <div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Student Hours</h4>
			  </div>
			  <div class="modal-body">
				<div class="form-inline">
				  <div class="form-group">
					<input type="text" class="form-control" id="getHoursStudentName">
				  </div>
				  <button type="submit" class="btn btn-default" onclick="getHours()">Get Hours</button>
				</div>
				<hr/>
				<div id="getHoursStudentContent">
				</div>
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			  </div>
			</div>

		  </div>
		</div>
		<!-- Admin Action Modal -->
		<div id="adminAction" class="modal fade" role="dialog">
		  <div class="modal-dialog modal-lg">

			<!-- Modal content-->
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Admin Panel</h4>
			  </div>
			  <div class="modal-body">
			  <small><center>Comma and Space Separated!</center></small>
			  <hr/>
				<table style="width: 100%; margin-right: 15px">
					<tr>
						<td class="timeSlotDate">
							Mon
						</td>
						<td>
							<div class="container">
								<div class="row"><i class="col-lg-2">Morning: </i><input class="col-lg-10 timeSlot" id="mon-morn"></div>
								<div class="row"><i class="col-lg-2">Lunch: </i><input class="col-lg-10 timeSlot" id="mon-lunch"></div>
								<div class="row"><i class="col-lg-2">Afterschool: </i><input class="col-lg-10 timeSlot" id="mon-after"></div>
							</div>
						</td>
					</tr>
					<tr>
						<td class="timeSlotDate">
							Tue
						</td>
						<td>
							<div class="container">
								<div class="row"><i class="col-lg-2">Morning: </i><input class="col-lg-10 timeSlot" id="tue-morn"></div>
								<div class="row"><i class="col-lg-2">Lunch: </i><input class="col-lg-10 timeSlot" id="tue-lunch"></div>
								<div class="row"><i class="col-lg-2">Afterschool: </i><input class="col-lg-10 timeSlot" id="tue-after"></div>
							</div>
						</td>
					</tr>
					<tr>
						<td class="timeSlotDate">
							Wed
						</td>
						<td>
							<div class="container">
								<div class="row"><i class="col-lg-2">Morning: </i><input class="col-lg-10 timeSlot" id="wed-morn"></div>
								<div class="row"><i class="col-lg-2">Lunch: </i><input class="col-lg-10 timeSlot" id="wed-lunch"></div>
								<div class="row"><i class="col-lg-2">Afterschool: </i><input class="col-lg-10 timeSlot" id="wed-after"></div>
							</div>
						</td>
					</tr>
					<tr>
						<td class="timeSlotDate">
							Thu
						</td>
						<td>
							<div class="container">
								<div class="row"><i class="col-lg-2">Morning: </i><input class="col-lg-10 timeSlot" id="thu-morn"></div>
								<div class="row"><i class="col-lg-2">Lunch: </i><input class="col-lg-10 timeSlot" id="thu-lunch"></div>
								<div class="row"><i class="col-lg-2">Afterschool: </i><input class="col-lg-10 timeSlot" id="thu-after"></div>
							</div>
						</td>
					</tr>
					<tr>
						<td class="timeSlotDate">
							Fri
						</td>
						<td>
							<div class="container">
								<div class="row"><i class="col-lg-2">Morning: </i><input class="col-lg-10 timeSlot" id="fri-morn"></div>
								<div class="row"><i class="col-lg-2">Lunch: </i><input class="col-lg-10 timeSlot" id="fri-lunch"></div>
								<div class="row"><i class="col-lg-2">Afterschool: </i><input class="col-lg-10 timeSlot" id="fri-after"></div>
							</div>
						</td>
					</tr>
				</table>
				<br/>
				<button type="submit" class="btn btn-default" onclick="setActiveSched()">Save Schedule</button>
				<button type="submit" class="btn btn-default" onclick="pushSched()">Push Schedule onto "Next Week"</button>
				<hr/>
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			  </div>
			</div>

		  </div>
		</div>
	</div>
	
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
	</body>

</html>