<?
/*
 * Functions that write to the database.
 *
 * This file is part of MUSE.
 *
 * @author	Shawn P. Conroy
 */

/**
 * Process user's create request. ("@create Object Name")
 *
 * Cuts off the @create, and creates and object with that name,
 * in user's inventory. Adds action to user log.
 * 
 * @param string $actionRequest The user's request, starting with @create.
 * @returns void
 * 
 */
function userActionCreate( $actionRequest ) {
	$name = substr( $actionRequest, 8 );
	if( createEntity( $name,  $_SESSION['userID'], "object" ) ) {
		addLogToXML("Created $name.");
		addTextElement("inventory", $name);
	} else {
		addLogToXML("Failed to create $name.");
	}
}

/**
 * Changes the ownership field of the object.
 * 
 * Cut off the @chown, parse out the object name and new owner's name.
 * Then get info and call changeOwner(). Update user log.
 *
 * @param string $actionRequest The user request starting with @chown.
 * @return void
 */
function userActionChangeOwner( $actionRequest ) {
	$actionKeyword = strtok( $actionRequest, " " ); // @chown followed by a space
	$name = trim(strtok( "=" )); // Then name followed by equals
	$newOwner = trim(strtok('')); // get the rest of the string
	
	/* Get the ID of the object, and the new owner */
	$item = getEntity( $name );
	$newOwner = getEntity( $newOwner, true, 'user', false );
	
	/* Test that the object belongs to this user, and newUser is a user */
	if ( $item && $item['owner'] == $_SESSION['userID'] && $newOwner) {
		changeOwner( $item['id'], $newOwner['id'] );
	} else if ( !$item ) {
		addLogToXML("Item not found.");
	} else if ( !$newOwner ) {
		addLogToXML("Cannot find user.");
	} else {
		addLogToXML("You don't own that.");
	}
	
}

/**
 * Set object description.
 * 
 * User is describing an object, in form:
 *	@describe object name = Object's description.
 * or
 *	@desc object name = Object's description.
 *
 * Tokenize the string and set the description field.
 * Add success to user log.
 *
 * @param string $actionRequest The user request starting with @desc.
 * @return void
 */
function userActionDescribe( $actionRequest ) {
	$actionKeyword = strtok( $actionRequest, " " ); // Is either @desc or @describe followed by a space
	$name = trim(strtok( "=" )); // Then name followed by equals
	$description = trim(strtok('')); // get the rest of the string
	
	if( !empty($name) && !empty($description)) {
		addServerMessageToXML("Describing '$name' with '$description'.");
		
		if( $name == "me" ) {
			setEntityField("description", $_SESSION['userID'], $description );
			addLogToXML("Described $name.");
		} else if ( $name == "here" ) {
			setEntityField("description", $_SESSION['location'], $description );
			addLogToXML("Described $name.");
		} else {		
			// Find item & apply description
			$entity = getEntity( $name );
		
			if( $entity ) {
				if ( setEntityField("description", $entity['id'], $description ) ) {
					addLogToXML("Described $name.");
				} else {
					addLogToXML("Failed to describe {$entity['name']}.");
				}
			}
		}
	} else {
		addLogToXML("Did you mean '@desc object = description'?");
	}
	
	return;

}

/**
 * Creates a new room.
 * 
 * Tokenizes the string, and creates a new room, and exit, and links.
 * between them, and adds it to the user's log.
 *
 * @param string $actionRequest The user request, of form "@dig exit name = exit location, return exit" name (I think).
 * @return string room id number
 */
function userActionDig( $actionRequest ) {
	$actionKeyword = strtok( $actionRequest, " " );
	$roomName = trim( strtok( '=' ) );
	$exitTo = trim( strtok(',') );
	$entranceFrom = trim( strtok('') );
	
	if( !empty( $roomName ) ) {
		
		$roomID = createEntity( $roomName, null, 'room' );
		
		if( $roomID ) {
			addLogToXML("Dug.");
			if( !empty($exitTo) && !empty($entranceFrom) 
				&& createExit( $_SESSION['location'], $exitTo, $roomID, $entranceFrom ) ) {
				//addLogToXML("Rooms linked");
			} else if ( !empty($exitTo) && !empty($entranceFrom) ) {
				addLogToXML("Room link failed.");
			}
			userActionFullUpdate( $_SESSION['location'], true );
		} else  {
			addLogToXML("Dig failed.");
		}
	
	}
	
	return $roomID;
}

/**
 * Destroy object. Searching for object by name, and destroys it.
 * 
 * @param string $actionRequest User action "@destroy object name".
 * @return void
 */
