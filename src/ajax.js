/* Base AJAX Function
Parameters:
	endpoint - String, location of API call relative to /api/VERSION/
	params - JSON, Object indicating {Param: Value, ...} to pass to the API
	callback - function, function(response [, params]) to be called when the API returns
	cbParams - JSON, Object indicating {Param: Value, ...} to pass to callback function
			   in case it needs extra client-side data
*/
function ajax(endpoint, params, callback, cbParams = null) {
	// Parse Params into POST data string
	var data = "";
	for (var k in params)
		data += k+"="+params[k]+"&";
	data = data.substring(0, data.length);
	
	// Create AJAX (XMLHttpsRequest)
	var req = new XMLHttpRequest();
	req.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			// Use the parameterized callback only if cbParams is passed
			if (cbParams == null)
				callback(this.responseText);
			else
				callback(this.responseText, cbParams);
		}
	};
	
	// Send AJAX Request
	req.open("POST", "api/v1/"+endpoint, true);
	req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	req.send(data);
	
	// Display loading screen
	ajaxRequests += 1;
}

// Helper Functions

/* Description Generator
Parameters:
	period - String, period, either Morning, Lunch or Afternoon
	dayOfWeek - int, 0-6 representation of weekday
Returns:
	String - A description of a shift, for use with either SQL
			 or other JS Functions
*/
function generateDescription(period = "NaN", dayOfWeek = 0) {
	// Calculate relative dates based on today's date
	var date = new Date();
	if (dayOfWeek != 0)
		// Sunday will return next week's data - [ (date.getDay() == 6 ? 7 : 0) ]
		date.setDate(date.getDate() + dayOfWeek - date.getDay() + (date.getDay() == 6 ? 7 : 0));
	else {
		// Fix Saturday and Sunday returns Monday's Data
		if (date.getDay() == 0)
			date.setDate(date.getDate() + 1);
		else if (date.getDay() == 6)
			date.setDate(date.getDate() + 2);
	}
	
	// Year-Month-Date:Period
	var description = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
	description += ":" + period;
	return description;
}

/* Period Generator
Parameters:
	data - String[], list of all shifts in this period
	period - String, period, either Morning, Lunch or Afternoon
	dayOfWeek - int, 0-6 representation of weekday
	returnOnlyID - boolean, if true, function will return a String[]
				   of shift data {Desc, Name}
Returns:
	HTML - The full HTML for one day's specific period
*/
function periodInDiv(data, period, dayOfWeek, returnOnlyID = false) {	
	// Grab individual user data
	var temp = String(data).split(", ");
	// Return Variables
	var IDs = [], data = "";
	
	// Parse data
	for (var k in temp) {
		if (temp[k] != "") {
			data += "<div>";
			var name = temp[k];
			
			// Generate Dialog
			if (isAdmin) {
				var description = generateDescription(period, dayOfWeek);
				IDs.push({Description: description, Name: name});			
				data += `<input type="checkbox" 
								id="` + description + "=" + name + `" 
								onclick="toggleShift('` + description + `', ` + hours[period] + `, '` + name + `');">
						</input>
						<span class="dialog-link" data-dialog="` + uniqueID + `" onclick="fetchIndividual('` + name + `', ` + uniqueID + `);">
							` + name + `
						</span>
						<div class="dialog" title="` + name + `" id="` + uniqueID + `">
							<div style="padding-bottom: 10px; font-weight: bold;">
								Total Hours: <span id="` + uniqueID + `-hours"></span>
							</div>
							<hr/>
							<div style="padding-top: 10px; padding-bottom: 10px;">
								<span style="font-weight: bold;">Edit Custom Shift: &nbsp;<small style="font-size: small;">Descriptions MUST be unique when adding!</small></span>
								<input id="` + uniqueID + `-addDesc" type="text" placeholder="Description" style="width: calc(100% - 4px)">
								<input id="` + uniqueID + `-addHours"type="number" step="any" placeholder="Hours" style="width: calc(75% - 4px);">
								<button style="width: calc(25% - 5px);" onclick="addShift('` + name + `', ` + uniqueID + `)">Save</button>
							</div>
							<hr/>
							<div style="padding-top: 10px; font-weight: bold;">All Completed Shifts:</div>
							<div id="` + uniqueID + `-allShifts" style="max-height: 200px; overflow-y: scroll; padding-bottom: 10px;">
							</div>
						</div>
				</div>`;
				uniqueID++;
		
			// If it's not on the admin panel, only write the name
			} else {
				data += name + "</div>";
			}
			
		// If there is nobody assigned to a specific
		} else {
			data += "<div>Closed!</div>";
		}
	}
	if (returnOnlyID)
		return IDs;
	return data;
}

