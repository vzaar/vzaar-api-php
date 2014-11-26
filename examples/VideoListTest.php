<?php
require_once '../api_helper.php';

class VideoListTest extends PHPUnit_Framework_TestCase {
    public static function setUpBeforeClass() {
        Vzaar::$url = API_ENVS::get()["url"];
        Vzaar::$token = API_ENVS::get()["user1"]["rw_token"];
        Vzaar::$secret = API_ENVS::get()["user1"]["login"];
    }
    
    public function testCount() {
        $username = API_ENVS::get()["user1"]["login"];
        $vids = Vzaar::getVideoList($username, true, 3);

        $this->assertEquals(count($vids), 3);
    }

    public function testLabels() {
        $username = API_ENVS::get()["user1"]["login"];
        $vids = Vzaar::getVideoList($username, true, 20, "api,api2");

        $this->assertEquals(count($vids), 1);
    }
}
?>