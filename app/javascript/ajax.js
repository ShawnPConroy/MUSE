var xmlhttp;
var waitingForResponse = false;
var waitingAction = null;

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
				serverDebug("Incomprehensible server messages: " + messages[messageCounter].firstChild.nodeValue);
			}
		}
	} else {
		debug("No server messages to process");
	}
	
	return;
}

function processResponseUsers( response ) {
	
	var newUsers = response.documentElement.getElementsByTagName("user");
	
	if(newUsers) {
		// Get the objects list
		var userList= document.getElementById('users');

		// Add new objects
		debug("Number of users: " + newUsers.length);
	
		for( i=0; i<newUsers.length; i++ ) {
			// Add to list, first child is always name
			debug("Adding user: " + newUsers[i].firstChild.nodeValue);
			var newUser = document.createElement('li');
			newUser.innerHTML = newUsers[i].firstChild.nodeValue
			userList.insertBefore( newUser, userList.firstChild );
		}
	} else {
		debug("No players to process");
	}
	
	return;
}

function processResponseObjects( response ) {
	
	var newObjects = response.documentElement.getElementsByTagName("object");

	if (newObjects != null) {
		// Get the objects list
		var objectList = document.getElementById('objects');
		
		// Add new objects
		debug("Number of objects: " + newObjects.length);
		
		for( i=0; i<newObjects.length; i++ ) {
			// Add to list, first child is always name
			debug("Adding object: " + newObjects[i].firstChild.nodeValue);
			var newObject = document.createElement('li');
			newObject.innerHTML = newObjects[i].firstChild.nodeValue
			objectList.insertBefore( newObject, objectList.firstChild );
		}
	} else {
		debug("No objects to process");
	}
	return;
}

function processResponseExits( response ) {
	
	var newExits = response.documentElement.getElementsByTagName("exit");
	
	if( newExits ) {

		// Add new objects
		debug("Number of exits: " + newExits.length);
		// Get the objects list
		var exitList = document.getElementById('exits');
		
		for( i=0; i<newExits.length; i++ ) {
			// Add to list, first child is always name
			debug("Adding exit: " + newExits[i].firstChild.nodeValue);
			var newExit = document.createElement('li');
			newExit.innerHTML = newExits[i].firstChild.nodeValue;
			exitList.insertBefore( newExit, exitList.firstChild );
		}
	} else {
		debug("No exit to process");
	}
	
	return;
}

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

function processResponse( response ) {
	processResponseServerMessages( response );
	//processResponseUsers( response );
	//processResponseObjects( response );
	//processResponseExits( response );
	processResponseEntities( response );
	processResponseLocation( response );
	processResponseLogs( response);
	return;
}

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