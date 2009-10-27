<?php

class longURL {
  
  function __construct() {
//  set the user agent here. To be used in the curl_setopt() fuctions
  }

  static function expand($shorturl, $options = array()) {
    // all-redirects
    // content-type
    // response-code
    // title
    // rel-canonical 
    // meta-keywords
    // meta-description
    // format
    $format = empty($options['format']) ? 'php' : $options['format'];
    unset($options['format']);
    $queries = array(
      'url' => $shorturl,
      'format' => $format,
    );
    $queries = array_merge($queries, $options);
    $query = http_build_query($queries);
	  $longurl_api =  "http://api.longurl.org/v2/expand?" . $query;
  	$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $longurl_api);
  	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  	curl_exec($ch);
		$longurl = new StdClass();
	  $longurl->headers = curl_getinfo($ch);
	  $longurl->content = unserialize(curl_multi_getcontent($ch));
	  $longurl->longurl = $longurl->content['long-url'];
   return $longurl;
  }
}