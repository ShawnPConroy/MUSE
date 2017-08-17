<?

/*
 * Used by look (and other?) to list all objects in this room. 
 * Need to update getEntities
 */

function getEntitiesByLocation( $location, $type = null ) {
	global $wb; // App settings & database
	
	$sql = "SELECT * FROM {$wb['DB_PREFIX']}_entities WHERE location = {$location};";
	$entities = $wb['db']->query($sql);
	addServerMessageToXML($sql." Number of rows:". $entities->num_rows);
	
	return $entities;
}

/*
 * Returns the exit with the matching name in that location
 */
/*
function getExitByName( $exitName, $exitLocation ) {
	global $wb; // App settings & database
	
	$exit = $wb['db']->query("SELECT * 
		FROM  `{$wb['DB_PREFIX']}_entities` AS en
		LEFT JOIN  `{$wb['DB_PREFIX']}_exits` AS ex ON en.id = ex.entity_id
		WHERE en.location =  '$exitLocation'
		AND en.name =  '$exitName'
		AND type = 'exit'");
	addServerMessageToXML("SELECT * 
		FROM  `{$wb['DB_PREFIX']}_entities` AS en
		LEFT JOIN  `{$wb['DB_PREFIX']}_exits` AS ex ON en.id = ex.entity_id
		WHERE en.location =  '$exitLocation'
		AND en.name =  '$exitName'
		AND type = 'exit'");
	return $exit->fetch_assoc();
}
*/

/*
 * Returns current location fields
 */

function getLocation( $locationId ) {
	global $wb; // App settings & database
	
	$location = $wb['db']->query("SELECT * FROM {$wb['DB_PREFIX']}_entities WHERE id = '$locationId';");
	$location = $location->fetch_assoc();
	return $location;
}


/*
 * Get the object in this room by the given name.
 *
 * Returns object fields or false.
 */
 
function getLocationEntityByName( $name, $location ) {
	global $wb; // App settings & database
	
	addServerMessageToXML("--Searching all objects named $name!".
	"SELECT * FROM {$wb['DB_PREFIX']}_entities
		WHERE name = '{$name}' AND location = '{$location}';");
	$object = $wb['db']->query("SELECT * FROM {$wb['DB_PREFIX']}_entities
		WHERE name = '{$name}' AND location = '{$location}';");
	if( $object->num_rows ) {
		addServerMessageToXML("----Found something!");
		return $object->fetch_assoc();
	} else {
		addServerMessageToXML("----No entity...");
		return false;
	}
}


/*
 * Gets an entity in locations[] by name. Returns the first hit.
 * Maybe make getEntity that constructs it depending on having a name at all
 * (to get all items in $location). It should just take an array and use
 * the $value and $key in a foreach to construct the SQL.
 */
/*
function getEntityByName( $name, $locations, $type = null ) {
	global $wb; // App settings & database
	
	if  ( !is_array( $locations ) && !is_null( $locations ) ) {
		// Only a single location
		$locationSQL = "AND location = '{$locations}'";
	} else if( !is_null( $locations ) ) {
		// array of locations
		foreach( $locations as $location ) {
			if( empty( $locationString ) ) {
				$locationString= $location;
			} else {
				$locationString.= ", $location";
			}
		}
		$locationSQL = "AND location IN ({$locationString})";
	} else {
		// Is null
		$locationSQL = null;
	}
	
	if( !is_null($type) ) {
		$typeSQL = "AND type = '{$type}'";
		
		if( $type == "exit" || $type=="log" || $type=="user" || $type=="room" ) {
			$typePlural = $type."s";
			$joinSQL = "LEFT JOIN {$wb['DB_PREFIX']}_{$typePlural} AS j ON e.id = j.entity_id";
		} else {
			$joinSQL = "";
		}
		
	} else {
		$typeSQL = null;
	}
	
	$sql = "SELECT * FROM {$wb['DB_PREFIX']}_entities AS e {$joinSQL}
			WHERE name = '{$name}' $typeSQL $locationSQL;";
	addServerMessageToXML( $sql );
	
	$result = $wb['db']->query($sql);
	
	if( $result && $result->num_rows == 0 ) {
		// If no matches, do a wider search
		$sql = "SELECT * FROM {$wb['DB_PREFIX']}_entities AS e {$joinSQL}
				WHERE name LIKE '{$name}%' $typeSQL $locationSQL;";
		addServerMessageToXML( $sql );
	
		$result = $wb['db']->query($sql);
	}
	
	return $result;

}
*/
?>