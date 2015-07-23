<?php
require_once 'Vzaar.php';

Vzaar::$token = ''; //
Vzaar::$secret = '';

$api_reply = Vzaar::getUploadSignature();

echo(json_encode($api_reply['vzaar-api']));