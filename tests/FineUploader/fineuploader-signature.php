<?php

require '../../Vzaar.php';
Vzaar::$token = 'u2nd3DVI71jQ7dTtz9mHA953XeIQeodmZvSE6AbTX8';
Vzaar::$secret = 'skitsanos';

$rawReply = Vzaar::getUploadSignature();
$api_reply = $rawReply['vzaar-api'];

echo(json_encode($api_reply));

