<?php
    require 'autoload.php';


$paramsFile = array('filepath' => './videos/small.mp4');
$paramsFile['title'] = 'Test Video Subtitle';

$videoFile = VzaarApi\Video::create($paramsFile);
echo PHP_EOL.'Create VOD: '. $videoFile->id .'-'. $videoFile->title;

// Create subtitle from string
$params = [
    'code' => 'en',
    'content' => "1\n00:00:00,498 --> 00:00:02,827\nMy Subtitles",
];
$subtitle = VzaarApi\Subtitle::create($videoFile->id, $params);
echo PHP_EOL.'Create Subtitle from string: '. $subtitle->id .'-'. $subtitle->title;

// Create subtitle from file
$params = [
    'code' => 'fr',
    'filepath' => './subtitles/subtitle.srt',
];
$subtitle = VzaarApi\Subtitle::create($videoFile->id, $params);
echo PHP_EOL.'Create Subtitle from file: '. $subtitle->id .'-'. $subtitle->title;

// Update subtitle from string
$subtitle->code = 'de';
$subtitle->content = "1\n00:00:00,498 --> 00:00:02,827\nGuten morgen this is subtitle";
$subtitle->save($videoFile->id);
echo PHP_EOL.'Updated Subtitle from string: '. $subtitle->id .'-'. $subtitle->title;

// Update subtitle from file
$subtitle->code = 'be';
$subtitle->filepath = './subtitles/subtitle.srt';
$subtitle->save($videoFile->id);
echo PHP_EOL.'Updated Subtitle from file: '. $subtitle->id .'-'. $subtitle->title;

// Iterate over subtitle
foreach(VzaarApi\SubtitleList::each_item_for_vod($videoFile->id) as $sub) {
    echo PHP_EOL.'List Subtitles (iterate): id: ' .$sub->id .' '. $sub->code .' '. $sub->title;
}

// Delete subtitle
$subtitle->delete($videoFile->id);