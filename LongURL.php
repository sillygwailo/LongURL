<?php

class longURL {
  
  function __construct() {
  // set the user agent here. To be used in the curl_setopt() fuctions
  }

  static function expand($shorturl, $options = array()) {
    /* 
    $options is an array with the following possible keys. All are optional. Documentation from http://longurl.org/api#expand-url
       'all-redirects' => Set value to 1 to include all HTTP redirects in the response 
       'content-type' => Set value to 1 to include the internet media type of the destination URL in the response.
       'response-code' => Set value to 1 to include the HTTP response code of the destination URL in the response.
       'title' => Set value to 1 to include the HTML title of the destination URL in the response (if a web page).
       'rel-canonical ' => Set value to 1 to include the canonical URL of the destination URL in the response (if a web page).
       'meta-keywords' => Set value to 1 to include the meta keywords of the destination URL in the response (if a web page).
       'meta-description' => Set value to 1 to include the meta description of the destination URL in the response (if a web page). 
       'format' => Response format. Could be 'xml', 'json', or 'php'. Default in this library, unlike the web service, is 'php'.
       'callback' => Not supported in this library.
    */
    $format = empty($options['format']) ? 'php' : $options['format']; // if $format is not set in the options array, set it to 'php'
    unset($options['format']);
    
    // construct the query
    $queries = array(
      'url' => $shorturl,
      'format' => $format,
    );
    $queries = array_merge($queries, $options);
    $query = http_build_query($queries);
    
    // construct the URL for the API call
	  $longurl_api =  "http://api.longurl.org/v2/expand?" . $query;
  	$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $longurl_api);
  	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  	curl_exec($ch);
		$longurl = new StdClass();
	  $longurl->headers = curl_getinfo($ch); // can be used to debug, especially useful for HTTP codes, documented at http://longurl.org/api#error-responses
	  $longurl->content = unserialize(curl_multi_getcontent($ch));
	  $longurl->longurl = $longurl->content['long-url'];
   return $longurl;
  }
  
  static function services() {
    
  }
}