<?php
require_once '../api_helper.php';

class AddSubtitleTest extends PHPUnit_Framework_TestCase {
    public static function setUpBeforeClass() {
        Vzaar::$url = API_ENVS::get()["url"];
        Vzaar::$token = API_ENVS::get()["user1"]["rw_token"];
        Vzaar::$secret = API_ENVS::get()["user1"]["login"];
    }
    
    
    public function testUploadSubtitle() {
        $videoId = API_ENVS::get()["user1"]["test_video_id"];
        $res = Vzaar::uploadSubtitle("en", $videoId, "SRT");
        $this->assertRegExp('/Accepted/', $res);
    }
}
?>