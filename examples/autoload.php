<?php
    require '../lib/vzaar.php';
    
    VzaarApi\Client::$client_id = $_ENV['VZAAR_CLIENT_ID'];
    VzaarApi\Client::$auth_token = $_ENV['VZAAR_AUTH_TOKEN'];
    
    //outputs HTTP protocol data
    //VzaarApi\HttpCurl::$VERBOSE = true;
    
    //outputs json request/response
    //VzaarApi\Client::$VERBOSE = true;
    
    //outputs S3upload POST form parameters (except the 'file')
    //VzaarApi\S3Client::$VERBOSE = true;
?>
