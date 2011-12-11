<?
	#
	# a library for turning wowhead pages into useful data.
	# don't abuse wowhead - we love them.
	#


	function wowhead_achievement($id){

		$ret = wowhead_request("/achievement=$id");
		if (!$ret['ok']) return $ret;

		$body = $ret['body'];

		$out = array(
			'ok'		=> 1,
			'title'		=> '',
			'description'	=> '',
			'icon'		=> '',
			'points'	=> 0,
			'faction'	=> 'both',
		);

		if (strpos($body, 'span class=icon-horde') !== false) $out['faction'] = 'horde';
		if (strpos($body, 'span class=icon-alliance') !== false) $out['faction'] = 'alliance';
		if (preg_match('!achievementpoints=(\d+)!', $body, $m)) $out['points'] = $m[1];

		$out['title'] = wowhead_meta_tag($body, 'og:title');
		$out['description'] = wowhead_meta_tag($body, 'og:description');

		if (preg_match("!_\[$id\]=(\{.*?\});!", $body, $m)){
			$obj = wowhead_decode_json($m[1]);
			$out['icon'] = $obj['icon'];
		}

		return $out;
	}


	###########################################################################

	#
	# fetches a page from wowhead
	#

	function wowhead_request($path){

		return http_get("http://www.wowhead.com$path");
	}


	#
	# wowhead page contain meta tags - this function pulls out the `content`
	# attribute for tags with a matching `property` attribute
	#

	function wowhead_meta_tag($body, $property){

		if (preg_match("!<meta.*?property=\"$property\".*?>!", $body, $m)){

			if (preg_match('!content="(.*?)"!', $m[0], $m2)){

				return $m2[1];
			}
		}

		return '';
	}



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

	function wowhead_decode_json($json){

		#
		# replace single quotes with doubles where it makes sense
		# (this does not currently handle ~\\'~ correctly)
		#

		$json = preg_replace("~(?<!\\\\)'~", '"', $json);
		$json = str_replace("\\'", "'", $json);


		#
		# quote unquoted key names
		#

		$json = preg_replace('!(\w+):!', '"$1":', $json);

		return JSON_decode($json, true);
	}
