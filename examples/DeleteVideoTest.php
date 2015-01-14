<?php
require_once './examples/api_helper.php';

class DeleteVideoTest extends PHPUnit_Framework_TestCase {
    public static $filePath = "./examples/video.mp4";
    public static function setUpBeforeClass() {
        Vzaar::$url = API_ENVS::get()["url"];
        Vzaar::$token = API_ENVS::get()["user1"]["rw_token"];
        Vzaar::$secret = API_ENVS::get()["user1"]["login"];
    }
    
    
    public function testUploadVideo() {
        $guid = Vzaar::uploadVideo(self::$filePath);        
        $videoId = Vzaar::processVideo($guid, "for deletion", "php test", "");

        $json = json_decode(Vzaar::deleteVideo($videoId));
        $this->assertEquals($json->video_status_id, 9);
    }
}
?>