<?php

/*
 * Functions that process information about/for objects.
 *
 * This file is part of MUSE.
 *
 * @author Shawn P. Conroy
 */


/**
 * Gets a single entity by identifier-name or indentification-number.
 *
 * option: limit search to specificed $location (rooms or objects)
 *	as list. Example, $location = "1, 5, 19" or "5".
 * option: Set $location = true for a full DB search
 * @param string $id Identifyer of object: either the ID or the name of the object.
 * @param string $type Optionally limit search to $type.
 * @param boolean $silent set to true to supress response to user.
 * @return array Single entity or false.
 */
function getEntity( $id, $location = null, $type = null, $silent = false ) {
	global $muse;
	
	
	/* If location is 'here' or 'me' get the ID number */
	if( $id == "here" ) {
		$id = "#".$_SESSION['location'];
	} else if ( $id == "me" ) {
		$id = "#".$_SESSION['userID'];
	}
	
	/* Determine if search by id number, or name */
	if( substr( $id, 0, 1 ) == "#" ) {
		$idType = "id";
		$id = substr( $id, 1);
		$locationSQL = "";
		$searchById = true;
	} else {
		$idType = "name";
		$searchById = false;
	}
	
	/* Determine location: if null, look here and in inventory, 
	 * if true, look everywhere, else look in $location.
	 * 
	 * If searching by id, search everywhere.
	 */ 
	if( is_null($location) && !$searchById ) {
		$location = $_SESSION['location'] .", ". $_SESSION['userID'];
		$locationSQL = "AND location IN ($location)";
	} else if( $searchById || $location === true ) {
		$locationSQL = "";
	} else {
		$locationSQL = "AND location IN ($location)";
	}
	
	/* If type specified, limit search to that type */
	if( $type != null ) {
		$typeSQL = "AND type = '$type'";
	}
	
	$sql = "SELECT * FROM {$muse['DB_PREFIX']}_entities
			WHERE ($idType = '{$id}' OR $idType LIKE '{$id};%' OR $idType LIKE '%;{$id};%') $typeSQL $locationSQL;";
			
	addServerMessageToXML( $sql );
	
	$result = $muse['db']->query($sql);
	
	/* If no matches, do a wider search */
	if( $result && $result->num_rows == 0 ) {
		$sql = "SELECT * FROM {$muse['DB_PREFIX']}_entities
				WHERE $idType LIKE '%{$id}%' $typeSQL $locationSQL;";
		addServerMessageToXML( $sql );
	
		$result = $muse['db']->query($sql);
	}

	/* Get all data for this object and return it, or return a list of
	 * matching objcts, or specify that it wasn't found.
	 */
	if( $result && $result->num_rows == 1 ) {
		$result = $result->fetch_assoc();
		$result['names'] = $result['name'];
		$result['name'] = strtok($result['name'], ';');
		getExtendedData( $result );
	} else 	if( $result && $result->num_rows > 1 ) {
		if( !$silent ) addNarrativeToXML("Which $type do you mean? " . listOfEntities( $result ) );
		$result = false;
	} else if( $result && $result->num_rows == 0 ) {
		if( !$silent ) addNarrativeToXML("That $type isn't here.");
		$result = false;
	}

	return $result;
}


/**
 * Takes the $entity and inserts to the array
 * extended data about the object to the original array
 * in the calling function.
 *
 * @param array $entity An array representing an in-world object
 * @return array An array representing an in-world object with all fields
 */
function getExtendedData( &$entity ) {
	global $muse;
	
	$result = $muse['db']->query("SELECT * FROM {$muse['DB_PREFIX']}_extended
				WHERE entity_id = '{$entity['id']}';");
	
	while( $result && $parameter = $result->fetch_assoc() ) {
		$entity[ $parameter['name'] ] = $parameter['value'];
	}
	
	return $result;
}


/**
 * Return a list of all objects in a query result.
 * @param unknown $entities query result
 * @return string readable list of object names
 */
function listOfEntities( $entities ) {
	while( $entity = $entities->fetch_assoc() ) {
		if ( !empty($list) ) {
			$list .=", ".$entity['name'];
		} else {
			$list = $entity['name'];
		}
	}
	return $list;
}


/**
 * Determines if the user is unable (locked) from performing an action, like
 * take or pass through an exit.
 * 
 * Assumes true as most objects don't have locks. If this object has a lock,
 * it will assume false.
 * 
 * Either it is locked to a userID, or it is locked to an object ID that is
 * in the user's inventory.
 * 
 * Limitation: There is currently no way to have more than one key to the lock.
 * For example, having ten keys to a club house so that all members may enter
 * the club house. Is this possible in a MUSH?
 * 
 * Maybe could have a clone of or based on extended field that it could check?
 * 
 * This doesn't work yet, fully.
 * 
 * @param unknown $entity
 * @return Ambigous <boolean, multitype:, string>
 */
function passesLock( $entity ) {

	$result = true;
	if( isset( $entity['lock'] ) ) {
		$result = false;
		if( substr( $entity['lock'], 1 ) == $_SESSION['userID'] ) {
			$result = true;
		} else {
		
			$result = getEntity( $entity['lock'], $_SESSION['userID'], null, true );
		}
	}
	
	return $result;
}


/**
 * Move the user object.
 * 
 * Determines if the user can move throught he exit. If so, does so.
 * Also, add proper log and send info in client response.
 * 
 * @param unknown $exit
 * @return Ambigous <boolean, unknown>
 */
function moveUser($exit) {
	global $muse; // App settings & database
	
	$user['id'] = $_SESSION['userID'];
	
	/* Move the user if lock is passed, and exit links somewhere.
	 * Or, show fail log message. Or, say exit doesn't go anywhere.
	 */
	
	if( $passLock = passesLock( $exit ) &&  !is_null($exit['link']) &&
	    $result = moveEntity( $user, $exit['link'] ) ) {
		if( isset( $exit['success'] ) ) {
			addNarrativeToXML( $exit['success'] );
		}
		
		if ( isset( $exit['osuccess'] ) ) {
			insertLog("user", $_SESSION['userID'], $_SESSION['location'],
				$_SESSION['username']." ".$exit['osuccess']);
		} else {
			// User is moving but was not picked up
			insertLog("user", $_SESSION['userID'], $_SESSION['location'],
				$_SESSION['username'] . " went " . $exit['name'] );
		}

		$_SESSION['location'] = $exit['link'];
		$enterMessage = $_SESSION['username'] . " is now here.";
		insertLog("user", $_SESSION['userID'], $_SESSION['location'], $enterMessage );
	} else if( !$passLock ) {
		if( isset( $exit['fail'] ) ) {
			addNarrativeToXML( $exit['fail'] );
		} else {
			addNarrativeToXML("{$exit['name']} is locked.");
		
		}
		
		if( isset( $exit['ofail'] ) ) {
			insertLog("user", $_SESSION['userID'], $_SESSION['location'],
				$_SESSION['username'] .  $exit['ofail'] );		
		}
	} else if ( is_null($exit['link']) ) {
		addNarrativeToXML("That exit doesn't lead anywhere.");
		$result = false;
	}
		
	return $result;
}
?>