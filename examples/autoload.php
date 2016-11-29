<?php
    require '../lib/vzaar.php';

    Vzaar\Client::$id = $_ENV['VZAAR_CLIENT_ID'];
    Vzaar\Client::$token = $_ENV['VZAAR_AUTH_TOKEN'];

    //outputs HTTP protocol data
    //Vzaar\HttpCurl::$VERBOSE = true;

    //outputs json request/response
    //Vzaar\Client::$VERBOSE = true;

    //outputs S3upload POST form parameters (except the 'file')
    //Vzaar\S3Client::$VERBOSE = true;
?>
