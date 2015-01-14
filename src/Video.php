<?php
/**
 * Active video details along with it's relevant metadata.
 *
 */
require_once 'User.php';

class Video
{
	var $version;
	var $id;
	var $title;
	var $description;
	var $createdAt;
	var $url;
	var $thumbnail;
	var $playCount;
	var $duration;
	var $width;
	var $height;
	var $framegrabUrl;

	var $user;

	static function fromJson($data)
	{
		$jo = json_decode($data);

		$video = new Video();
		$video->duration = $jo->duration;
		$video->playCount = $jo->play_count;
		$video->title = $jo->title;
		$video->url = $jo->url;
		$video->createdAt = $jo->created_at;
		$video->version = $jo->version;
		$video->user = new User();
		$video->user->authorAccount = $jo->user->author_account;
		$video->user->authorName = $jo->user->author_name;
		$video->user->authorUrl = $jo->user->author_url;
		$video->user->videoCount = $jo->user->video_count;
		$video->id = $jo->id;
		$video->thumbnail = $jo->thumbnail;
		$video->width = $jo->width;
		$video->height = $jo->height;
		$video->framegrabUrl = 'http://vzaar.com/videos/' . $jo->id . '.frame';

		return $video;
	}


	/**
	 * Package protected constructor.
	 *
	 * @param version the vzaar API version number
	 * @param id the video ID number
	 * @param title the video title. It may be null
	 * @param description the video description. It may be null
	 * @param createdAt the date time the video was uploaded
	 * @param url the link to the video page
	 * @param thumbnailUrl the URL link that points to the video thumbnail
	 * @param playCount the number of times the video has been played
	 * @param authorName the vzaar user name (i.e. their login)
	 * @param authorUrl the link to the vzaar user summary page
	 * @param authorAccount the number representing the users vzaar account
	 * @param videoCount the number of active videos in the users account
	 * @param duration the duration of the video
	 * @param <integer> width the width of the video
	 * @param height the height of the video
	 */
	public function  __construct1($version, $id, $title, $description, $createdAt, $url, $thumbnailUrl, $playCount, $authorName, $authorUrl, $authorAccount, $videoCount, $duration, $width, $height)
	{
		$this->user = new User();

		$this->version = $version;
		$this->id = $id;
		$this->title = $title;
		$this->description = $description;
		$this->createdAt = $createdAt;
		$this->url = $url;
		$this->thumbnailUrl = $thumbnailUrl;
		$this->playCount = $playCount;
		$this->user->authorName = $authorName;
		$this->user->authorUrl = $authorUrl;
		$this->user->authorAccount = $authorAccount;
		$this->user->videoCount = $videoCount;
		$this->duration = $duration;
		$this->width = $width;
		$this->height = $height;
	}
}

?>