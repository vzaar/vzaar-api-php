<?php
    require 'autoload.php';

$paramsFile = array('filepath' => './videos/small.mp4');
$paramsFile['title'] = 'Test Video Image Frame';

$videoFile = VzaarApi\Video::create($paramsFile);
echo PHP_EOL.'Create VOD: '. $videoFile->id .'-'. $videoFile->title;

// From video itself
$params = [
    'time' => 2.5,
];
$video = VzaarApi\ImageFrame::set($videoFile->id, $params);
echo 'Video: ' . $video->id;