<?php
date_default_timezone_set("Europe/London");

require_once '../src/Vzaar.php';

Vzaar::$token = "token";
Vzaar::$secret = "secret";

$video_id = 123;

if(isset($_FILES['file'])) {
  $status = Vzaar::uploadThumbnail(123, $_FILES['file']['tmp_name']);

  print "Upload status: " . $status;
}

?>

<html>
    <head>
        <title>Thumbnail Upload Form</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>

    <body>
        <form method="post" enctype="multipart/form-data">
            Thumbnail to upload:
            <input name="file" type="file"/>
            <br/>
            <input type="submit" value="Upload Thumbnail"/>
        </form>
    </body>
</html>
