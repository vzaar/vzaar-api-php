<?php

require_once '../../Vzaar.php';
Vzaar::$token = "GETUGkPFNC84JlzXkOMSYQFTOCAixOIiroh7oUj3k";
Vzaar::$secret = "skitsanos";

$guid = Vzaar::uploadVideo('D:\Sites\Vzaar\Vzaar API\api\php\trunk\tests\video.flv');
echo('guid: ' . $guid . ' --- video id: ');

$apireply = Vzaar::processVideo($guid, 'SimpleUpload test', '', 1);
echo($apireply);
?>
