<?php
/*
 * @author Skitsanos
*/

date_default_timezone_set("Europe/London");

require_once '../Vzaar.php';

Vzaar::$token = '960DUk4qz9mFijtPwahllzpHYWQzKSVJiIyUpQ82Ac'; //
Vzaar::$secret = 'skitsanos';

$uploadSignature=Vzaar::getUploadSignature();

$signature=$uploadSignature['vzaar-api'];
?>


<html>
    <head>
        <title>S3 POST Form</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="author" content="Skitsanos.com"/>
    </head>

    <body>
        <form action="https://<?php echo $signature['bucket'];?>.s3.amazonaws.com/" method="post" enctype="multipart/form-data">            
            <input type="hidden" name="key" value="<?php echo $signature['key']; ?>">
            <input type="hidden" name="AWSAccessKeyId" value="<?php echo $signature['accesskeyid']; ?>">
            <input type="hidden" name="acl" value="<?php echo $signature['acl']; ?>">
            <input type="hidden" name="policy" value="<?php echo $signature['policy']; ?>">
            <input type="hidden" name="success_action_status" value="201">
            <input type="hidden" name="signature" value="<?php echo $signature['signature']; ?>">

            File to upload to S3:
            <input name="file" type="file"/>
            <br/>
            <input type="submit" value="Upload File to S3"/>
        </form>
    </body>
</html>