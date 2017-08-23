<?
/* Process user account requestion. Login and Logout and Create.
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
function userAccountCreate( $username, $password ) {
	global $wb; // App settings & database
	
	// Create exit
	$result = $wb['db']->query("INSERT INTO {$wb['DB_PREFIX']}_entities
				(name, location, type) VALUES ( '$username', 0, 'user' );");
	
	$result = $wb['db']->query("INSERT INTO {$wb['DB_PREFIX']}_users
				(entity_id, password) values ( '".$wb['db']->insert_id."', MD5('$password'));");
	return( $result );
}

/**
 * Authenticate user. Set's system variables if successful.
 */
function userAccountLogin() {
	global $wb; // App settings & database
	
	$username = $wb['db']->real_escape_string( $_REQUEST['username'] );
	$password = $wb['db']->real_escape_string( $_REQUEST['password'] );
	
	$result = $wb['db']->query(
		"SELECT o.name, o.id, u.password, o.location
		FROM  `{$wb['DB_PREFIX']}_entities` AS o
		LEFT JOIN  `{$wb['DB_PREFIX']}_users` AS u ON o.id = u.entity_id
		WHERE o.type =  'user'
			AND o.name =  '$username'
			AND u.password = MD5(  '$password' ) 
		LIMIT 1"
		);
	
	if( $result && $result->num_rows ) {
		$user = $result->fetch_assoc();
		
		$_SESSION['loggedIn'] = true;
		$_SESSION['username'] = $user['name'];
		$_SESSION['userID'] = $user['id'];
		$_SESSION['location'] = $user['location'];
		$_SESSION['lastUpdate'] = date( 'Y-m-d H:i:s' );
	} else {
		$_SESSION['errorMessage'] = "Your username and password combination did not match any user we have. Please try again.";
		header("Location: " . $wb['APP_URI'] . "index.php");
	}
	return;
}

/**
 * Force user logout.
 */
function userAccountLogout() {
	session_destroy();
	addLogToXML("You have quit. Good bye.");
}
	


?>