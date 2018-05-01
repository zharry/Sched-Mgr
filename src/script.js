var isAdmin = false; // Check to see if the page is admin or not
var ajaxRequests = 0; // Used by Loading Screen to check how many active AJAX requests are still ongoing
var uniqueID = 371; // Used in Admin page dialog generation to provide each div with a unique ID

// Constants
var hours = {Morning: 0.5, Lunch: 0.5, Afternoon: 1};
var dateIDs = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

function indexLoad(update = false) {
	var date = new Date().getDay();
	// Sat and Sun Report as Monday
	if (date == 0 || date == 6)
		date = 1;
	
	// Query Data
	if (update) {
		ajax("schedule/get.php", {JustToday: ""}, getToday);
		ajax("schedule/get.php", {}, getWeek);
	} else {
		// Create collapsible elements for each weekday
		var coll = document.getElementsByClassName("collapsible");
		for (var i = 0; i < coll.length; i++) {
			// Allow Weekly Collapsible for all days but "Today"
			// Under admin, today is non-collapsible as the data is shown in the "Today" section
			if (coll[i].innerHTML.trim() != dateIDs[date] || !isAdmin) {
				coll[i].addEventListener("click", function() {
					var elem = this.nextElementSibling;
					this.classList.toggle("opened");
					if (elem.style.maxHeight) {
						elem.style.maxHeight = null;
						if (elem.id != "friday") {
							elem.style.borderBottom = "1px solid #dedede";
						}
					} else {
						elem.style.maxHeight = elem.scrollHeight + "px";
						elem.style.borderBottom = "0px solid #dedede";
					}
				});
			// Change ::after text to redirect attention to above
			} else {
				if (isAdmin) {
					coll[i].classList.toggle("notCollapsible");
				}
			}
		}
		ajax("schedule/get.php", {JustToday: ""}, getToday);
		ajax("schedule/get.php", {}, getWeek);
	}
	
	// Finish Up
	if (isAdmin) {
		initializeDialogs();
	}
	
	// Loading Screen
	console.log(setInterval(function() {
		if (ajaxRequests == 0)
			document.getElementById("loading").style.display = "none";
		else
			document.getElementById("loading").style.display = "inherit";
	}, 10));
}
// Set isAdmin to true, so that every other function will load admin elements
function adminLoad() {
	isAdmin = true;
}

/* Int to String Date Parser
Parameters:
	d - String, "year-month-day" as returned by something like
		generateDescription("NaN", [1-5]).split(":")[0];
	weekly - Boolean, Flag to display as "Monday (January 1)"
Returns:
	String - Representation of the date as "Monday, January 1st"
*/
function parseDate(d, weekly = false) {
	var val = "";
	var dd = d.split("-");
	var dateIOSFormatted = ("0000" + dd[0]).slice(-4) +"-"+ ("00" + dd[1]).slice(-2) +"-"+ ("00" + dd[2]).slice(-2);
	alert(dateIOSFormatted+"-"+d);
	var date = new Date(Date.parse(dateIOSFormatted));
	val += dateIDs[date.getDay()] + (weekly ? " (" : ", ");
	val += months[date.getMonth()] + " ";
	val += date.getDate();
	if (!weekly) {
		if (date.getDate() == 1 || date.getDate() == 21 || date.getDate() == 31)
			val += "st";
		else if (date.getDate() == 2 || date.getDate() == 22)
			val += "nd";
		else if (date.getDate() == 3 || date.getDate() == 23)
			val += "rd";
		else 
			val += "th";
	} else
		val += ")";
	return val;
}