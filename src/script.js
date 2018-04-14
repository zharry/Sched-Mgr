var isAdmin = false; // Check to see if the page is admin or not
var ajaxRequests = 1; // Used by Loading Screen to check how many active AJAX requests are still ongoing
var uniqueID = 371; // Used in Admin page dialog generation to provide each div with a unique ID

// Constants
var hours = {Morning: 0.5, Lunch: 0.5, Afternoon: 1};
var dateIDs = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

function indexLoad(update = false) {
	var date = new Date().getDay();
	// Sat and Sun Report as Monday
	if (date == 0 || date == 6)
		date = 1;
	
	// Query Data
	if (update) {
		ajax("schedule/get.php", {JustToday: ""}, getToday);
		ajax("schedule/get.php", {}, getWeek, ["Update"]);
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
					coll[i].innerHTML += "<small>&nbsp; (See Today)</small>";
				}
			}
		}
		ajax("schedule/get.php", {JustToday: ""}, getToday);
		ajax("schedule/get.php", {}, getWeek);
	}
	
	// Finish Up
	if (isAdmin)
		initializeDialogs();
	
	ajaxRequests -= 1;
}
// Set isAdmin to true, so that every other function will load admin elements
function adminLoad() {
	isAdmin = true;
}

// Loading Screen
console.log(setInterval(function() {
	if (ajaxRequests == 0)
		document.getElementById("loading").style.display = "none";
	else
		document.getElementById("loading").style.display = "inherit";
}, 10));