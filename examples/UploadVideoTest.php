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

    public function testNonMulitpartVideoSignature() {
        $signature = Vzaar::getUploadSignature();
        $policy = base64_decode($signature['vzaar-api']['policy']);

        // doesn't have chunk and chunks policy
        $this->assertNotContains('["starts-with","$chunk",""]', $policy);
        $this->assertNotContains('["starts-with","$chunks",""]', $policy);
    }

    public function testMulitpartVideoSignature() {
        $signature = Vzaar::getUploadSignature(null, true);
        $policy = base64_decode($signature['vzaar-api']['policy']);

        // has chunk and chunks policy
        $this->assertContains('["starts-with","$chunk",""]', $policy);
        $this->assertContains('["starts-with","$chunks",""]', $policy);
    }
}
?>
