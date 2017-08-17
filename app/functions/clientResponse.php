<?php
/**
 * Functions for creating the response XML to send to the client.
 */


/**
 * Start the XML response object.
 */
function responseStart() {
	global $wb; // App settings & database
	// Prepare AJAX XML response, with root node
	header("Content-Type: text/xml");

	$wb['xml'] = new DOMDocument();
	$wb['xml']->formatOutput = true;
	$wb['response'] = $wb['xml']->createElement( "response" );
	$wb['xml']->appendChild( $wb['response'] );
}

/**
 * Display the XML response object to client.
 */
function responseFinish() {
	global $wb; // App settings & database
	echo $wb['xml']->saveXML(); // Sends response
}

/**
 * Adds the world entities in $location to the XML response to client.
 * @param int $location the location ID to get object from
 * @param string $tag the XML node type
 */
function addLocationEntitiesToResponse( $location, $tag = null ) {
	global $wb; // App settings & database
	
	
	$entities = $wb['db']->query("SELECT * FROM {$wb['DB_PREFIX']}_entities WHERE location = {$location};");
	while ( $entities && $entity = $entities->fetch_assoc() ) {
		$entity['name'] = strtok($entity['name'], ';');
		if( $tag != null ) {
			$nodeTag = $tag;
		} else {
			$nodeTag = $entity['type'];
		}
		getExtendedData( $entity );
		if( !isset($entity['dark']) && $entity['id'] != $_SESSION['userID'] ) {
			addTextElement( $nodeTag, $entity['name'] );
		}
	}
	return;
}

/**
 * Add location to XML response to client.
 * @param unknown $location
 */
function addLocationToResponse( $location ) {
	$location = getLocation( $location );
	addTextElement( "location", $location['name'] );
	return;
}

/**
 * Adds an XML text node to response
 * @param string $object type of node to create
 * @param String $text data in node
 */
function addTextElement( $object, $text ) {
	global $wb; // App settings & database
	
	$element = $wb['xml']->createElement( $object );
	$element->appendChild( $wb['xml']->createTextNode( $text ) );
	$wb['response']->appendChild( $element );
}


/**
 * Append message as log to XML response
 * 
 * @param unknown $message
 */
function addLogToXML ($message) {
	addTextElement( "log", $message);
}

/**
 * Debug text to XML response.
 * 
 * @param unknown $message
 */
function addServerMessageToXML( $message ) {
	addTextElement( "serverMessage", $message );
}


?>