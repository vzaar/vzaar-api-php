<?php
require_once './examples/api_helper.php';

class GenerateThumbnailTest extends PHPUnit_Framework_TestCase {
    public static function setUpBeforeClass() {
        Vzaar::$url = API_ENVS::get()["url"];
        Vzaar::$token = API_ENVS::get()["user1"]["rw_token"];
        Vzaar::$secret = API_ENVS::get()["user1"]["login"];
    }
    
    public function testGenerateThumbnail() {
        $videoId = API_ENVS::get()["user1"]["test_video_id"];
        $res = Vzaar::generateThumbnail($videoId, 2);
        $this->assertRegExp('/Accepted/', $res);
    }
}
?>