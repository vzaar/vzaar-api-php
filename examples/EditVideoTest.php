<?php
require_once './examples/api_helper.php';

class EditVideoTest extends PHPUnit_Framework_TestCase {
    public static function setUpBeforeClass() {
        Vzaar::$url = API_ENVS::get()["url"];
        Vzaar::$token = API_ENVS::get()["user1"]["rw_token"];
        Vzaar::$secret = API_ENVS::get()["user1"]["login"];
    }
    
    public function testEditVideo() {
        $videoId = API_ENVS::get()["user1"]["test_video_id"];
        $newTitle = "api-php-" . generateRandomStr(7);

        $vid = Vzaar::getVideoDetails($videoId, true);
        $originalTitle = $vid->title;

        Vzaar::editVideo($videoId, $newTitle, "woof", false);
        $vid = Vzaar::getVideoDetails($videoId, true);

        $this->assertNotEquals($vid->title, $originalTitle);
    }
}
?>