<?php
/*
 * Functions that read data from the database for MUSE.
 *
 * This file is part of MUSE.
 *
 * @author	Shawn P. Conroy
 */


/**
 * Used by look (and other?) to list all objects in this room. 
 * Need to update getEntities
 *
 * @param string $location the location ID of the user
 * @param string $type the of entities to return (optional)
 * @return mixed returns the result of mysqli::query
 */

function getEntitiesByLocation( $location, $type = null ) {
	global $muse; // App settings & database
	
	$sql = "SELECT * FROM {$muse['DB_PREFIX']}_entities WHERE location = {$location};";
	$entities = $muse['db']->query($sql);
	addServerMessageToXML($sql." Number of rows:". $entities->num_rows);
	
	return $entities;
}


/**
 * Find the basic information of the supplied location ID
 *
 * @param string $locationId the entity ID number of the location
 * @return array current room fields array
 */

function getLocation( $locationId ) {
	global $muse; // App settings & database
	
	$location = $muse['db']->query("SELECT * FROM {$muse['DB_PREFIX']}_entities WHERE id = '$locationId';");
	$location = $location->fetch_assoc();
	return $location;
}


/**
 * Get the object in specified location by the given name.
 *
 * @param string $name Name of entity to search for.
 * @param string $location Location ID of where to search.
 * @return mixed Object fields array or false if not found.
 */
 
function getLocationEntityByName( $name, $location ) {
	global $muse; // App settings & database
	
	addServerMessageToXML("--Searching all objects named $name!".
	"SELECT * FROM {$muse['DB_PREFIX']}_entities
		WHERE name = '{$name}' AND location = '{$location}';");
	$object = $muse['db']->query("SELECT * FROM {$muse['DB_PREFIX']}_entities
		WHERE name = '{$name}' AND location = '{$location}';");
	if( $object->num_rows ) {
		addServerMessageToXML("----Found something!");
		return $object->fetch_assoc();
	} else {
		addServerMessageToXML("----No entity...");
		return false;
	}
}

/**
 * Get the log updates for the current location.
 *
 * Gets the log update of all logged messages of type user, in the current location
 * and since the last update.
 *
 * TODO: Add whisper support, where the message location is the user ID.
 *
 * @return void
 */
function getLogUpdates() {
	global $muse; // App settings & database

	$sql = "SELECT  *  FROM {$muse['DB_PREFIX']}_logs
	WHERE type='user'
	AND user_id != '{$_SESSION['userID']}' AND location = {$_SESSION['location']}
	AND timestamp > '{$_SESSION['lastUpdate']}'";
	addServerMessageToXML( $sql );
	$result = $muse['db']->query( $sql );
	while( $result && $log = $result->fetch_assoc() ) {
		addLogToXML( $log['message'] );
		$_SESSION['lastUpdate'] = $log['timestamp'];
	}

	return;
}


?>