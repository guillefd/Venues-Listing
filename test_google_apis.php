<?php
set_time_limit(600);
// this is the IP address that www.bata.com.sg resolves to
$server = '194.228.50.32';
$host   = 'www.bata.com.sg';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $server);

/* set the user agent - might help, doesn't hurt */
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);


/* try to follow redirects */
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

/* timeout after the specified number of seconds. assuming that this script runs
on a server, 20 seconds should be plenty of time to verify a valid URL.  */
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
curl_setopt($ch, CURLOPT_TIMEOUT, 20);


$headers = array();
$headers[] = "Host: $host";

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

curl_setopt($ch, CURLOPT_VERBOSE, true);

/* don't download the page, just the header (much faster in this case) */
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_HEADER, true);

$response = curl_exec($ch);
curl_close($ch);

echo '<h2>Testing: Bata.com.sq API: <br><small>[for testing porpose, so you can see that this API works]</small></h2>';
echo '<pre> RESPONSE OK: ';
var_dump($response);
echo '</pre><br>';

echo '<h2>Testing: Google API discovery URL: (https://www.googleapis.com/discovery/v1/apis) <br>
      <small>[this works directly in the browser too - <a href="https://www.googleapis.com/discovery/v1/apis" target="_blank">try here</a>]';
try 
{
	$get = file_get_contents('https://www.googleapis.com/discovery/v1/apis');
} 
catch (Exception $e) 
	{
		echo '<pre> CONNECTION TIMEOUT - ERROR:';
		var_dump($e);	
		echo '</pre>';
	}
echo '<pre> RESPONSE OK ';
var_dump($get);	
echo '</pre>';	

?>