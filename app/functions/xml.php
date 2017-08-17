<?php

function addLocationEntitiesToXML( $location, $tag = null ) {
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


/* Adds a text node to response */
function addTextElement( $object, $text ) {
	global $wb; // App settings & database
	
	$element = $wb['xml']->createElement( $object );
	$element->appendChild( $wb['xml']->createTextNode( $text ) );
	$wb['response']->appendChild( $element );
}

function addLocationToXML( $location ) {
	$location = getLocation( $location );
	addTextElement( "location", $location['name'] );
	return;
}



?>