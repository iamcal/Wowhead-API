<?
	require_once(dirname(__FILE__) . '/simpletest/autorun.php');

	$cfg = array(
		'http_timeout' => 5,
	);

	include('lib_http.php');
	include('lib_json.php');
	include('lib_wowhead.php');


	#
	# let the tests begin!
	#

	class WowheadAPITests extends UnitTestCase {

		function testAchievements(){

			$ret = wowhead_achievement(2537);
			$this->assertTrue($ret['ok']);

			$this->assertEqual($ret['title'], "Mountain o' Mounts");
			$this->assertEqual($ret['description'], "Obtain 100 mounts.");
			$this->assertEqual($ret['icon'], 'ability_hunter_pet_dragonhawk');
			$this->assertEqual($ret['points'], 10);
			$this->assertEqual($ret['faction'], 'horde');
		}

		function testQuests(){

			$ret = wowhead_quest_cats();
			$this->assertTrue($ret['ok']);

			$this->assertTrue(count($ret['cats']) > 0);

			$this->assertEqual($ret['cats'][7]['title'], 'Miscellaneous');
			$this->assertEqual($ret['cats'][7]['id'], 7);

			$this->assertTrue(count($ret['cats'][7]['subcats']) > 0);

			$this->assertEqual($ret['cats'][7]['subcats'][-379]['title'], 'Firelands Invasion');
			$this->assertEqual($ret['cats'][7]['subcats'][-379]['id'], -379);
		}
	}

