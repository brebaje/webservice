<?php

require_once('google_places.php');

if(count($_GET) > 0) {
	if(isset($_GET['requesttype'])) {
		// instantiate web service class
		$key = 'replaceMeWithAPIkey';
		$webservice = new googlePlaces($key);

		// set common params to all requests
		if(isset($_GET['output']))
			$webservice->setOutput($_GET['output']);
		if(isset($_GET['sensor']))
			$webservice->setSensor($_GET['sensor']);
		if(isset($_GET['location']))
			$webservice->setLocation($_GET['location']);
		if(isset($_GET['language']))
			$webservice->setLanguage($_GET['language']);
		if(isset($_GET['radius']))
			$webservice->setRadius($_GET['radius']);
		if(isset($_GET['types']))
			$webservice->setTypes($_GET['types']);

		// set common params to nearbysearch, textsearch & radarsearch
		if(!($_GET['requesttype'] == googlePlaces::AUTOCOMPLETE)) {
			if(isset($_GET['minprice']))
				$webservice->setMinprice($_GET['minprice']);
			if(isset($_GET['maxprice']))
				$webservice->setMaxprice($_GET['maxprice']);
			if(isset($_GET['opennow']))
				$webservice->setOpennow($_GET['opennow']);

			// set common params to nearbysearch & radarsearch
			if(!($_GET['requesttype'] == googlePlaces::TEXT_SEARCH)) {
				if(isset($_GET['keyword']))
					$webservice->setKeyword($_GET['keyword']);
				if(isset($_GET['name']))
					$webservice->setName($_GET['name']);
			}
		}

		// set specific params to each request and perform the webservice request
		switch ($_GET['requesttype']) {
			case googlePlaces::NEARBY_SEARCH:
				if(isset($_GET['rankby']))
					$webservice->setRankby($_GET['rankby']);
				if(isset($_GET['pagetoken']))
					$webservice->setPagetoken($_GET['pagetoken']);

				$webservice->nearbysearch();
				break;
			case googlePlaces::TEXT_SEARCH:
				if(isset($_GET['query']))
					$webservice->setQuery($_GET['query']);

				$webservice->textsearch();
				break;
			case googlePlaces::RADAR_SEARCH:
				$webservice->radar_search();
				break;
			case googlePlaces::AUTOCOMPLETE:
				if(isset($_GET['query']))
					$webservice->setQuery($_GET['query']);
				if(isset($_GET['offset']))
					$webservice->setOffset($_GET['offset']);
				if(isset($_GET['components']))
					$webservice->setComponents($_GET['components']);

				$webservice->autocomplete();
				break;
			default:
				$response[] = 'Wrong request type submitted.';
				echo json_encode($response);
		}

		// check for errors and return errors or data retrieved
		if(empty($webservice->_errors))
			echo $webservice->_response;
		else
			echo json_encode($webservice->_errors);
	}
	else {
		$response[] = 'Request type is missing.';
		echo json_encode($response);
	}
}
else {
	$response[] = 'No parameters passed to the webservice.';
	echo json_encode($response);
}
