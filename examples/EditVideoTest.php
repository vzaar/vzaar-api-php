<?php
require_once '../api_helper.php';

class EditVideoTest extends PHPUnit_Framework_TestCase {
    public static function setUpBeforeClass() {
        Vzaar::$url = API_ENVS::get()["url"];
        Vzaar::$token = API_ENVS::get()["user1"]["rw_token"];
        Vzaar::$secret = API_ENVS::get()["user1"]["login"];
    }
    
    public function testUploadThumbnail() {
        $videoId = API_ENVS::get()["user1"]["test_video_id"];
        $newTitle = "api-php-" . generateRandomStr(7);
        
        $vid = Vzaar::getVideoDetails($videoId, true);
        $originalTitle = $vid->title;

        $this->assertNotEquals($newTitle, $originalTitle);
    }
}
?>