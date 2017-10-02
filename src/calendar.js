var Calendar = function(o) {
    //Store div id
    this.divId = o.ParentID;

    // Days of week, starting on Sunday
    this.DaysOfWeek = o.DaysOfWeek;

    // Months, stating on January
    this.Months = o.Months;

    // Set the current month, year
    var d = new Date();
    this.CurrentMonth = d.getMonth();
    this.CurrentYear = d.getFullYear();
    var f = o.Format;

    this.f = typeof(f) == 'string' ? f.charAt(0).toUpperCase() : 'M';
};

// Goes to next month
Calendar.prototype.nextMonth = function() {
    if (this.CurrentMonth == 11) {
        this.CurrentMonth = 0;
        this.CurrentYear = this.CurrentYear + 1;
    } else {
        this.CurrentMonth = this.CurrentMonth + 1;
    }
    this.showCurrent();
};

// Goes to previous month
Calendar.prototype.previousMonth = function() {
    if (this.CurrentMonth == 0) {
        this.CurrentMonth = 11;
        this.CurrentYear = this.CurrentYear - 1;
    } else {
        this.CurrentMonth = this.CurrentMonth - 1;
    }

    this.showCurrent();
};

// Show current month
Calendar.prototype.showCurrent = function() {
    this.Calendar(this.CurrentYear, this.CurrentMonth);
};

// Show month (year, month)
Calendar.prototype.Calendar = function(y, m) {
    typeof(y) == 'number' ? this.CurrentYear = y: null;
    typeof(y) == 'number' ? this.CurrentMonth = m: null;

    // 1st day of the selected month
    var firstDayOfCurrentMonth = new Date(y, m, 1).getDay();
    // Last date of the selected month
    var lastDateOfCurrentMonth = new Date(y, m + 1, 0).getDate();
    // Last day of the previous month
    var lastDateOfLastMonth = m == 0 ? new Date(y - 1, 11, 0).getDate() : new Date(y, m, 0).getDate();

    // Write selected month and year. This HTML goes into <div id="month"></div>
    var monthandyearhtml = '<span class="monthandyearspan">' + this.Months[m] + ' - ' + y + '</span>';

    var html = '<table>';
    // Write the header of the days of the week
    html += '<tr>';
    for (var i = 0; i < 5; i++) {
        html += '<th class="daysheader">' + this.DaysOfWeek[i] + '</th>';
    }
    html += '</tr>';

    var p = dm = this.f == 'M' ? 1 : firstDayOfCurrentMonth == 0 ? -5 : 2;

    var cellvalue;
    for (var d, i = 0, z0 = 0; z0 < 6; z0++) {
        html += '<tr>';
        for (var z0a = 0; z0a < 7; z0a++) {
            d = i + dm - firstDayOfCurrentMonth;

            // Dates from prev month
            if (d < 1) {
                cellvalue = lastDateOfLastMonth - firstDayOfCurrentMonth + p++;
				if (z0a != 5 && z0a != 6)
					html += '<td class="prevmonthdates">' + (cellvalue) + '</td>';

                // Dates from next month
            } else if (d > lastDateOfCurrentMonth) {
				if (z0a != 5 && z0a != 6)
					html += '<td class="nextmonthdates">' + (p++) + '</td>';

                // Current month dates
            } else {
				if (z0a != 5 && z0a != 6) {
					html += '<td class="currentmonthdates">';
					html += '<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#'+y+'-'+(m+1)+'-'+d+'">' + d + '</button>';
					html += '</td>';
					
					var xhttp = new XMLHttpRequest();
					xhttp.open("GET", "api/sched.php?action=getDate&date="+y+"-"+(m+1)+"-"+d, false);
					xhttp.send();
					
					var shiftInfo = {"morning":"", "lunch":"", "afternoon":""};
					var data = new Array(JSON.parse(xhttp.responseText));
					for (var shift of new Array(data[0]["data"])) {
					console.log(shift);
						if (shift != null) {
							for (var shifti = 0; shifti < shift.length; shifti++) {
								var shiftModal = '<button type="button" class="btn btn btn-lg" data-toggle="modal" data-target="#shiftModal'+shift[shifti]["id"]+'">'+shift[shifti]["name"]+'</button> ';
								shiftModal += '<div class="modal fade" id="shiftModal'+shift[shifti]["id"]+'" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button>';
								shiftModal += '<h4 class="modal-title">'+shift[shifti]["name"]+'</h4></div><div class="modal-body">';
								if (shift[shifti]["done"] != 0) {
									shiftModal += '<p>Shift Completed!</p>';	
								} else {
									shiftModal += '<p><button type="button" class="btn" onclick="completeShift('+shift[shifti]["id"]+')">Sign On</button></p>';	
								}
								shiftModal += '</div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></div></div></div>';
								shiftInfo[shift[shifti]["period"]] = shiftInfo[shift[shifti]["period"]] + shiftModal;
							}
						} 
					}
					console.log(shiftInfo);
					
					var newModal = '<div id="'+y+'-'+(m+1)+'-'+d+'" class="modal fade" role="dialog"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button>';
					newModal += '<h4 class="modal-title">'+this.Months[m]+'-'+d+'</h4>';
					newModal += '</div><div class="modal-body"><hr/>';
					newModal += 'Morning:<br/>' + shiftInfo["morning"] + '<hr/>';
					newModal += 'Lunch:<br/>' + shiftInfo["lunch"] + '<hr/>';
					newModal += 'Afterschool:<br/>' + shiftInfo["afternoon"] + '<hr/>';
					newModal += '</div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></div></div></div>';
					
					document.getElementById("modals").innerHTML = document.getElementById("modals").innerHTML + newModal;
				}
                p = 1;
            }

            if (i % 7 == 6 && d >= lastDateOfCurrentMonth) {
                z0 = 10; // no more rows
            }
            i++;
        }
        html += '</tr>';
    }

    // Closes table
    html += '</table>';

    document.getElementById("monthandyear").innerHTML = monthandyearhtml;
    document.getElementById(this.divId).innerHTML = html;
};