function userActionDestroy( $actionRequest ) {
	$actionKeyword = strtok( $actionRequest, " " );
	$name = trim( strtok('') );
	
	$locations[] = $_SESSION['userID'];
	$locations[] = $_SESSION['location'];
	
	$entity = getEntity( $name );
	
	if( $entity ) {
		if( destroyEntity( $entity ) ) {
			addLogToXML("Destroyed {$entity['name']}.");
			userActionFullUpdate( $_SESSION['location'], true );
		} else {
			addLogToXML("Failed to destroy {$entity['name']}.");
		}
	}
	
}

/**
 * Links an exit to a room.
 * 
 * Tokenizes the string, finds both exits and links
 * @param string $actionRequest The user's action requestion, starting with @link.
 * @return void
 */
function userActionLink( $actionRequest ) {
	$actionKeyword = strtok( $actionRequest, " " );
	$exitName = trim( strtok( '=' ) );
	$roomName = trim( strtok('') );
	
	// $exit = getExitByName( $exitName, $_SESSION['location'] );
	$exit = getEntity( $exitName, $_SESSION['location'], "exit" );
	$room = getEntity( $roomName, true, "room" );
	
	if( !$exit ) {
		addLogToXML("No such exist here.");
	}
	if ( !$room ) {
		addLogToXML("No such room exists");
	}
	
	if( $exit && $room ) {
		if( linkExit( $exit['id'], $room['id'] ) ) {
			//addLogToXML("Linked exit '{$exit['name']}' to '{$room['name']}'.");
		} else {
			addLogToXML("Link failed.");
		}
	}
	
}

/**
 * Rename an object.
 * 
 * @param string $actionRequest The user request starting with @name.
 * @return void
 */
function userActionName( $actionRequest ) {
	addServerMessageToXML("Renaming...");
	$actionKeyword = strtok( $actionRequest, " " );
	$oldName = trim( strtok( '=' ) );
	$newName = trim( strtok('') );
	addServerMessageToXML("Looking for '$oldName' to change to '$newName'.");
	$object = getEntity( $oldName );
	
	AddServerMessageToXML("--if...");
	if( $object ) {
		AddServerMessageToXML("----if 1");
		if( setEntityField("name", $object['id'], $newName) ) {
			addLogToXML("Name set.");
		} else {
			addLogToXML("Failed to name.");
		}
	}
	addServerMessageToXML("--Done.");
	userActionFullUpdate( $_SESSION['location'], true );
	return;
}

/**
 * Create an exit. (Which goes nowhere.)
 * 
 * @param string $actionRequest The user request, starting with @open.
 * @return void
 */
function userActionOpen( $actionRequest ) {
	$actionKeyword = strtok( $actionRequest, " " );
	$exitName = trim( strtok( '=' ) );
	$exitToName = trim( strtok(',') );
	$returnName = trim( strtok('') );
	
	if( empty( $exitTo ) ) {
		$exitTo = null;
	}
	if( empty( $returnName ) ) {
		$returnName = null;
	}
	
	$exitTo = getEntity( $exitToName, true, "room" );
	
	if( createExit( $_SESSION['location'], $exitName, $exitTo['id'], $returnName ) ) {
		userActionFullUpdate($_SESSION['location'], true );
	} else {
		addLogToXML("Exit failed to be created.");
	}
	
	return;
		
}


/**
 * Creative user requests, the build, destroy et cetera actions.
 * 
 * @param string $actionRequest The user's request.
 * @return void
 */

Function userBuildActions( $actionRequest ) {
	$actionKeyword = strtok( $actionRequest, " " );
	$name = trim( strtok( '=' ) );
	$value = trim( strtok('') );
	addServerMessageToXML("User Build Action: '$actionKeyword', Object '$name', Value '$value'.");

	if( $actionKeyword == "@create" ) {
		userActionCreate( $actionRequest );
	} else if( $actionKeyword == "@chown" ) {
		userActionChangeOwner( $actionRequest );
	} else if( $actionKeyword == "@destroy" ) {
		userActionDestroy( $actionRequest );
	} else if( $actionKeyword == "@describe" || $actionKeyword == "@desc" ) {
		userActionDescribe( $actionRequest );
	} else if( $actionKeyword == "@dig" ) {
		userActionDig( $actionRequest );
	} else if( $actionKeyword == "@name" ) {
		userActionName( $actionRequest );
	} else if( $actionKeyword == "@open" ) {
		userActionOpen( $actionRequest );
	} else if( $actionKeyword == "@link" ) {
		userActionLink( $actionRequest );
	} else if( $actionKeyword == "@set" ) {
		if( $entity = getEntity( $name ) ) {
			setExtendedData ($entity, $value, null );
		}
	} else {
		$entity = getEntity( $name );
		if( $entity ) {
			setExtendedData( $entity, substr($actionKeyword,1) , $value );
		}
	}
}
?>