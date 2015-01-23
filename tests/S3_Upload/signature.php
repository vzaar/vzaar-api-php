<?php
/*
 * S3_Upload Test
 * Generating Signature for Amazon S3 uploads. Add your own security layer here
 * if necessary.
 */
require_once '../../src/Vzaar.php';
Vzaar::$token = "u2nd3DVI71jQ7dTtz9mHA953XeIQeodmZvSE6AbTX8";
Vzaar::$secret = "skitsanos";
Vzaar::$enableFlashSupport = true;

header('Content-type: text/xml');

echo(Vzaar::getUploadSignatureAsXml());
?>
