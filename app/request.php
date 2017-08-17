<?php
// Connect to DB & stuff
include "./config.php";

/*****************************************************************************
 The Action File -- This file recieves the action request and returns the 
	proper response to the client.
 *****************************************************************************/

/**
 * Gatekeeper function.
 */
function checkLogin() {
	global $wb; // App settings & database
	// Only let logged in people come through
	if( !$_SESSION['loggedIn'] ) {
		addLogToXML("You have been logged out. Please reload the page");
		echo $wb['xml']->saveXML();
		exit();
	}
}

/**
 * Breaks apart the client request to system variables.
 */
function processRequest(){
	global $wb; // App settings & database
	global $HTTP_RAW_POST_DATA;
	
	// Get the user's posted action
	$wb['actionRequestRaw'] = $HTTP_RAW_POST_DATA;
	$wb['actionRequest'] = $wb['db']->real_escape_string(trim($HTTP_RAW_POST_DATA));
	// First word of action request will be the action keyword.
	$wb['actionKeyword'] = strtolower(substr( $wb['actionRequest'], 0, strpos( $wb['actionRequest'], " " ) ) );
	
	// If it's only one word, through it back in. Capatlization issues?
	if ($wb['actionKeyword']==false) {
		$wb['actionKeyword'] = $wb['actionRequest'];
	}
}







responseStart();	// Start client response
checkLogin();		// Gatekeeper
processRequest();	// Process client request and store in system variables
getLogUpdates();	// Always show log updates

// Attempt to match to request type
if ( userAction( $wb['actionKeyword'], $wb['actionRequest'] ) ) {
} else if ( serverAction() ) {
} else if ( userActionMove( $wb['actionRequest'] ) ) {
} else if ( $wb['actionRequest'] != "update" ) {
	// Give an error message only if user entered an action
	addLogToXML("I'm not sure what you meant.");
}

responseFinish();		// End and display client response

?>