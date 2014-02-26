<?php

/**
 * Active video and oEmbed details along with it's relevant metadata.
 *
 */
class VideoDetails
{

	var $type;
	var $version;
	var $title;
	var $description;
	var $playCount;
	var $authorName;
	var $authorUrl;
	var $authorAccount;
	var $providerName;
	var $providerUrl;
	var $thumbnailUrl;
	var $thumbnailWidth;
	var $thumbnailHeight;
	var $framegrabUrl;
	var $framegrabWidth;
	var $framegrabHeight;
	var $html;
	var $height;
	var $width;
	var $borderless;
	var $duration;
	var $videoStatus;
	var $videoStatusDescription;

	/**
	 * Package protected constructor.
	 *
	 * @param type the oEmbed resource type
	 * @param version the vzaar API version number
	 * @param title the video title. It may be null
	 * @param description the video description. It may be null
	 * @param authorName the vzaar user name (i.e. their login)
	 * @param authorUrl the link to the vzaar user summary page
	 * @param authorAccount the number representing the users vzaar account
	 * @param providerName this will always be vzaar
	 * @param providerUrl this will always be http://vzaar.com
	 * @param thumbnailUrl the URL link that points to the video thumbnail
	 * @param thumbnailWidth the width of the thumbnail in pixels
	 * @param thumbnailHeight the height of the thumbnail in pixels
	 * @param framegrabUrl the URL that points to a framegrab of the video
	 * @param framegrabWidth the width of the frame grab in pixels
	 * @param framegrabHeight the height of the frame grab in pixels
	 * @param html the exact HTML you need to use to embed the video into a webpage
	 * @param height the height of the video
	 * @param width the width of the video
	 * @param borderless does the video player has no border
	 * @param duration the duration of the video
	 */
	function __construct()
	{

	}

	function __construct1($type, $version, $title, $description, $authorName, $authorUrl, $authorAccount, $providerName, $providerUrl, $thumbnailUrl, $thumbnailWidth, $thumbnailHeight, $framegrabUrl, $framegrabWidth, $framegrabHeight, $html, $height, $width, $borderless, $duration, $videoStatus)
	{
		$this->type = $type;
		$this->version = $version;
		$this->title = $title;
		$this->description = $description;
		$this->user->authorName = $authorName;
		$this->user->authorUrl = $authorUrl;
		$this->user->authorAccount = $authorAccount;
		$this->providerName = $providerName;
		$this->providerUrl = $providerUrl;
		$this->thumbnailUrl = $thumbnailUrl;
		$this->thumbnailWidth = $thumbnailWidth;
		$this->thumbnailHeight = $thumbnailHeight;
		$this->framegrabUrl = $framegrabUrl;
		$this->framegrabWidth = $framegrabWidth;
		$this->framegrabHeight = $framegrabHeight;
		$this->html = $html;
		$this->height = $height;
		$this->width = $width;
		$this->borderless = $borderless;
		$this->duration = $duration;
		$this->videoStatus = $videoStatus;
	}

	/**
	 * Contructs Video Details object from JSON
	 * @param <type> $data
	 * @return VideoDetails
	 */
	static function fromJson($data)
	{
		$jo = json_decode($data); //error messages comes in format like this: "{"error":"In progress"}"
		if ($jo == NULL)
		{
			return NULL;
		}
		else
		{
			$vid = new VideoDetails();
			if (array_key_exists('error', $jo))
			{
				if (strpos($jo->error, 'progress'))
				{
					$vid->type = 'video';
					$vid->videoStatus = VideoStatus::PROCESSING;
					$vid->videoStatusDescription = VideoStatusDescriptions::PROCESSING;
				}
			}
			else
			{
				if (array_key_exists('vzaar-api', $jo))
				{
					$vars = get_object_vars($jo);
					if (array_key_exists('error', $vars['vzaar-api']))
					{
					   throw new VzaarException($vars['vzaar-api']->error->type);
					}
					else
					{
						$vid->type = $vars['vzaar-api']->type;
						$vid->videoStatus = $vars['vzaar-api']->video_status_id;
						$vid->videoStatusDescription = $vars['vzaar-api']->state;
					}
				}
				else
				{
					$vid->authorAccount = $jo->author_account;
					$vid->authorName = $jo->author_name;
					$vid->authorUrl = $jo->author_url;
					$vid->borderless = (array_key_exists('borderless', $jo) ? $jo->borderless : NULL);
					$vid->description = (array_key_exists('description', $jo) ? $jo->description : NULL);
					$vid->duration = (array_key_exists('duration', $jo) ? $jo->duration : NULL);
					$vid->framegrabHeight = (array_key_exists('framegreb_height', $jo) ? $jo->framegreb_height : NULL);
					$vid->framegrabUrl = (array_key_exists('framegrab_url', $jo) ? $jo->framegrab_url : NULL);
					$vid->framegrabWidth = (array_key_exists('framegrab_width', $jo) ? $jo->framegrab_width : NULL);
					$vid->height = $jo->height;
					$vid->html = $jo->html;
					$vid->providerName = $jo->provider_name;
					$vid->providerUrl = $jo->provider_url;
					$vid->thumbnailHeight = (array_key_exists('thumbnail_height', $jo) ? $jo->thumbnail_height : NULL);
					$vid->thumbnailUrl = (array_key_exists('thumbnail_url', $jo) ? $jo->thumbnail_url : NULL);
					$vid->thumbnailWidth = (array_key_exists('thumbnail_width', $jo) ? $jo->thumbnail_width : NULL);
					$vid->title = $jo->title;
					$vid->playCount = $jo->play_count;
					$vid->type = $jo->type;
					$vid->version = $jo->version;
					$vid->width = $jo->width;
					$vid->videoStatus = (array_key_exists('video_status_id', $jo) ? $jo->video_status_id : 0);
					$vid->videoStatusDescription = (array_key_exists('video_status_description', $jo) ? $jo->video_status_description : '');
				}
			}
			return $vid;
		}
	}

}

?>