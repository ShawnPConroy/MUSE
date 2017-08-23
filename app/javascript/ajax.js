/*
 * Functions that make requests and process responses.
 *
 * This file is part of MUSE.
 *
 * @author	Shawn P. Conroy
 */
 
var xmlhttp;
var waitingForResponse = false;
var waitingAction = null;

/**
 * Creates an XML HTTP request with the user action.
 * @param {string} url Where to send the request.
 * @param {string} action The user's action request.
 * @param {requestCallBack} cfunc Return event processing function.
 */
function sendAction(url,action,cfunc)
{
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} else {
		// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=cfunc;
	xmlhttp.open("POST",url,true);

	xmlhttp.send(action);
}

/**
 * Processes server messages. Pulls server messages from the response.
 * Performs actions from the server, such as clearing the helper lists.
 * Displays any debug messages to the log / story, if debugging is on.
 * @param {string} response The full response from the server.
 */
function processResponseServerMessages( response ) {
	debug("Processing server responeses...");
	var messages = response.documentElement.getElementsByTagName("serverMessage");
	
	if(messages) {

		// Add new objects
		debug("Number of server messages: " + messages.length);
		for( messageCounter=0; messageCounter<messages.length; messageCounter++ ) {
			if ( messages[messageCounter].firstChild.nodeValue == "clear" ) {
				uiClearLists();
			} else {
				serverDebug("<strong>Server message</strong>: " + messages[messageCounter].firstChild.nodeValue);
			}
		}
	} else {
		debug("No server messages to process");
	}
	
	return;
}

/**
 * Grabs the location and updates the helper list header with current location.
 * @param {array} Locations XML response from server.
 * @return void
 */
function processResponseLocation( locations ) {
	
	if (locations && locations.length) {
		// Add new objects
		debug("Number of locations: " + locations.length);
		var locationP = document.getElementById('location');
		
		// Add to list, first child is always name
		debug("Adding location: " + locations[0].firstChild.nodeValue);
		locationP.innerHTML = locations[0].firstChild.nodeValue;
	} else {
		debug("No locations to process");
	}
	return;
}

/**
 * Add items to specified helper list.
 * This really seems like it's UI stuff. Maybe helper lists should be in their on JS file.
 * @param {string} listID The HTML ID of the list to add items to.
 * @param {xml} XML listing of one type to add to specified list.
 */
function processResponseAddToList( listID, items) {
	debug("Adding to list: "+listID);

	
	if( items ) {

		// Get the objects list
		var itemList = document.getElementById(listID);
		
		for( i=0; i<items.length; i++ ) {
			// Add to list, first child is always name
			var newItem = document.createElement('li');
			newItem.innerHTML = items[i].firstChild.nodeValue;
			itemList.insertBefore( newItem, itemList.firstChild );
		}
	} else {
		debug("--No "+listID+" to process");
	}
	
	return;
}

/**
 * Processes users, objects, inventory and exist, also location name,
 * for the helper sidebar.
 * @param {xml} response The XML Response from the server.
 */
function processResponseEntities( response ) {
	var users = response.documentElement.getElementsByTagName("user");
	var objects = response.documentElement.getElementsByTagName("object");
	var inventory = response.documentElement.getElementsByTagName("inventory");
	var exits = response.documentElement.getElementsByTagName("exit");
	var locations = response.documentElement.getElementsByTagName("location");
	
	processResponseAddToList( "users", users );
	processResponseAddToList( "objects", objects );
	processResponseAddToList( "inventory", inventory );
	processResponseAddToList( "exits", exits );
	
	processResponseLocation( locations ); // This shouldn't be in this function, move back 1 level 
}

/**
 * Adds standard game responses/logs to the log box (story box).
 * @param {xml} response XML response from server
 */
function processResponseLogs( response ) {
	
	
	var newLogs = response.documentElement.getElementsByTagName("log");
	
	if (newLogs) {
			
		// Add new objects
		debug("Number of logs: " + newLogs.length);
		
		for( i=0; i<newLogs.length; i++ ) {
			// Add to list, first child is always name
			debug("Adding log: " + newLogs[i].firstChild.nodeValue);
			addToLog( newLogs[i].firstChild.nodeValue );
		}
	} else {
		debug("No logs to process");
	}
	
	return;
}

/**
 * Hand off the response to every function that processes it for info.
 * @param {response} Server's response.
 */
function processResponse( response ) {
	processResponseServerMessages( response );
	processResponseEntities( response );
	processResponseLocation( response );
	processResponseLogs( response);
	return;
}

/**
 * Function to handle a user action request. It displays the 
 * requestion to the log / story box, sends it to the server
 * and prepares for next action.
 *
 * @param {string} actionString The user's command/request.
 */
function action( actionString ) {
	if( !waitingForResponse ) {
		userAction( actionString );
		silentAction( actionString );
		uiFocusCursor(); // sets cursor to input box
	} else {
		debug("Can't send!");
		waitingAction = actionString;
	}
		
}

/**
 * Quietly sense a command to the server and processes the response.
 * Wait, will this properly update the helper list for new items dropped
 * in location?
 *
 * @param {string} actionString The command/request.
 */
function silentAction( actionString ) {
	waitingForResponse = true;
	debug("AJAX TRUE");
	sendAction("request.php",actionString,function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			if (xmlhttp.responseXML == null ) debug("Got null XML response.");
			debug( "Got this text response: " + xmlhttp.responseText );
			processResponse(xmlhttp.responseXML);
			waitingForResponse=false;
			if( waitingAction ) {
				temp = waitingAction;
				waitingAction = null;
				action( temp );
			}
		}
	});

}