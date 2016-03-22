<?php
/**
 * VideoList
 *
 */
require_once 'Video.php';
require_once 'User.php';
require_once 'VzaarException.php';

class VideoList {
    var $items;

    static function fromJson($data) {
        $jo = (array)json_decode($data);

        if(array_key_exists('error', $jo)) {
            throw new VzaarException($jo->error);
        }
        else {
            $videos = array();

            for ($i = 0, $l=count($jo); $i<$l; $i++) {
                $video = new Video();
                $video->duration = $jo[$i]->duration;
                $video->playCount = $jo[$i]->play_count;
                $video->title = $jo[$i]->title;
                $video->description = $jo[$i]->description;
                $video->url = $jo[$i]->url;
                $video->createdAt = $jo[$i]->created_at;
                $video->version = $jo[$i]->version;
                $video->user= new User();
                $video->user->authorAccount = $jo[$i]->user->author_account;
                $video->user->authorName = $jo[$i]->user->author_name;
                $video->user->authorUrl = $jo[$i]->user->author_url;
                $video->user->videoCount = $jo[$i]->user->video_count;
                $video->id = $jo[$i]->id;
                $video->thumbnail = $jo[$i]->thumbnail;
                $video->width = $jo[$i]->width;
                $video->height = $jo[$i]->height;
                $video->framegrabUrl = Vzaar::$url . 'videos/' . $jo[$i]->id . '.frame';

                array_push($videos, $video);
            }

            return $videos;
        }
    }
}
?>
