vzaar API PHP client
---
vzaar API client for PHP developers.

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

In order to use the vzaar API, you need to have a valid username and API token that you can get from your vzaar dashboard at [http://app.vzaar.com/settings/api](http://app.vzaar.com/settings/api)

To check you can connect via the API, you can run the following command:

```php
echo(Vzaar::whoAmI());
```

If it returns you your vzaar username, then you're good to go.

####User Details

This API call returns the user's public details along with relevant metadata.

```php
print_r(Vzaar::getUserDetails('VZAAR_USERNAME'));
```

Where _VZAAR__USERNAME_ is the vzaar username. Result of this call will be an object of UserDetails type.


####Video List

This API call returns a list of the user's active videos along with relevant metadata. 20 videos are returned by default, but this is customizable.

```php
print_r(Vzaar::getVideoList('VZAAR_USERNAME', true, 10));
```

In this example, the `true` parameter says that the API call should be authenticated. If you have your API settings set to 'private', then you will need to be authenticated.

####Video Details

This API call returns metadata about the selected video, like its dimensions, thumbnail information, author, duration, play count and so on.

```php
print_r(Vzaar::getVideoDetails(VZAAR_VIDEO_ID, true));
```

In this case, VZAAR_VIDEO_ID_ is the unique vzaar video ID assigned to a video after its processing.

####Upload Signature

In some cases you might need to not perform actual uploading from API but to use some third-party uploaders, like S3_Upload widget or similar, so you would need to get only upload signature for it.

```php
print_r(Vzaar::getUploadSignature());
```

###Uploading and processing videos

Getting a video into your vzaar account is a two step process; you must first upload and then process the video.

####Uploading videos from the filesystem

Upload video from local drive directly to Amazon S3 bucket. Use this method when you build desktop apps or when you upload videos to vzaar directly from your server.

```php
$filename = '548.mov'; // the file must be located in the same directory as the script. If not use full disk path.

$file = getcwd() . '\\' . $filename;
echo('file to upload: ' . $file);
$result=Vzaar::uploadVideo($file);
echo($result);
```

####Uploading videos using a url

Uploading a new video or replacing an existing one from a url

```php
$url = "http://www.mywebsite.com/my_video.mp4";
echo('uploading video from url: ' . $url);
$video_id=Vzaar::uploadLink($url);
echo($video_id);
```

####Processing videos

This API call tells the vzaar system to process a newly uploaded video. This will encode it if necessary and then provide a vzaar video ID back.

```php
$apireply = Vzaar::processVideo(GUID, VIDEO_TITLE, VIDEO_DESCRIPTION, VIDEO_LABELS, Profile::Original);
echo($apireply)
```

You would need to pass following parameters to this API function:

* _GUID_ (string) - Specifies the guid to operate on. This should have been returned from the upload operation.
* _VIDEO_TITLE_ (string) - Specifies the title for the video
* _VIDEO_DESCRIPTION_ (string) - Specifies the description for the video
* _PROFILE_ (integer) - Specifies the size for the video to be encoded in. If not specified, this will use the vzaar default or the user default (if set)
* _VIDEO_LABELS_ (string) - Comma separated list of labels to be assigned to the video


####Uploading thumbnails

Upload thumbnails for a video by using the video id.

```php
$video_id = 123;

$thumb_path = "/home/herk/my_image.jpg";
echo('uploading thumbnail for video:' . $video_id . ', file path:' . $thumb_path);
$result=Vzaar::uploadThumbnail($video_id, $thumb_path);
echo($result);
```

####Uploading thumbnails

Generate a thumbnail based on frame time.

```php
$video_id = 123;

$result=Vzaar::generateThumbnail($video_id, 3);
echo($result);
```


####Editing video

This API call allows a user to edit or change details about a video in the system.

```php
$apiresult = Vzaar::editVideo(VIDEO_ID, VIDEO_TITLE, VIDEO_DESCRIPTION, MARK_AS_PRIVATE);
```

The following arguments should be passed to the method:

* _VIDEO_ID_ (integer) - Unique vzaar Video ID of the video you are going to modify
* _VIDEO_TITLE_ (string) - Specifies the new title for the video
* _VIDEO_DESCRIPTION_ (string) - Specifies the new description for the video
* _MARK_AS_PRIVATE_ (boolean) (true|false) - Marks the video as private or public


####Deleting video
This API call allows you to delete a video from your account. If deletion was successful it will return you _true_ otherwise _false_.

```php
$apiresult = Vzaar::deleteVideo(VZAAR_VIDEO_ID);
```


### License

Released under the [MIT License](http://www.opensource.org/licenses/MIT).
