/*
 * Functions control the interface.
 *
 * This file is part of MUSE.
 *
 * @author	Shawn P. Conroy
 */
 
var userActionList = new Array();
var userActionListHistoryCount = 0;
var debuging = false;
var serverDebuging = false;
var actionInputBox;
var updateTimer;

/**
 * Gets input element, requests player location info,
 * starts auto update timer set to every 3 seconds,
 * moves cursor to input box.
 */
function initialize () {
	actionInputBox = document.getElementById("actionInput");
	silentAction("full-update");
	updateTimer=setInterval(function(){
		if( !waitingForResponse ) {
			silentAction("update")
		}
		},3000);
		
	uiFocusCursor();
}

/**
 * Finds input box and gives it focus.
 */
function uiFocusCursor() {
	// Called on load and after a send action (just in case they clicked something)
	actionInputBox = document.getElementById("actionInput");
	actionInputBox.focus();
}

/**
 * Adds string to log (or 'story box' as it should be called).
 * @param {string} logString Text to add to log / story box.
 */
function addToLog( logString ) {
	logDiv = document.getElementById("log");
	logDiv.innerHTML += logString+"<br>";
	logDiv.scrollTop = logDiv.scrollHeight;
}

/**
 * Adds debugging to log (or 'story box' as it should be called).
 * @param {string} debugString Text to add to log / story box.
 */
function debug( debugString ) {
	if( debuging ) addToLog( "<font color=red>"+debugString+"</font>" );
}

/**
 * Adds debugging to log (or 'story box' as it should be called).
 * @param {string} debugString Text to add to log / story box.
 */
function serverDebug( debugString ) {
	if( serverDebuging ) addToLog( "<font color=orange>"+debugString+"</font>" );
}

/**
 * Process user action. Determine if it's a UI command. Pretty much just debugging stuff.
 * Displays actionString to user.
 * @param {string} actionString User input for processing.
 */
function userAction( actionString ) {
	if( actionString == "debugon" ) {
		debuging = true;
		addToLog( "<br><strong>"+actionString+"</strong>" );
		addToLog( "Debug on" );
	} else if( actionString == "debugoff" ) {
		debuging = false;
		addToLog( "<br><strong>"+actionString+"</strong>" );
		addToLog( "Debug off" );
	} else if( actionString == "serverdebugon" ) {
		serverDebuging = true;
		addToLog( "<br><strong>"+actionString+"</strong>" );
		addToLog( "Server debug on" );
	} else if( actionString == "serverdebugoff" ) {
		serverDebuging = false;
		addToLog( "<br><strong>"+actionString+"</strong>" );
		addToLog( "Server debug off" );
	} else if( actionString == "debugall" ) {
		serverDebuging = true;
		debuging = true;
		addToLog( "<br><strong>"+actionString+"</strong>" );
		addToLog( "All debugging on" );
	} else if( actionString == "debugnone" ) {
		serverDebuging = false;
		debuging = false;
		addToLog( "<br><strong>"+actionString+"</strong>" );
		addToLog( "All debugging off" );
	} else {
		addToLog( "<br><strong>"+actionString+"</strong>" );
		//userActionList.push( actionString );
		//userActionListHistoryCount += 1;
	}
	
}

/**
 * Clears one of the UI helper lists. (Helper function)
 * @param {string} listName The name of the list to clear.
 */
function uiClearList( listName ) {
	var list = document.getElementById( listName );
	var elements = list.getElementsByTagName("li");
	debug("--Clearing list: " + listName );
	for( i = elements.length-1; i >= 0; i-- ) {
		list.removeChild( elements[i] );
	}
}

/**
 * Clears all of the UI helper lists.
 */
function uiClearLists () {
	uiClearList("users");
	uiClearList("objects");
	uiClearList("inventory");
	uiClearList("exits");
}

/**
 * Incompletely. This processes hotkey support. Enter processes action.
 * I guess I wanted to use the up arrow to recall last action.
 * @param {event} e Key press event.
 */
function actionInput (e) {
	var key = e.which ? e.which : e.keyCode;
	if( key === 13 ) {
		// If user hit enter, send action
		action( actionInputBox.value);
		actionInputBox.value = "";
	} else if ( key === 38 ) {
		alert("up arrow");
	} else if ( key === 38 ) {
		alert("up arrow");
	}
	return;
}