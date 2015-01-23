<?php
/*
 * @author Skitsanos
*/
require_once '../src/Vzaar.php';


Vzaar::$token = 'token'; //
Vzaar::$secret = 'secret';

$redirect_url='http://vzaarapi.vpn/tests/uploadvideoprocess.php';

$uploadSignature=Vzaar::getUploadSignature($redirect_url);

$signature=$uploadSignature['vzaar-api'];
?>


<html>
    <head>
        <title>S3 POST Form with redirect</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="author" content="Skitsanos.com"/>
    </head>

    <body>
        <form action="https://<?php echo $signature['bucket'];?>.s3.amazonaws.com/" method="post" enctype="multipart/form-data">
            <!--
            <input name="content-type" type="hidden" value="binary/octet-stream" />
            -->
            <input type="hidden" name="acl" value="<?php echo $signature['acl']; ?>">
            <input type="hidden" name="bucket" value="<?php echo $signature['bucket']; ?>">
            <input type="hidden" name="policy" value="<?php echo $signature['policy']; ?>">
            <input type="hidden" name="AWSAccessKeyId" value="<?php echo $signature['accesskeyid']; ?>">
            <input type="hidden" name="signature" value="<?php echo $signature['signature']; ?>">
            <input type="hidden" name="success_action_status" value="201">
            <input type="hidden" name="success_action_redirect" value="<?php echo $redirect_url; ?>?guid=<?php echo $signature['guid']; ?>">
            <input type="hidden" name="key" value="<?php echo $signature['key']; ?>">

            File to upload to S3:
            <input name="file" type="file">
            <br>
            <input type="submit" value="Upload File to S3">
        </form>
    </body>
</html>