function initializeDialogs() {
	$( ".dialog" ).dialog({
		autoOpen: false,
		resizable: false,
		width: 500
	});

	// Link to open the dialog
	$( ".dialog-link" ).click(function( event ) {
		$( "#" + this.dataset.dialog ).dialog( "open" );
		event.preventDefault();
	});
}