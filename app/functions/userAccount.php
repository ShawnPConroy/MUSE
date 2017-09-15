<?
/* Process user account requestion. Sign in and Sign out and Create.
 *
 * This file is part of MUSE.
 *
 * @author	Shawn P. Conroy
 */
 
/**
 * Create user account.
 * 
 * @param unknown $username
 * @param unknown $password
 * @return boolean True if successful.
 * @return boolean False if unsuccessful.
 */
function userAccountSignUp( $username, $password ) {
	global $muse; // App settings & database
	
	// Create exit
	$result = $muse['db']->query("INSERT INTO {$muse['DB_PREFIX']}_entities
				(name, location, type) VALUES ( '$username', 0, 'user' );");
	
	$result = $muse['db']->query("INSERT INTO {$muse['DB_PREFIX']}_users
				(entity_id, password) values ( '".$muse['db']->insert_id."', MD5('$password'));");
	insertLog("system", $_SESSION['userID'], 0, "New user sign up: $username");
	return( $result );
}

/**
 * Authenticate user. Set's system variables if successful.
 */
function userAccountSignIn() {
	global $muse; // App settings & database
	
	$username = $muse['db']->real_escape_string( $_REQUEST['username'] );
	$password = $muse['db']->real_escape_string( $_REQUEST['password'] );
	
	$result = $muse['db']->query(
		"SELECT o.name, o.id, u.password, o.location
		FROM `{$muse['DB_PREFIX']}_entities` AS o
		LEFT JOIN `{$muse['DB_PREFIX']}_users` AS u ON o.id = u.entity_id
		WHERE o.type = 'user'
			AND o.name = '$username'
			AND u.password = MD5( '$password' ) 
		LIMIT 1"
		);
	
	if( $result && $result->num_rows ) {
		$user = $result->fetch_assoc();
		
		$_SESSION['signedIn'] = true;
		$_SESSION['username'] = $user['name'];
		$_SESSION['userID'] = $user['id'];
		$_SESSION['location'] = $user['location'];
		$_SESSION['lastUpdate'] = date( 'Y-m-d H:i:s' );
		insertLog("system", $_SESSION['userID'], 0, "User sign in: $_SESSION[username]");
	} else {
		$_SESSION['errorMessage'] = "Your username and password combination did not match any user we have. Please try again.";
		header("Location: " . $muse['APP_URI'] . "index.php");
	}
	return;
}

/**
 * Force user sign out.
 */
function userAccountSignOut() {
	session_destroy();
	addNarrativeToXML("You have quit. Good bye.");
}
	


?>