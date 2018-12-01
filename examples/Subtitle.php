<?php
    require 'autoload.php';


$paramsFile = array('filepath' => './videos/small.mp4');
$paramsFile['title'] = 'Test Video Subtitle';

$videoFile = VzaarApi\Video::create($paramsFile);
echo PHP_EOL.'Create VOD: '. $videoFile->id .'-'. $videoFile->title;

$params = [
    'code' => 'en',
    'content' => "1\n00:00:00,498 --> 00:00:02,827\nMy Subtitles",
];
$subtitle = VzaarApi\Subtitle::create($videoFile->id, $params);
echo PHP_EOL.'Create Subtitle: '. $subtitle->id .'-'. $subtitle->title;


foreach(VzaarApi\SubtitleList::each_item_for_vod($videoFile->id) as $sub) {
    echo PHP_EOL.'List Subtitles (iterate): id: ' .$sub->id .' '. $sub->code .' '. $sub->title;
}

$subtitle->delete($videoFile->id);