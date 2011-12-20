<?
	include('init.php');

	plan(6);


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