// Ajax Requests

function toggleShift(desc, hours, name) {
	ajax('shifts/setShift.php', {
		Description: desc,
		Hours: hours,
		Name: name
	}, setShift);
}

/* Fetch Individual Data (Clicking from list of names in Period)
Parameters:
	name - String, Name who's shift we need to get
	dialogID - String, ID of the dialog we need to update
*/
function fetchIndividual(name, dialogID) {
	ajax("shifts/getHours.php", {Name: name}, getHours, {ID: dialogID});
	ajax("shifts/getCompleted.php", {Name: name}, getCompleted, {ID: dialogID});
}

/* Add/Edit Custom Shift (Clicking Edit Custom Shift "Save Button")
Parameters:
	name - String, Name who's shift we are adding
	dialogID - String, ID of the dialog we need to update
*/
function addShift(name, dialogID) {
	var desc = document.getElementById(dialogID + "-addDesc").value;
	var hours = document.getElementById(dialogID + "-addHours").value;
	ajax("shifts/setShift.php", {Name: name, Description: desc, Hours: hours}, addCustomShift, {ID: dialogID, Name: name});
}

/* Delete Completed Shift (Clicking Delete button in completed shifts section)
Parameters:
	id - int, SQL id of row to delete
	name - String, Name who's shift we are deleting (for refreshing page data)
	dialogID - String, ID of the dialog we need to update
*/
function removeShift(id, name, dialogID) {
	ajax("shifts/setShift.php", {ID: id}, deleteShift, {ID: dialogID, Name: name, ShiftID: id});
}

/* Delete Completed Shift (Clicking Delete button in completed shifts section)
Parameters:
	id - int, SQL id of row to delete
	name - String, Name who's shift we are deleting (for refreshing page data)
	dialogID - String, ID of the dialog we need to update
Global Variables:
	savingProcess - int, amount of dayOfWeek/period combintations that are not yet completed
*/
var savingProcess = 0;
function saveSchedule() {
	savingProcess += 15;
	ajax("schedule/set.php", {Date: "Mon", Period: "Morning", Data: document.getElementById("mon-morn").value}, schedule);
	ajax("schedule/set.php", {Date: "Mon", Period: "Lunch", Data: document.getElementById("mon-lunch").value}, schedule);
	ajax("schedule/set.php", {Date: "Mon", Period: "Afternoon", Data: document.getElementById("mon-after").value}, schedule);
	ajax("schedule/set.php", {Date: "Tue", Period: "Morning", Data: document.getElementById("tue-morn").value}, schedule);
	ajax("schedule/set.php", {Date: "Tue", Period: "Lunch", Data: document.getElementById("tue-lunch").value}, schedule);
	ajax("schedule/set.php", {Date: "Tue", Period: "Afternoon", Data: document.getElementById("tue-after").value}, schedule);	
	ajax("schedule/set.php", {Date: "Wed", Period: "Morning", Data: document.getElementById("wed-morn").value}, schedule);
	ajax("schedule/set.php", {Date: "Wed", Period: "Lunch", Data: document.getElementById("wed-lunch").value}, schedule);
	ajax("schedule/set.php", {Date: "Wed", Period: "Afternoon", Data: document.getElementById("wed-after").value}, schedule);	
	ajax("schedule/set.php", {Date: "Thu", Period: "Morning", Data: document.getElementById("thu-morn").value}, schedule);
	ajax("schedule/set.php", {Date: "Thu", Period: "Lunch", Data: document.getElementById("thu-lunch").value}, schedule);
	ajax("schedule/set.php", {Date: "Thu", Period: "Afternoon", Data: document.getElementById("thu-after").value}, schedule);	
	ajax("schedule/set.php", {Date: "Fri", Period: "Morning", Data: document.getElementById("fri-morn").value}, schedule);
	ajax("schedule/set.php", {Date: "Fri", Period: "Lunch", Data: document.getElementById("fri-lunch").value}, schedule);
	ajax("schedule/set.php", {Date: "Fri", Period: "Afternoon", Data: document.getElementById("fri-after").value}, schedule);
}

// API Callbacks

