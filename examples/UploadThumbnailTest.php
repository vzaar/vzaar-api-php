<?php
require_once './examples/api_helper.php';

class UploadThumbnailTest extends PHPUnit_Framework_TestCase {
    private static $thumbPath = "./examples/pic.jpg";
    public static function setUpBeforeClass() {
        Vzaar::$url = API_ENVS::get()["url"];
        Vzaar::$token = API_ENVS::get()["user1"]["rw_token"];
        Vzaar::$secret = API_ENVS::get()["user1"]["login"];
    }
    
    public function testUploadThumbnail() {
        $videoId = API_ENVS::get()["user1"]["test_video_id"];
        $res = Vzaar::uploadThumbnail($videoId, self::$thumbPath);
        $this->assertRegExp('/Accepted/', $res);
    }
}
?>