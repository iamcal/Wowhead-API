<?
	#
	# a library for turning wowhead pages into useful data.
	# don't abuse wowhead - we love them.
	#


	function wowhead_achievement($id){

		$ret = wowhead_request("http://www.wowhead.com/achievement=$id");
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
			$obj = json_decode_loose($m[1]);
			$out['icon'] = $obj['icon'];
		}

		return $out;
	}


	###########################################################################

	#
	# fetches a page from wowhead. this is just a stub to let
	# us pass any needed args.
	#

	function wowhead_request($url){

		return http_get($url);
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
