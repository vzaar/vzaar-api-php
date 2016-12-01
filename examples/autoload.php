<?php
    require '../lib/vzaar.php';
    
    VzaarApi\Client::$client_id = $_ENV['VZAAR_CLIENT_ID'];
    VzaarApi\Client::$auth_token = $_ENV['VZAAR_AUTH_TOKEN'];
    
    //outputs equest/response details
    //VzaarApi\Client::$VERBOSE = true;

?>
