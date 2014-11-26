<?php
require_once '../api_helper.php';

class AuthenticationTest extends PHPUnit_Framework_TestCase {
    public static $filePath = "./video.mp4";

    public static function setUpBeforeClass() {
        Vzaar::$url = API_ENVS::get()["url"];
        Vzaar::$token = API_ENVS::get()["user1"]["rw_token"];
        Vzaar::$secret = API_ENVS::get()["user1"]["login"];
    }
    
    public function testOauthAuthentication() {
        $expected_val = API_ENVS::get()["user1"]["login"];
        $res = Vzaar::whoAmI();
        $this->assertEquals($res, $expected_val);
    }
}
?>