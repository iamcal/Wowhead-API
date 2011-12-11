<?
	#
	# this is a 'loose' JSON decoder. the built-in PHP JSON decoder
	# is very strict, and will not accept things which are fairly
	# common in the wild:
	#
	#  * unquoted keys, e.g. {foo: 1}
	#  * single-quoted strings, e.g. {"foo": 'bar'}
	#  * escaped single quoted, e.g. {"foo": "b\'ar"}
	#  * empty array elements, e.g. [1,,2]
	#

	$GLOBALS['json_strings'] = array();

	# these are used as placeholders. they must:
	# 1) only contain alpha, numerics, underscore and dash
	# 2) not exist in the actual json
	$GLOBALS['json_str_prefix'] = 'JSON-STRING-XYZ';
	$GLOBALS['json_slash_temp'] = 'JSON-SLASH-PAIR-XYZ';

	function json_decode_loose($json){

		$GLOBALS['json_strings'] = array();


		#
		# first find obvious strings
		#
#echo "PRE-FIND: $json\n";
		$json = preg_replace_callback('!"((?:[^\\\\"]|\\\\\\\\|\\\\")*)"!', 'json_dqs', $json);
		$json = preg_replace_callback("!'((?:[^\\\\']|\\\\\\\\|\\\\')*)'!", 'json_sqs', $json);
#echo "POST-FIND: $json\n";
#print_r($GLOBALS['json_strings']);

		#
		# missing elements
		#

		$json = str_replace(',,', ',null,', $json);
		$json = str_replace('[,', '[null,', $json);
		$json = str_replace(',]', ',null]', $json);


		#
		# quote unquoted key names
		#

		$json = preg_replace_callback('!([a-zA-Z0-9-_]+):!', 'json_key', $json);


		#
		# replace the strings
		#

#echo "PRE-CONV: $json\n";

		$pre = preg_quote($GLOBALS['json_str_prefix'], '!');
		$json = preg_replace_callback('!'.$pre.'(\d+)!', 'json_strs', $json);

#echo "POST-CONV: $json\n";

		$ret = JSON_decode($json, true);

		if ($ret === null){
			die("Failed to parse JSON:\n$json");
		}

		return $ret;
	}


	function json_dqs($m){

		$idx = count($GLOBALS['json_strings']);
		$GLOBALS['json_strings'][$idx] = $m[1];

		return $GLOBALS['json_str_prefix'].$idx;
	}

	function json_sqs($m){

		$text = str_replace("\\\\", $GLOBALS['json_slash_temp'], $m[1]);
		$text = str_replace("\\'", "'", $text);
		$text = str_replace('"', "\\\"", $text);
		$text = str_replace($GLOBALS['json_slash_temp'], "\\\\", $text);

		$idx = count($GLOBALS['json_strings']);
		$GLOBALS['json_strings'][$idx] = $text;

		return $GLOBALS['json_str_prefix'].$idx;
	}

	function json_strs($m){

		return '"'.$GLOBALS['json_strings'][$m[1]].'"';
	}

	function json_key($m){

		if (strpos($m[1], $GLOBALS['json_str_prefix']) === 0) return $m[0];

		$idx = count($GLOBALS['json_strings']);
		$GLOBALS['json_strings'][$idx] = $m[1];

		return $GLOBALS['json_str_prefix'].$idx.':';
	}