/* Schedule Callback (Invoked by saveSchedule)
Parameters:
	response - JSON, API response from backend
Global Variables:
	savingProcess - int, amount of dayOfWeek/period combinations that are not yet completed
	ajaxRequests - int, amount of AJAX requests still in progress
*/
function schedule(response) {
	savingProcess--;
	if (savingProcess == 0) {
		alert("Success!");
		indexLoad(true);
	}
	ajaxRequests -= 1;
}

/* Add/Edit Custom shift callback (Invoked by addShift)
Parameters:
	response - JSON, API response from backend
Global Variables:
	ajaxRequests - int, amount of AJAX requests still in progress
*/
function addCustomShift(response, params) {
	try {
		var r = JSON.parse(response);		
		if (r.action == "removeShift") {
			addShift(params.Name, params.ID);
		} else {
			alert("Success!");
		}
	} catch(e) {
		// Error Occurred
		alert(e);
	}
	ajaxRequests -= 1;
	fetchIndividual(params.Name, params.ID);
	indexLoad(true);
}

/* Remove shift callback (Invoked by removeShift)
Parameters:
	response - JSON, API response from backend
	params - JSON, passed from the ajax() base function
Global Variables:
	ajaxRequests - int, amount of AJAX requests still in progress
*/
function deleteShift (response, params) {
	try {
		var r = JSON.parse(response);		
		if (r.action == "removeShift") {
			alert("Success!");
		} else {
			removeShift(params.ShiftID, params.Name, params.dialogID);
		}
	} catch(e) {
		// Error Occurred
		alert(e);
	}
	ajaxRequests -= 1;
	fetchIndividual(params.Name, params.ID);
	indexLoad(true);
}

/* Get Individual Hours callback (Invoked by fetchIndividual)
Parameters:
	response - JSON, API response from backend
	params - JSON, passed from the ajax() base function
Global Variables:
	ajaxRequests - int, amount of AJAX requests still in progress
*/
function getHours(response, params) {
	try {
		var r = JSON.parse(response).data;
		document.getElementById(params.ID + "-hours").innerHTML = r;
	} catch(e) {
		// Error Occurred
		alert(e);
	}
	ajaxRequests -= 1;
}

/* Get Completed Shifts callback (Invoked by fetchIndividual)
Parameters:
	response - JSON, API response from backend
	params - JSON, passed from the ajax() base function
Global Variables:
	ajaxRequests - int, amount of AJAX requests still in progress
*/
function getCompleted(response, params) {
	try {
		var r = JSON.parse(response).data;
		document.getElementById(params.ID + "-allShifts").innerHTML = "";
		for (var k in r) {
			document.getElementById(params.ID + "-allShifts").innerHTML = `
				<div class="clearfix" style="width: 100%;">
				<button style="float: right; margin-right: 25px; padding-left: 5px; padding-right: 5px;" onclick="removeShift(` + k + `, '` + params.Name + `', '` + params.ID + `');">
					Delete
				</button> &nbsp;` + r[k][1] + " - " + r[k][0] + ` Hour(s)</div>` + document.getElementById(params.ID + "-allShifts").innerHTML;
		}
	} catch(e) {
		// Error Occurred
		alert(e);
	}
	ajaxRequests -= 1;
}

/* Get Today's Data callback (Invoked by indexLoad)
Parameters:
	response - JSON, API response from backend
Global Variables:
	ajaxRequests - int, amount of AJAX requests still in progress
*/
function getToday(response) {
	try {
		// Write Today's Date
		document.getElementById("today").children[0].innerHTML = parseDate(generateDescription().split(":")[0]);
		
		// Parse response into body		
		var r = JSON.parse(response).data;
		var today = document.getElementById("today");
		today.children[1].children[0].innerHTML = `
			<div class="col col1">
				<div class="heading">Morning</div>
				<div class="parent">
					<div class="child morningPeriod">
					` + periodInDiv(r.Morning, "Morning", 0) + `
					</div>
				</div>
			</div>
			<div class="col col2">
				<div class="heading">Lunch</div>
				<div class="parent">
					<div class="child lunchPeriod">
					` + periodInDiv(r.Lunch, "Lunch", 0) + `
					</div>
				</div>
			</div>
			<div class="col col3">
				<div class="heading">Afternoon</div>
				<div class="parent">
					<div class="child afternoonPeriod">
					` + periodInDiv(r.Afternoon, "Afternoon",0) + `
					</div>
				</div>
			</div>`;
			
		// Generate the dialogs if it's in the Admin page
		if (isAdmin) {
			var allShifts = [periodInDiv(r.Morning, "Morning", 0, true)];
			allShifts.push(periodInDiv(r.Lunch, "Lunch", 0, true));
			allShifts.push(periodInDiv(r.Afternoon, "Afternoon", 0, true));
			for (var k in allShifts)
				for (var i in allShifts[k])
					ajax("shifts/getShift.php", allShifts[k][i], getShiftState);
		}
	} catch(e) {
		// Error Occurred
		alert(e);
	}
	ajaxRequests -= 1;
	
	// Run the JS to re-initialize the dialogs
	if (isAdmin)
		initializeDialogs();
}

