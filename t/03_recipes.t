<?
	include('init.php');

	plan(5);


	#
	# recipes
	#

	$ret = wowhead_recipes(165);
	ok($ret['ok'], "Recipes returned ok");

	cmp_ok(count($ret['recipes']), '>', 1, "We have multiple recipes");

	is($ret['recipes'][101940]['name'], "Bladeshadow Wristguards");
	is($ret['recipes'][101940]['qual'], 3);
	is($ret['recipes'][101940]['icon'], "inv_bracer_leather_raidrogue_k_01");