// On Load of the window
window.onload = function() {
    // Start calendar
    var c = new Calendar({
        ParentID: "divcalendartable",

        DaysOfWeek: [
            'MON',
            'TUE',
            'WED',
            'THU',
            'FRI',
            'SAT',
            'SUN'
        ],

        Months: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],

        Format: 'dd/mm/yyyy'
    });

    c.showCurrent();
	
	var xhttp = new XMLHttpRequest();
	xhttp.open("GET", "api/activeSched.php?action=fetch", false);
	xhttp.send();
	var data = new Array(JSON.parse(xhttp.responseText))[0];
	
	document.getElementById("mon-morn").value = data["MON"]["morning"];
	document.getElementById("mon-lunch").value = data["MON"]["lunch"];
	document.getElementById("mon-after").value = data["MON"]["afternoon"];
	document.getElementById("tue-morn").value = data["TUE"]["morning"];
	document.getElementById("tue-lunch").value = data["TUE"]["lunch"];
	document.getElementById("tue-after").value = data["TUE"]["afternoon"];
	document.getElementById("wed-morn").value = data["WED"]["morning"];
	document.getElementById("wed-lunch").value = data["WED"]["lunch"];
	document.getElementById("wed-after").value = data["WED"]["afternoon"];
	document.getElementById("thu-morn").value = data["THU"]["morning"];
	document.getElementById("thu-lunch").value = data["THU"]["lunch"];
	document.getElementById("thu-after").value = data["THU"]["afternoon"];
	document.getElementById("fri-morn").value = data["FRI"]["morning"];
	document.getElementById("fri-lunch").value = data["FRI"]["lunch"];
	document.getElementById("fri-after").value = data["FRI"]["afternoon"];

    // Bind next and previous button clicks
    document.getElementById('btnPrev').onclick = function() {
        c.previousMonth();
    };
    document.getElementById('btnNext').onclick = function() {
        c.nextMonth();
    };
}