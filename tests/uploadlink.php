<?php
date_default_timezone_set("Europe/London");

require_once '../Vzaar.php';

Vzaar::$token = "token";
Vzaar::$secret = "secret";

$title = "My video";
$description = "Sample description";

if(isset($_POST['url'])) {
  $video_id = Vzaar::uploadLink($_POST['url'], $title, $description);

  print "Video id: " . $video_id;
}

?>

<html>
    <head>
        <title>Link Upload Form</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>

    <body>
        <form method="post" enctype="multipart/form-data">
            URL to download the video from:
            <input name="url" type="text"/>
            <br/>
            <input type="submit" value="Upload Video"/>
        </form>
    </body>
</html>
