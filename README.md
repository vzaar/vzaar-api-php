vzaar API PHP client
---
Refreshed vzaar API client for PHP developers. Documentation provided below is valid for the library version 2.0 and above.

---

>vzaar is the go to video hosting platform for business. Affordable, customizable and secure. Leverage the power of online video and enable commerce with vzaar. For more details and signup please visit [http://vzaar.com](http://vzaar.com)

----

####Using the library

In order to start using vzaar API library

```php
require_once 'Vzaar.php';

Vzaar::$token = 'VZAAR_API_TOKEN';
Vzaar::$secret = 'VZAAR_USERNAME';
```

In order to use vzaar API, you need to have a valid user name and API token that you can get from your vzaar dashboard at [http://app.vzaar.com/settings/api](http://app.vzaar.com/settings/api)

The very next thing you would want to do is to check if your account actually works and operational and you can do it by simple calling _whoAmI_:

```php
echo(Vzaar::whoAmI());
```

If it returns you your vzaar username, - we are good to go.

####User Details

>This API call returns the user's public details along with it's relevant metadata. It also contains vzaar Account ID that you can use in _getAccountDetails_ call.

```php
print_r(Vzaar::getUserDetails('VZAAR_USERNAME'));
```

Where _VZAAR_USERNAME_ is the vzaar username. Result of this call will be an object of UserDetails type.

####Account Details

>This API call returns the details and rights for each vzaar subscription account type along with it's relevant metadata. This will show the details of the packages available here: [http://vzaar.com/pricing](http://vzaar.com/pricing)

```php
print_r(Vzaar::getAccountDetails(VZAAR_ACCOUNT_ID));
```

Where _VZAAR_ACCOUNT_ID_ is the unique account id assigned by vzaar.

Result of this call will be an object of AccountDetails type.

####Video List

>This API call returns a list of the user's active videos along with it's relevant metadata. 20 videos are returned by default, but this is customizable.

```php
print_r(Vzaar::getVideoList('VZAAR_USERNAME', true, 10));
```

####Video Details

>This API call returns metadata about selected video, like its dimensions, thumbnail information, author, duration, play count and so on.

```php
print_r(Vzaar::getVideoDetails(VZAAR_VIDEO_ID, true));
```

Where _VZAAR_VIDEO_ID_ is unique vzaar video ID assigned to a video after its processing.

####Upload Signature

>In some cases you might need to not perform actual uploading from API but to use some third-party uploaders, like S3_Upload widget, or any other, so you would need to get only upload signature for it.

```php
print_r(Vzaar::getUploadSignature());
```

####Uploading video

>Upload video from local drive directly to Amazon S3 bucket. Use this method when you build desktop apps or when you upload videos to vzaar directly from your server.

```php
$filename = '548.mov'; // the file must be located in the same directory as the script. If not use full disk path

$file = getcwd() . '\\' . $filename;
echo('file to upload: ' . $file);
$result=Vzaar::uploadVideo($file);
echo($result);
```

####Uploading thumbnails

>Upload thumbnails for a video by using the video id.

```php
$video_id = 123;

$thumb_path = "/home/herk/my_image.jpg";
echo('uploading thumbnail for video:' . $video_id . ', file path:' . $thumb_path);
$result=Vzaar::uploadThumbnail($video_id, $thumb_path);
echo($result);
```

####Uploading thumbnails

>Generate thumbnail based on frame time.

```php
$video_id = 123;

$result=Vzaar::generateThumbnail($video_id, 3);
echo($result);
```

####Uploading videos using urls

>Uploading a new video or replacing an existing one from an url

```php
$url = "http://www.mywebsite.com/my_video.mp4";
echo('uploading video from url: ' . $url);
$video_id=Vzaar::uploadLink($url);
echo($video_id);
```

####Processing video

>This API call tells the vzaar system to process a newly uploaded video. This will encode it if necessary and then provide a vzaar video ID back.

```php
$apireply = Vzaar::processVideo(GUID, VIDEO_TITLE, VIDEO_DESCRIPTION, Profile::Original);
echo($apireply)
```

You would need to pass following parameters to this API function:

* _GUID_ (string) - Specifies the guid to operate on
* _VIDEO_TITLE_ (string) - Specifies the title for the video
* _VIDEO_DESCRIPTION_ (string) - Specifies the description for the video
profile integer - Specifies the size for the video to be encoded in. If not specified, this will use the vzaar default or the user default (if set)

####Editing video

>This API call allows a user to edit or change details about a video in the system.

```php
$apiresult = Vzaar::editVideo(VIDEO_ID, VIDEO_TITLE, VIDEO_DESCRIPTION, MARK_AS_PRIVATE);
```

The following arguments should be passed to the method:

* _VIDEO_ID_ (integer) - Unique vzaar Video ID of the video you are going to modify
* _VIDEO_TITLE_ (string) - Specifies the new title for the video
* _VIDEO_DESCRIPTION_ (string) - Specifies the new description for the video
* _MARK_AS_PRIVATE_ (boolean) (true|false) - Marks the video as private or public

####Deleting video
>This API call allows you to delete a video from your account. If deletion was successful it will return you _true_ otherwise _false_.

```php
$apiresult = Vzaar::deleteVideo(VZAAR_VIDEO_ID);
```

Where VZAAR_VIDEO_ID is unique vzaar video ID assigned to a video after its processing.
