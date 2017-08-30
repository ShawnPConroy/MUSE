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
	global $muse; // App settings & database
	// Only let logged in people come through
	if( !$_SESSION['loggedIn'] ) {
		addLogToXML("You have been logged out. Please reload the page");
		echo $muse['xml']->saveXML();
		exit();
	}
}

/**
 * Breaks apart the client request to system variables.
 */
function processRequest(){
	global $muse; // App settings & database
	global $HTTP_RAW_POST_DATA;
	
	// Get the user's posted action
	$muse['actionRequestRaw'] = $HTTP_RAW_POST_DATA;
	$muse['actionRequest'] = $muse['db']->real_escape_string(trim($HTTP_RAW_POST_DATA));
	// First word of action request will be the action keyword.
	$muse['actionKeyword'] = strtolower(substr( $muse['actionRequest'], 0, strpos( $muse['actionRequest'], " " ) ) );
	
	// If it's only one word, through it back in. Capatlization issues?
	if ($muse['actionKeyword']==false) {
		$muse['actionKeyword'] = $muse['actionRequest'];
	}
}







responseStart();	// Start client response
checkLogin();		// Gatekeeper
processRequest();	// Process client request and store in system variables
getLogUpdates();	// Always show log updates

// Attempt to match to request type
if ( userAction( $muse['actionKeyword'], $muse['actionRequest'] ) ) {
} else if ( serverAction() ) {
} else if ( userActionMove( $muse['actionRequest'] ) ) {
} else if ( $muse['actionRequest'] != "update" ) {
	// Give an error message only if user entered an action
	addLogToXML("I'm not sure what you meant.");
}

responseFinish();		// End and display client response

?>