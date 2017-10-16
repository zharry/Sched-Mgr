function getHours() {
	var name = document.getElementById("getHoursStudentName").value;
	var xhttp = new XMLHttpRequest();
	xhttp.open("GET", "api/sched.php?action=getHours&name="+name, false);
	xhttp.send();
	var data = new Array(JSON.parse(xhttp.responseText))[0];
	
	var contentText = "<p><b>Student: </b>"+data["name"]+"</p>";
	if (data["hours"] == -1)
		contentText += "<p>No Records as of date!</p>";
	else
		contentText += "<p><b>Total Hours: </b>"+data["hours"]+"</p>";
	document.getElementById("getHoursStudentContent").innerHTML = contentText;
}

function setActiveSched() {
	var req = "";
	req += "&MON-morning="+document.getElementById("mon-morn").value;
	req += "&MON-lunch="+document.getElementById("mon-lunch").value;
	req += "&MON-afternoon="+document.getElementById("mon-after").value;
	req += "&TUE-morning="+document.getElementById("tue-morn").value;
	req += "&TUE-lunch="+document.getElementById("tue-lunch").value;
	req += "&TUE-afternoon="+document.getElementById("tue-after").value;
	req += "&WED-morning="+document.getElementById("wed-morn").value;
	req += "&WED-lunch="+document.getElementById("wed-lunch").value;
	req += "&WED-afternoon="+document.getElementById("wed-after").value;
	req += "&THU-morning="+document.getElementById("thu-morn").value;
	req += "&THU-lunch="+document.getElementById("thu-lunch").value;
	req += "&THU-afternoon="+document.getElementById("thu-after").value;
	req += "&FRI-morning="+document.getElementById("fri-morn").value;
	req += "&FRI-lunch="+document.getElementById("fri-lunch").value;
	req += "&FRI-afternoon="+document.getElementById("fri-after").value;
	
	var xhttp = new XMLHttpRequest();
	xhttp.open("GET", "api/activeSched.php?action=set"+req, false);
	xhttp.send();
	var data = new Array(JSON.parse(xhttp.responseText))[0];
	
	if ("error" in data)
		alert(data["error"]);
	else
		alert("Updated!");
}

function pushSched() {	
	var xhttp = new XMLHttpRequest();
	xhttp.open("GET", "api/activeSched.php?action=push", false);
	xhttp.send();
	var data = new Array(JSON.parse(xhttp.responseText))[0];
	
	if ("error" in data)
		alert(data["error"]);
	else {
		alert("Pushed!");
		reload();
	}
}

function pushSched() {	
	var xhttp = new XMLHttpRequest();
	xhttp.open("GET", "api/activeSched.php?action=pushThis", false);
	xhttp.send();
	var data = new Array(JSON.parse(xhttp.responseText))[0];
	
	if ("error" in data)
		alert(data["error"]);
	else {
		alert("Pushed!");
		reload();
	}
}

function completeShift(id) {
	var xhttp = new XMLHttpRequest();
	xhttp.open("GET", "api/sched.php?action=completeShift&id="+id, false);
	xhttp.send();
	var data = new Array(JSON.parse(xhttp.responseText))[0];
	console.log(data);
	if ("error" in data)
		alert(data["error"]);
	else {
		alert("Signed on!");
		reload();
	}
}

function reload() {
		window.location.reload(true);
}