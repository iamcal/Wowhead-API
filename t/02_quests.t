<?
	include('init.php');

	plan(7);


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
