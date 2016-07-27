<?php

include_once('webservice_interface.php');

class googlePlaces implements webservice {

	const NEARBY_SEARCH  = 'nearbysearch';
	const TEXT_SEARCH = 'textsearch';
	const RADAR_SEARCH = 'radarsearch';
	const AUTOCOMPLETE = 'autocomplete';

	public $_errors = array();
	public $_response = '';

	protected $_requesttype = '';			// type of request
	protected $_output = 'json';
	protected $_url = 'https://maps.googleapis.com/maps/api/place';

	// Required parameters
	protected $_query;					// required for textsearch & autocomplete
	protected $_key = '';				// Google Places API key
	protected $_sensor = 'false';
	protected $_radius = 50000;			// required for nearbysearch & radarsearch
	protected $_location;				// required for nearbysearch & radarsearch

	// Optional parameters
	protected $_rankby = 'prominence';
	protected $_keyword;
	protected $_language = 'en';
	protected $_minprice;
	protected $_maxprice;
	protected $_opennow;
	protected $_types;
	protected $_name;
	protected $_pagetoken;
	// protected $_zagatselected;		// API enterprise customers only

	// Optional parameters (autocomplete only)
	protected $_offset;
	protected $_components;


	public function __construct($key) {
		$this->_key = $key;
	}

	public function nearbysearch() {
		$this->_requesttype = self::NEARBY_SEARCH;

		$this->_response = $this->_api_call();
	}

	public function textsearch() {
		$this->_requesttype = self::TEXT_SEARCH;

		$this->_response = $this->_api_call();
	}

	public function radarsearch() {
		$this->_requesttype = self::RADAR_SEARCH;

		$this->_response = $this->_api_call();
	}

	public function autocomplete() {
		$this->_requesttype = self::AUTOCOMPLETE;

		$this->_response = $this->_api_call();
	}

	/*
	 * _api_call : performs the request against Google Places API
	 *
	 * returns raw json/xml data received from the API
	 */
	protected function _api_call() {
		$this->_check_params();

		if(empty($this->_errors)) {
			$params = $this->_get_params();

			$url = $this->_url . '/' . $this->_requesttype . '/' . $this->_output . '?key='.$this->_key . '&' . $params;

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, FALSE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

			$data = curl_exec($ch);

			// check if any error occurred
			if(curl_errno($ch))
    			$this->_errors[] = 'Error trying to request data: ' . curl_error($ch);

			curl_close($ch);

			return $data;
		}
	}

	/*
	 * _check_params : checks for required params in the request
	 */
	protected function _check_params() {
		if(empty($this->_key))
			$this->_errors[] = 'Google Places API key is missing.';

		if(empty($this->_requesttype))
			$this->_errors[] = 'Request type is missing.';

		if($this->_requesttype == self::NEARBY_SEARCH) {
			if(!isset($this->_radius) && $this->_rankby == 'prominence')
				$this->_errors[] = "Radius paramater is missing for this {$this->_requesttype} request.";

			if($this->_rankby == 'distance') {
				if(!(isset($this->_keyword) || isset($this->_name) || isset($this->_types)))
					$this->_errors[] = "Either one of keyword, name or types parameters is missing for this {$this->_requesttype} request.";
			}
		}

		if($this->_requesttype == self::TEXT_SEARCH || $this->_requesttype == self::AUTOCOMPLETE) {
			if(!isset($this->_query) || $this->_query == '')
				$this->_errors[] = "Query/input parameter is missing for this {$this->_requesttype} request.";
		}

		if($this->_requesttype == self::RADAR_SEARCH) {
			if(!isset($this->_radius))
				$this->_errors[] = "Radius paramater is missing for this {$this->_requesttype} request.";
		}

		if($this->_requesttype == self::NEARBY_SEARCH || $this->_requesttype == self::RADAR_SEARCH) {
			if(!isset($this->_location))
				$this->_errors[] = "Location parameter is missing for this {$this->_requesttype} request.";
		}

	}

	/*
	 * _get_params : formats the url parameters depending on the request type
	 *
	 * returns the parameters for the request
	 */
	protected function _get_params() {
		$params = array();

		// common params to all requests
		$params[] = 'sensor=' . $this->_sensor;
		$params[] = 'language=' . $this->_language;

		if($this->_rankby == 'prominence') 						// nearbysearch check 
			$params[] = 'radius=' . $this->_radius;

		if(isset($this->_location))
			$params[] = 'location=' . $this->_location;

		if(isset($this->_types))
			$params[] = 'types=' . $this->_types;

		// specific params to each request
		switch ($this->_requesttype) {
			case self::NEARBY_SEARCH:
				if(isset($this->_pagetoken))
					$params[] = 'pagetoken=' . $this->_pagetoken;
				$params[] = 'rankby=' . $this->_rankby;
				break;
			case self::TEXT_SEARCH:
				if(isset($this->_query))
					$params[] = 'query=' . $this->_query;
				break;
			case self::AUTOCOMPLETE:
				if(isset($this->_query))
					$params[] = 'input=' . $this->_query;
				if(isset($this->_offset))
					$params[] = 'offset=' . $this->_offset;
				if(isset($this->_components))
					$params[] = 'components=' . $this->_components;
				break;
		}

		// common params to nearbysearch, textsearch & radarsearch
		if(!($this->_requesttype == self::AUTOCOMPLETE)) {
			if(isset($this->_minprice))
				$params[] = 'minprice=' . $this->_minprice;

			if(isset($this->_maxprice))
				$params[] = 'maxprice=' . $this->_maxprice;

			if(isset($this->_opennow))
				$params[] = 'opennow=' . $this->_opennow;

			// common params to nearbysearch & radarsearch
			if(!($this->_requesttype == self::TEXT_SEARCH)) {
				if(isset($this->_keyword))
					$params[] = 'keyword=' . $this->_keyword;

				if(isset($this->_name))
					$params[] = 'name=' . $this->_name;
			}
		}


		$params = implode('&', $params);
		return $params;
	}


	/*
	 * setters for the class
	 */
	public function setOutput($output) {
		$output = strtolower($output);

		if ($output == 'json' || $output == 'xml')
			$this->_output = $output;
	}

	public function setQuery($query) {
		$this->_query = preg_replace('/\s/', '+', $query);
	}

	public function setSensor($sensor) {
		$sensor = strtolower($sensor);

		if($sensor == 'true' || $sensor == 'false')
			$this->_sensor = $sensor;
	}

	public function setRadius($radius) {
		if($radius >= 0 && $radius <= 50000)
			$this->_radius = $radius;
	}

	public function setKeyword($keyword) {
		$this->_keyword = $keyword;
	}

	public function setLocation($location) {
		$this->_location = $location;
	}

	public function setLanguage($language) {
		$this->_language = $language;
	}

	public function setMinprice($minprice) {
		$this->_minprice = $minprice;
	}

	public function setMaxprice($maxprice) {
		$this->_maxprice = $maxprice;
	}

	public function setOpennow($opennow) {
		$this->_opennow = $opennow;
	}

	public function setTypes($types) {
		$this->_types = $types;
	}

	public function setName($name) {
		$this->_name = $name;
	}

	public function setRankby($rankby) {
		$rankby = strtolower($rankby);

		if ($rankby == 'prominence' || $rankby == 'distance')
			$this->_rankby = $rankby;
	}

	public function setPagetoken($pagetoken) {
		$this->_pagetoken = $pagetoken;
	}

	public function setOffset($offset) {
		$this->_offset = $offset;
	}

	public function setComponents($components) {
		$this->_components = $components;
	}

}