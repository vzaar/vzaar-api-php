<?php
require_once '../api_helper.php';

class AccountDetailsTest extends PHPUnit_Framework_TestCase {
    public static function setUpBeforeClass() {
        Vzaar::$url = API_ENVS::get()["url"];
        Vzaar::$token = API_ENVS::get()["user1"]["rw_token"];
        Vzaar::$secret = API_ENVS::get()["user1"]["login"];
    }
    
    public function testResponse() {
        $accountId = 34;
        $account = Vzaar::getAccountDetails($accountId);
        $this->assertEquals($account->accountId, $accountId);
    }
}
?>