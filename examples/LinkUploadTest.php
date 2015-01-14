<?php
require_once './examples/api_helper.php';

class LinkUploadTest extends PHPUnit_Framework_TestCase {
    public static $fileUrl = "http://samples.mplayerhq.hu/MPEG-4/turn-on-off.mp4";
    public static function setUpBeforeClass() {
        Vzaar::$url = API_ENVS::get()["url"];
        Vzaar::$token = API_ENVS::get()["user1"]["rw_token"];
        Vzaar::$secret = API_ENVS::get()["user1"]["login"];
    }
    
    
    public function testLinkUpload() {
        $title = "api-php-link-upload-" . generateRandomStr(5);
        $videoId = Vzaar::uploadLink(self::$fileUrl, $title);

        $vid = Vzaar::getVideoDetails($videoId, true);
        $this->assertEquals($vid->videoStatusDescription, "Preparing");
        
        // clean up
        Vzaar::deleteVideo($videoId);
    }
}
?>