/* Get Weekly's Data callback (Invoked by indexLoad)
Note: This function is mostly the same as getToday, except that it
	  updates more than one row of data
Parameters:
	response - JSON, API response from backend
Global Variables:
	ajaxRequests - int, amount of AJAX requests still in progress
*/
function getWeek(response) {
	try {
		var r = JSON.parse(response).data;
		var week = document.getElementById("week");
		var allShifts = [];
		for (var i = 1; i <= 5; i++) {
			// Update Date
			week.children[i].children[0].innerHTML = parseDate(generateDescription("NaN", i).split(":")[0], true);
			
			// Update the schedule editor
			if (isAdmin) {
				var editor = document.getElementsByClassName("containerrow");
				editor[(i*3-3)].children[1].value = r[i].Morning;
				editor[(i*3-2)].children[1].value = r[i].Lunch;
				editor[(i*3-1)].children[1].value = r[i].Afternoon;
			}
			
			// Parse response into each day of the week	
			week.children[i].children[1].innerHTML = `
				<div class="col col1">
					<div class="heading">Morning</div>
					<div class="parent">
						<div class="child morningPeriod">
						` + periodInDiv(r[i].Morning, "Morning", i) + `
						</div>
					</div>
				</div>
				<div class="col col2">
					<div class="heading">Lunch</div>
					<div class="parent">
						<div class="child lunchPeriod">
						` + periodInDiv(r[i].Lunch, "Lunch", i) + `
						</div>
					</div>
				</div>
				<div class="col col3">
					<div class="heading">Afternoon</div>
					<div class="parent">
						<div class="child afternoonPeriod">
						` + periodInDiv(r[i].Afternoon, "Afternoon", i) + `
						</div>
					</div>
				</div>`;
			allShifts.push(periodInDiv(r[i].Morning, "Morning", i, true));
			allShifts.push(periodInDiv(r[i].Lunch, "Lunch", i, true));
			allShifts.push(periodInDiv(r[i].Afternoon, "Afternoon", i, true));
		} 
		if (isAdmin)
			for (var k in allShifts)
				for (var i in allShifts[k])
					ajax("shifts/getShift.php", allShifts[k][i], getShiftState);
	} catch(e) {
		// Error Occurred
		alert(e);
	}
	ajaxRequests -= 1;
	if (isAdmin)
		initializeDialogs();
}

/* Get shift completion status callback (Invoked by getToday and getWeek)
Parameters:
	response - JSON, API response from backend
Global Variables:
	ajaxRequests - int, amount of AJAX requests still in progress
*/
function getShiftState(response) {
	try {
		var r = JSON.parse(response);
		if (r.data) {
			document.getElementById(r.post_params.Description + "=" + r.post_params.Name).checked = true;
		} else {
			document.getElementById(r.post_params.Description + "=" + r.post_params.Name).checked = false;
		}
	} catch(e) {
		// Error Occurred
		alert(e);
	}
	ajaxRequests -= 1;
}

/* Toggle Shift callback (Invoked by toggleShift)
Parameters:
	response - JSON, API response from backend
Global Variables:
	ajaxRequests - int, amount of AJAX requests still in progress
*/
function setShift(response) {
	try {
		var r = JSON.parse(response);
		// Alert whether the shift was deleted/added successfully
		if (r.action == "removeShift") {
			alert("Shift Unset!");
			document.getElementById(r.post_params.Description + "=" + r.post_params.Name).checked = false;
	
		} else {
			alert("Shift Completed!");
			document.getElementById(r.post_params.Description + "=" + r.post_params.Name).checked = true;
		}
	} catch(e) {
		// Error Occurred
		alert(e);
	}
	ajaxRequests -= 1;
	
	// Reset Opened textboxes
	var a = document.getElementsByClassName("ui-icon-closethick");
	try {
		for (var k in a)
			a[k].click();
	} catch(e) {
	}
}