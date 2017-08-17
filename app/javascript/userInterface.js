var userActionList = new Array();
var userActionListHistoryCount = 0;
var debuging = false;
var serverDebuging = false;
var actionInputBox;
var updateTimer;

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

function uiFocusCursor() {
	// Called on load and after a send action (just in case they clicked something)
	actionInputBox = document.getElementById("actionInput");
	actionInputBox.focus();
}
function addToLog( logString ) {
	logDiv = document.getElementById("log");
	logDiv.innerHTML += logString+"<br>";
	logDiv.scrollTop = logDiv.scrollHeight;
}

function debug( debugString ) {
	if( debuging ) addToLog( "<font color=red>"+debugString+"</font>" );
}

function serverDebug( debugString ) {
	if( serverDebuging ) addToLog( "<font color=orange>"+debugString+"</font>" );
}

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


function uiClearList( listName ) {
	var list = document.getElementById( listName );
	var elements = list.getElementsByTagName("li");
	debug("--Clearing list: " + listName );
	for( i = elements.length-1; i >= 0; i-- ) {
		list.removeChild( elements[i] );
	}
}

function uiClearLists () {
	uiClearList("users");
	uiClearList("objects");
	uiClearList("inventory");
	uiClearList("exits");
}

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