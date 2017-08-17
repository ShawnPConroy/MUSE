<?

/**
 * Create object.
 * 
 * User is creating an object, in form
 *	@create Object Name
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
 * @param String $item item to change ownership's name or ID
 * @param String $newOwner name or ID of the new owner.
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
	
	/* Execute! */
}

/**
 * Set object description.
 * 
 * User is describing an object, in form:
 *	@describe object name = Object's description.
 * or
 *	@desc object name = Object's description.
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
 * @param unknown $actionRequest
 * @return int room id
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
 * Destroy object.
 * 
 * @param unknown $actionRequest
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
 * Create an exit between two rooms.
 * 
 * @param unknown $actionRequest
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
 * @param unknown $actionRequest
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
 * @param unknown $actionRequest
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
 * @param unknown $actionRequest
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