<?php

/*
 * Actions you would expect common users to issue: look, drop, examine, move, say take.
 *
 * This file is part of MUSE.
 *
 * @author	Shawn P. Conroy
 */

/**
 * Add a full update sending the client application all information to display
 * in helper lists and issue the look user action to get a description of the room.
 * 
 * @param string $location the location
 * @param boolean $silent
 * @return void
 */
function userActionFullUpdate( $location= null, $silent = false ) {
	if( $location == null ) {
		$location = $_SESSION['location'];
	}
	
	/* Clear the lists and repopulate */
	addServerMessageToXML("clear");
	addLocationEntitiesToResponse( $location );
	addLocationEntitiesToResponse( $_SESSION['userID'], "inventory");
	
	/* describe location */
	addLocationToResponse( $location );
	if( !$silent ) {
		userActionLook( "look" ); // get room description
	}
	
	return;
}

/**
 * Adds contents of the room to response.
 * @param unknown $actionRequest Requested action
 */
function userActionLook( $actionRequest ) {
	
	/* Determine where the item name starts, get name */
	if( strpos( $actionRequest, "look at" ) == 0 && strpos( $actionRequest, "look at" ) !== false ) {
		$start = 8;
	} else {
		$start = 5;
	}
	$name = trim(substr( $actionRequest , $start ));
	
	/* Determine if a look command, a full update, or look at me command */
	if( $actionRequest == "look" || $name == "here" ) {
		$location = getLocation( $_SESSION['location'] );
		$entities = getEntitiesByLocation( $_SESSION['location'] );
		addServerMessageToXML("entities?");
		if(!$entities) {
			addServerMessageToXML("No entities");
		} else {
			addServerMessageToXML("Num rows: ".$entities->num_rows);
		}
		while( $entities && $entity = $entities->fetch_assoc() ) {
			addServerMessageToXML("Entity!n {$entity[name]}");
			$entity['name'] = strtok($entity['name'], ';');
			getExtendedData( $entity );
			if( !isset($entity['dark']) && $entity['type'] == "object" ) {
				if( !empty( $objects ) ) {
					$objects .= ", " . $entity['name'];
				} else {
					$objects .= "<em>You can see</em>: " . $entity['name'];
				}
			} else if( !isset($entity['dark']) && $entity['type'] == "exit" ) {
				if( !empty( $exits ) ) {
					$exits .= ", " . $entity['name'];
				} else {
					$exits .= "<em>Obvious exits are</em>: " . $entity['name'];
				}
			} else if ( !isset($entity['dark']) && $entity['type'] == "user" && $entity['id'] != $_SESSION['userID'] ) {
				if( !empty( $players ) ) {
					$players .= ", " . $entity['name'];
				} else {
					$players .= "<em>Also here</em>: " . $entity['name'];
				}
			}
		}
		addServerMessageToXML("Ended loop");
		
		
		$xml = "<div class='location'>".$location['name']."</div>";
		if( !empty($location['description']) ) {
			$xml .= $location['description']."<br>";
		} else addServerMessageToXML("Desc empty");
		if( !empty($objects) ) {
			$xml .= $objects."<br>";
		} else addServerMessageToXML("objects empty");
		if( !empty($exits) ) {
			$xml .= $exits."<br>";
		} else addServerMessageToXML("exits empty");
		if( !empty($players) ) {
			$xml .= $players."<br>";
		} else addServerMessageToXML("players empty");
		addLogToXML( $xml );
	} else if ($actionRequest == "full-update") {
		userActionFullUpdate( $_SESSION['location'] );
	} else {
		
		if( $name == "me" ) {
			$name = $_SESSION['username'];
		}
		
		// Could be an exit, a player, an object 
		$entity = getEntity( $name ); 
		if( $entity ) {
			addLogToXML($entity['name']."<br>".$entity['description']);
		}
	}
	
}

/**
 * Drop object in action request.
 * 
 * @param string $actionRequest User requestion, starting with drop.
 * @return void
 */
function userActionDrop( $actionRequest ) {
	$itemName = substr( $actionRequest, 5 );
	addServerMessageToXML("Checking out '$itemName'...");
	// find entity in inventory
	$item = getEntity( $itemName, $_SESSION['userID'] );
	
	if( $item ) {
		if( dropEntity ( $item ) ) {
			userActionFullUpdate($_SESSION['location'], true); // true means silent
		} else {
			addLogToXML("Failed to drop.");
		}
	}
	return;
}

/**
 * Examine object in action request.
 * 
 * @param unknown $actionRequest User request starting with examine.
 */
function userActionExamine( $actionRequest ) {
	$actionKeyword = strtok( $actionRequest, " " );
	$name = trim( strtok('') );
	
	$locations[]=$_SESSION['location'];
	$locations[]=$_SESSION['userID'];
	
	if( $name == "here" ) {
		$name = "#".$_SESSION['location'];
	}
	
	$entity = getEntity( $name );
	
	if( $entity ) {
		$response = $entity['name'] . " (id #" . $entity['id'] . ")";
		foreach( $entity as $name=>$value ) {
			if( $name != "id" && $name != "name" ) {
				$response .= "<br>&nbsp;&nbsp;";
				if( empty($value) ) {
					$response.=$name;
				} else {
					$response.=$name." is ".$value;
				}
			}
		}
		addLogToXML($response);
	}

}


