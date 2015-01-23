<?php
require_once '../../src/Vzaar.php';
Vzaar::$token = "u2nd3DVI71jQ7dTtz9mHA953XeIQeodmZvSE6AbTX8s34";
Vzaar::$secret = "skitsanos";

header('Content-type: text/html');

if (isset($_POST['guid'])) {
    $apireply = Vzaar::processVideo($_POST['guid'], $_POST['title'], $_POST['description'], Profile::Original);
    echo($apireply);
} else {
    echo('GUID is missing');
}
?>