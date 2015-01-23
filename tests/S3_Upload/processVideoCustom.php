<?php
require_once '../../src/Vzaar.php';
Vzaar::$token = "u2nd3DVI71jQ7dTtz9mHA953XeIQeodmZvSE6AbTX8";
Vzaar::$secret = "skitsanos";

header('Content-type: text/html');

if (isset($_POST['guid'])) {
    $apireply = Vzaar::processVideoCustomized($_POST['guid'], $_POST['title'], $_POST['description'], '');
    echo($apireply);
}
else
{
    echo('GUID is missing');
}
?>