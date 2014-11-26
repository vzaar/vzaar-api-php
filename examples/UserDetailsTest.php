<?php
require_once '../api_helper.php';

class UserDetailsTest extends PHPUnit_Framework_TestCase {
    public static function setUpBeforeClass() {
        Vzaar::$url = API_ENVS::get()["url"];
        Vzaar::$token = API_ENVS::get()["user1"]["rw_token"];
        Vzaar::$secret = API_ENVS::get()["user1"]["login"];
    }
    
    public function testResponse() {
        $username = API_ENVS::get()["user1"]["login"];
        $user = Vzaar::getUserDetails($username);
        
        $this->assertContains("users/" . $username, $user->authorUrl);
    }
}
?>