/**
 * Moves user to location in action request.
 * 
 * @param $actionRequest User's request, starting with go or move.
 *
 * @return true if exit is found, exit is not linked, multiple exits
 *   found, or no exit found.
 * @return false if actionRequest didn't not include the move command
 *   and no exit match was found. (I.e., user didn't type in a command
 *   and we were trying to see if they just typed in an exit name.
 */
function userActionMove( $actionRequest ) {
	
	// Find the location of all possible commands issued
	$pos["go to the"] = strpos( $actionRequest, "go to the ");
	$pos["go to"] = strpos( $actionRequest, "go to ");
	$pos["goto"] = strpos( $actionRequest, "goto ");
	$pos["go"] = strpos( $actionRequest, "go ");
	$pos["move"] = strpos( $actionRequest, "move ");
	
	// Figure out which one it was, and record start position
	if( $pos["go to"] == 0 && $pos["go to"] !== false ) {
		$itemStart = 6;
	} else if ( $pos["go to the"] == 0 && $pos["go to the"] !== false ) {
		$itemStart = 5;
	} else if ( $pos["goto"] == 0 && $pos["goto"] !== false ) {
		$itemStart = 5;
	} else if ( $pos["go"] == 0 && $pos["go"] !== false ) {
		$itemStart = 3;
	} else if ( $pos["move"] == 0 && $pos["move"] !== false ) {
		$itemStart = 5;
	} else {
		// No keyword, ie. user typed "north"
		$itemStart = 0;
		addServerMessageToXML("----userActionMove function with no move keyword");
	}
	
	// Find the exit
	$exitName = substr( $actionRequest, $itemStart );
	$result = $exit = getEntity( $exitName, $_SESSION['location'], "exit", true);
	
	// Try to go through the exit
	if( $exit ) {
			// TODO: If user passes lock
			$user['id'] = $_SESSION['userID'];
			$user['type'] = "user";
			$user['name'] = $_SESSION['username'];
			if( moveUser( $exit ) ) {
				userActionFullUpdate();
			}
			// If user fails lock
				// addLogToXML( $exit['fail'] );

	}
	return $result;
}

/**
 * Say text in user request.
 * 
 * @param string $actionRequest The user request, starting with " or say.
 * @return void
 */
function userActionSay( $actionRequest ) {
	$actionKeyword = strtok( $actionRequest, " " );
	$message = trim( strtok('') );
	
	if( insertLog("user", $_SESSION['userID'], $_SESSION['location'], $_SESSION['username'] ." says \"<span class='speech'>".$message."</span>\"") ) {
		addLogToXML("Said.");
	} else {
		addLogToXML("Said failed.");
	}
	return;
}
	
/**
 * Take object in user request in to inventory.
 * 
 * @param string $actionRequest User action request, starting with take.
 * @return void
 */
function userActionTake( $actionRequest ) {
	$itemName = substr( $actionRequest, 5 );
	
	// find entity
	$item = getEntity( $itemName, $_SESSION['location'] );
	
	if( $item ) {
		// move entity to inventory
		if( takeEntity( $item ) ) {
			//addLogToXML( "Took {$item[name]}.");
			userActionFullUpdate($_SESSION['location'], true); // true means silent
		} else {
			addLogToXML("Failed to take.");
		}
	} /* else if ( $item && $item ->num_rows > 1 ) {
		addLogToXML("Which one do you mean? ".listOfEntities( $item ) );
	} else {
		addLogToXML("You don't see that here.");
	} */
	return;
}

/**
 * Determine if the actionKeyword is a user action, and if so execute it.
 * 
 * @param unknown $actionKeyword
 * @param unknown $actionRequest
 * @return boolean Was a user action attempted.
 */
function userAction( $actionKeyword, $actionRequest ) {
	addServerMessageToXML("ActionKeyword: $actionKeyword.");
	$result = true;
	if( $actionKeyword == "look") {
		userActionLook( $actionRequest );
	} else if( $actionKeyword == "say" || $actionKeyword == "\\\"" ) {
		userActionSay( $actionRequest );
	} else if( $actionKeyword == "go" || $actionKeyword == "goto" ) {
		userActionMove( $actionRequest);
	} else if( $actionKeyword == "take" ) {
		userActionTake( $actionRequest );
	} else if( $actionKeyword == "drop" ) {
		userActionDrop( $actionRequest );
	} else if( $actionKeyword == "full-update" ) {
		userActionFullUpdate( $_SESSION['location'] );
	} else if( $actionKeyword == "examine" ) {
		userActionExamine( $actionRequest );
	} else if( substr( $actionRequest, 0, 1) == "@" ) {
		userBuildActions( $actionRequest );
	} else if( $actionKeyword == "QUIT" || $actionKeyword == "SIGNOUT" || $actionKeyword == "LOGOUT" ) {
		// Needs to be made case sensitive
		userAccountLogout();
	} else {
		$result = false;
	}
	return $result;
}

?>