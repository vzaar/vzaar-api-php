<?php
/**
 * User's public details along with it's relevant metadata.
 * @author Skitsanos
 */

require_once 'VzaarException.php';

class User {
    var $version;
    var $authorName;
    var $authorId;
    var $authorUrl;
    var $authorAccount;
    var $createdAt;
    var $videoCount;
    var $playCount;

    static function fromJson($data) {

	$jo = json_decode($data);

	if($jo==NULL) {
	    throw new VzaarException('Object not found');
	}
	else {
	    $user = new User();

	    $user->authorName = $jo->author_url;
	    $user->playCount = $jo->play_count;
	    $user->authorId = $jo->author_id;
	    $user->authorUrl = $jo->author_url;
	    $user->createdAt = $jo->created_at;
	    $user->authorAccount = $jo->author_account;
	    $user->videoCount = $jo->video_count;
	    $user->version = $jo->version;
	    return $user;
	}
    }

    /**
     * Package protected constructor.
     *
     * @param version the vzaar API version number.
     * @param authorName the vzaar user name (i.e. their login)
     * @param authorId the vzaar user id
     * @param authorUrl a link to the vzaar user summary page
     * @param authorAccount a number representing the users vzaar
     * 		account. 1 represents a free account
     * @param createdAt the date time the video was uploaded. Will be
     * 		in UTC format
     * @param videoCount the number of active videos in the users
     * 		account
     * @param playCount the number of times all the users videos
     * 		have been played
     */
    function __construct() {
    }

    function __construct1($version, $authorName, $authorId, $authorUrl, $authorAccount, $createdAt, $videoCount, $playCount) {
	$this->version = $version;
	$this->authorName = $authorName;
	$this->authorId = $authorId;
	$this->authorUrl = $authorUrl;
	$this->authorAccount = $authorAccount;
	$this->createdAt = $createdAt;
	$this->videoCount = $videoCount;
	$this->playCount = $playCount;
    }

    /**
     * String representation of the user bean.
     */
    public function toString() {
	return
	"version=" . $this->version .
	    ", authorName=" . $this->authorName .
	    ", authorId=" . $this->authorId .
	    ", authorUrl=" . $this->authorUrl .
	    ", authorAccount=" . $this->authorAccount .
	    ", createdAt=" . $this->createdAt .
	    ", videoCount=" . $this->videoCount .
	    ", playCount=" . $this->playCount;
    }
}
?>