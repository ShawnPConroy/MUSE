<?php
/**
 * Functions for creating the response XML to send to the client.
 *
 * This file is part of MUSE.
 *
 * @author	Shawn P. Conroy
 */


/**
 * Start the XML response object.
 */
function responseStart() {
	global $muse; // App settings & database
	// Prepare AJAX XML response, with root node
	header("Content-Type: text/xml");

	$muse['xml'] = new DOMDocument();
	$muse['xml']->formatOutput = true;
	$muse['response'] = $muse['xml']->createElement( "response" );
	$muse['xml']->appendChild( $muse['response'] );
}

/**
 * Display the XML response object to client.
 */
function responseFinish() {
	global $muse; // App settings & database
	echo $muse['xml']->saveXML(); // Sends response
}

/**
 * Adds the world entities in $location to the XML response to client.
 * @param string $location The location ID to get object from.
 * @param string $tag The XML node type.
 * @return void
 */
function addLocationEntitiesToResponse( $location, $tag = null ) {
	global $muse; // App settings & database
	
	$entities = $muse['db']->query("SELECT * FROM {$muse['DB_PREFIX']}_entities WHERE location = {$location};");
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
 * Adds location name element to XML response to client.
 * @param string $location Location name.
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
	global $muse; // App settings & database
	
	$element = $muse['xml']->createElement( $object );
	$element->appendChild( $muse['xml']->createTextNode( $text ) );
	$muse['response']->appendChild( $element );
}


/**
 * Append message as narrative to XML response
 * 
 * @param unknown $message
 */
function addNarrativeToXML($message) {
	addTextElement( "narrative", $message);
}

/**
 * Add debug text to XML response.
 * 
 * @param unknown $message
 */
function addServerMessageToXML( $message ) {
	addTextElement( "serverMessage", $message );
}


?>