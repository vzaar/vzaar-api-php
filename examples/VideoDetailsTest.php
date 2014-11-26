<?php
require_once '../api_helper.php';

class VideoDetailsTest extends PHPUnit_Framework_TestCase {
    public static function setUpBeforeClass() {
        Vzaar::$url = API_ENVS::get()["url"];
        Vzaar::$token = API_ENVS::get()["user1"]["rw_token"];
        Vzaar::$secret = API_ENVS::get()["user1"]["login"];
    }
    
    public function testApiResponse() {
        $videoId = API_ENVS::get()["user1"]["test_video_id"];
        $vid = Vzaar::getVideoDetails($videoId, true);
        $this->assertEquals($vid->type, "video");
    }
}
?>