<?
	$dir = dirname(__FILE__);

	include($dir.'/testmore.php');

	$cfg = array(
		'http_timeout' => 5,
	);

	include($dir.'/lib_http.php');
	include($dir.'/lib_json.php');
	include($dir.'/../lib_wowhead.php');
