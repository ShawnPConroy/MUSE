<?php
/**
 * Functions that read data from the database
 */

/**
 * Get the log updates for the current location.
 *
 * Gets the log update of all logged messages of type user, in the current location
 * and since the last update.
 *
 * TODO: Add whisper support, where the message location is the user ID.
 */
function getLogUpdates() {
	global $wb; // App settings & database

	$sql = "SELECT  *  FROM {$wb['DB_PREFIX']}_logs
	WHERE type='user'
	AND user_id != '{$_SESSION['userID']}' AND location = {$_SESSION['location']}
	AND timestamp > '{$_SESSION['lastUpdate']}'";
	addServerMessageToXML( $sql );
	$result = $wb['db']->query( $sql );
	while( $result && $log = $result->fetch_assoc() ) {
		addLogToXML( $log['message'] );
		$_SESSION['lastUpdate'] = $log['timestamp'];
	}

	return;
}




?>