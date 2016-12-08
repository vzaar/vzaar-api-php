<?php

    require dirname(__FILE__). '/../lib/vzaar.php';
    
    require dirname(__FILE__).'/fixtures/DummyRecord.php';
    require dirname(__FILE__).'/fixtures/DummyList.php';
    require dirname(__FILE__).'/VzaarTest.php';
    
    VzaarApi\Client::$client_id = $_ENV['VZAAR_CLIENT_ID'];
    VzaarApi\Client::$auth_token = $_ENV['VZAAR_AUTH_TOKEN'];

?>
