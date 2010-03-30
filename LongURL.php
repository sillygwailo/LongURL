<?php

class longURL {
  
  function __construct() {
  // set the user agent here. To be used in the curl_setopt() fuctions
  }
  
  protected function longurl_api($url, $options = array()) {
    if (empty($options['format'])) {
      $options['format'] = 'php';
    }
    $options['user_agent'] = empty($options['user-agent']) ? "LongURL-PHP-Client-Library/0.1" : $options['user-agent'];

    // construct the query
    $queries = array();

    if (is_array($options)) $queries = array_merge($queries, $options);

    $query = http_build_query($queries);

    $url .= '?' . $query;

    $ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
  	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  	curl_setopt($ch, CURLOPT_USERAGENT, $options['user_agent']); 
  	curl_exec($ch);
  	$longurl_return = array();
  	$longurl_return['headers'] = curl_getinfo($ch);
  	$content = curl_multi_getcontent($ch);
  	if ($options['format'] == 'php') {
  	  $longurl_return['content'] = unserialize($content);
  	}
  	else { $longurl_return['content'] = $content; }
  	return $longurl_return;
  }

  public function expand($shorturl, $options = array()) {
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

    // construct the URL for the API call
	  $longurl_api_url =  "http://api.longurl.org/v2/expand";
	  $options['url'] = $shorturl;
		$longurl = new StdClass();
		$longurl_call = $this->longurl_api($longurl_api_url, $options);
	  $longurl->headers = $longurl_call['headers']; // can be used to debug, especially useful for HTTP codes, documented at http://longurl.org/api#error-responses
	  $longurl->content = $longurl_call['content'];
	  $longurl->longurl = $longurl->content['long-url'];
    return $longurl;
  }

  public function services($options = array()) {
		$longurl = new StdClass();
	  $longurl_api_url = "http://api.longurl.org/v2/services";
	  $longurl_call = $this->longurl_api($longurl_api_url, $options);
	  $longurl->headers = $longurl_call['headers'];
	  $longurl->content = $longurl_call['content'];
	  $longurl->services = array_keys($longurl_call['content']);
    return $longurl;
  }
}