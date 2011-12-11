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
	}

