<?
	#
	# make sure the backtrack limit is set to soemthing sensible.
	# PHP before 5.2.0 sets it to 10M
	# PHP 5.2.0-5.37 sets it to 100k (too small!)
	# PHP 5.3.7 onwards sets it to 1M
	# https://bugs.php.net/bug.php?id=40846
	#

	$backtrack = ini_get('pcre.backtrack_limit');
	$backtrack_set = 10000000; # 10 million
	if ($backtrack < $backtrack_set) ini_set('pcre.backtrack_limit', $backtrack_set);


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


	function wowhead_quest_cats(){

		$ret = wowhead_request('http://wowjs.zamimg.com/js/locale_enus.js');
		if (!$ret['ok']) return $ret;

		$body = $ret['body'];


		#
		# parse it out into variable assignments
		#

		$all = array();
		$chunks = explode('var ', $body);
		foreach ($chunks as $chunk){
			$chunk = preg_replace('!;\s*!', '', $chunk);
			if (strpos($chunk, '=') !== false){
				list($name, $json) = explode('=', $chunk, 2);
				$all[$name] = $json;
			}
		}


		#
		# get quest data
		#

		$obj = json_decode_loose($all['mn_quests']);

		$out = array();
		foreach ($obj as $cat){
			$subs = array();

			if (isset($cat[3]) && is_array($cat[3])){
				foreach ($cat[3] as $sub){
					$subs[$sub[0]] = array(
						'id'	=> $sub[0],
						'title'	=> $sub[1],
					);
				}
			}

			$out[$cat[0]] = array(
				'id'	=> $cat[0],
				'title'	=> $cat[1],
				'subcats' => $subs,
			);
		}

		return array(
			'ok'	=> 1,
			'cats'	=> $out,
		);
	}


	###########################################################################

	function wowhead_recipes($profession_id){

		$ret = wowhead_request("http://www.wowhead.com/skill=$profession_id");
		if (!$ret['ok']) return $ret;

		$body = $ret['body'];


		#
		# find the recipes ListView
		#

		$map = array();

		preg_match_all('!new Listview\((.*?)\);!', $body, $m);
		foreach ($m[1] as $json){

			$obj = json_decode_loose($json);

			$map[$obj['id']] = $obj;
		}

		$rs = array();
		foreach ($map['recipes']['data'] as $row){

			$r = array(
				'name'	=> substr($row['name'], 1),
				'qual'	=> substr($row['name'], 0, 1),
			);

			if (preg_match("!_\[$row[id]\]=(.*?);!", $body, $m)){
				$obj = json_decode_loose($m[1]);

				$r['icon'] = $obj['icon'];
			}

			$rs[$row['id']] = $r;

		}

		return array(
			'ok'		=> 1,
			'recipes'	=> $rs,
		);
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
