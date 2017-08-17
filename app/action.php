<?php
// Connect to DB & stuff
include "./config.php";

/*****************************************************************************
 The Action File -- This file recieves the action request and returns the 
	proper response to the client.
 *****************************************************************************/


function checkLogin() {
	global $wb;
	// Only let logged in people come through
	if( !$_SESSION['loggedIn'] ) {
		addLogToXML("You have been logged out. Please reload the page");
		echo $wb['xml']->saveXML();
		exit();
	}
}

function processRequest(){
	global $wb;
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
function xmlStart() {
	global $wb;
	// Prepare AJAX XML response, with root node
	header("Content-Type: text/xml");
	
	$wb['xml'] = new DOMDocument();
	$wb['xml']->formatOutput = true;
	$wb['response'] = $wb['xml']->createElement( "response" );
	$wb['xml']->appendChild( $wb['response'] );
}

function xmlFinish() {
	global $wb;
	// Sends response
	echo $wb['xml']->saveXML();
}








xmlStart();
checkLogin();
processRequest();
getLogUpdates();	// Always show log updates

if ( userAction( $wb['actionKeyword'], $wb['actionRequest'] ) ) {
	// Ignore this line?
} else if ( serverAction() ) {
	// Ignore this line?
} else if ( userActionMove( $wb['actionRequest'] ) ) {
	// Ignore this line?
} else if ( $wb['actionRequest'] != "update" ) {
	// Give an error message only if user entered an action
	addLogToXML("I'm not sure what you meant.");
}

xmlFinish();

?>