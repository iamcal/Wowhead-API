<?
	include('testmore.php');

	$cfg = array(
		'http_timeout' => 5,
	);

	include('lib_http.php');
	include('lib_json.php');
	include('lib_wowhead.php');

	plan(13);


	#
	# simple achievements fetch
	#

	$ret = wowhead_achievement(2537);
	ok($ret['ok'], "Achievement fetch returned ok");
	is($ret['title'], "Mountain o' Mounts", "Achievement title");
	is($ret['description'], "Obtain 100 mounts.", "Achievement description");
	is($ret['icon'], 'ability_hunter_pet_dragonhawk', "Achievement icon");
	is($ret['points'], 10, "Achievement points");
	is($ret['faction'], 'horde', "Achievement faction");


	#
	# quest categories
	#

	$ret = wowhead_quest_cats();
	ok($ret['ok'], "Quest cats returned ok");

	cmp_ok(count($ret['cats']), '>', 1, "Quests have multiple categories");

	is($ret['cats'][7]['title'], 'Miscellaneous', "Quest cat 7 title");
	is($ret['cats'][7]['id'], 7, "Quest cat 7 ID");

	cmp_ok(count($ret['cats'][7]['subcats']), '>', 1, "Quest cat 7 has multiple sub cats");

	is($ret['cats'][7]['subcats'][-379]['title'], 'Firelands Invasion', "Quest cat 7.-379 title");
	is($ret['cats'][7]['subcats'][-379]['id'], -379, "Quest cat 7.-379 ID");
