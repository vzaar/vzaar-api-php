<?php
require_once './examples/api_helper.php';

class UploadVideoTest extends PHPUnit_Framework_TestCase {
    public static $filePath = "./examples/video.mp4";
    public static function setUpBeforeClass() {
        Vzaar::$url = API_ENVS::get()["url"];
        Vzaar::$token = API_ENVS::get()["user1"]["rw_token"];
        Vzaar::$secret = API_ENVS::get()["user1"]["login"];
    }
    
    
    public function testUploadVideo() {
        $title = "api-php-" . generateRandomStr(5);
        $guid = Vzaar::uploadVideo(self::$filePath);
        
        $videoId = Vzaar::processVideo($guid, $title, "php test", "");

        $vid = Vzaar::getVideoDetails($videoId, true);
        $this->assertEquals($vid->videoStatusDescription, "Preparing");

        // clean up
        Vzaar::deleteVideo($videoId);
    }
}
?>