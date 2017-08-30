<?php
/**
 * Server actions. Doesn't really do anything.
 *
 * This file is part of MUSE.
 *
 * @author	Shawn P. Conroy
 */

/**
 * Server actions. Doesn't really do anything.
 * 
 * @return boolean
 */
function serverAction() {
	global $muse; // App settings & database
	
	$result = true; // Assume innocent until proven guilty
	
	if ( $muse['actionKeyword'] == "ping" ) {
		addLogToXML("pong");
	} else if ( $muse['actionRequest'] == "open the pod bay doors, hal.") {
		addLogToXML("<span class=\"speech\">I'm sorry Dave, I can't do that...</span>");
	} else if ( $muse['actionRequest'] == "what time is it?") {
		addLogToXML("<span class=\"speech\">It's time to kick ass and chew bubble gum... and you're all outta gum.</span>");
	} else if ( $muse['actionRequest'] == "how do I feel?") {
		addLogToXML("<span class=\"speech\">Old school</span>");
	} else {
		$result = false;
	}
	return $result;
}
?>