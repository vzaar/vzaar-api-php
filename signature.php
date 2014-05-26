<?php
require_once 'Vzaar.php';

Vzaar::$token = 'YurxlhhEDLkce4psktKGVIxooQlSDF7T4HUWdYp6ZQ'; //
Vzaar::$secret = 'krevision';

$api_reply = Vzaar::getUploadSignature();

echo(json_encode($api_reply['vzaar-api']));