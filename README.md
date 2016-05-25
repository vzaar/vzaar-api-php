## vzaar API PHP client
##### vzaar API client for PHP developers.

>vzaar is the go to video hosting platform for business. Affordable, customizable and secure. Leverage the power of online video and enable commerce with vzaar. For more details and signup please visit [http://vzaar.com](http://vzaar.com)

#### Using the library

In order to start using vzaar API library

```php
require_once 'Vzaar.php';

Vzaar::$token = 'VZAAR_API_TOKEN';
Vzaar::$secret = 'VZAAR_USERNAME';
```

For version > 1.3.0, if you have installed the library through Composer, you may require the Composer autoload file instead of individual Vzaar source files. Composer will automatically find and load your classes when used:

```php
require_once 'vendor/autoload.php';

Vzaar::$token = 'VZAAR_API_TOKEN';
Vzaar::$secret = 'VZAAR_USERNAME';
```

In order to use the vzaar API, you need to have a valid username and API token that you can get from your vzaar dashboard at [http://app.vzaar.com/settings/api](http://app.vzaar.com/settings/api)

To check you can connect via the API, you can run the following command:

```php
Vzaar::whoAmI();
```

If it returns your vzaar username, then you're good to go.

#### User Details

This API call returns the user's public details along with relevant metadata.

```php
Vzaar::getUserDetails('VZAAR_USERNAME');
```

#### Video List

This API call returns a list of the user's active videos along with relevant metadata. 20 videos are returned by default, but this is customizable.

```php
Vzaar::getVideoList('VZAAR_USERNAME', true, 10);
```

In this example, the `true` parameter says that the API call should be authenticated. If you have your API settings set to 'private', then you will need to be authenticated.

#### Video Details

This API call returns metadata about the selected video (dimensions, thumbnail information, author, duration, play count, etc).

```php
Vzaar::getVideoDetails(VZAAR_VIDEO_ID, true);
```

In this case, _VZAAR_VIDEO_ID_ is the unique vzaar video ID assigned to a video after its processing.

#### Special characters in filenames

As per the AWS S3 documentation, only a small number of special characters in filenames are supported: http://docs.aws.amazon.com/AmazonS3/latest/dev/UsingMetadata.html

The following special characters are supported by the vzaar API:

- a-z
- A-Z
- 0-9
- Space
- - (dash)
- . (dot)
- ! (exclamation)
- () (braces)

#### Upload Signature

If you are performing your own uploading (e.g. a 3rd-party or custom uploader) you will need to generate an S3 upload signature. You can then use this in your custom uploader.

```php
Vzaar::getUploadSignature(null, '/tmp/video.mp4', true, 'video.mp4', 102400);
```

##### Note
As of version *1.3.0* the method signature for `getUploadSignature` has changed. The new method
expects additional arguments which are required to support multipart uploads.

### Uploading and processing videos

Getting a video into your vzaar account is a two step process; you must first upload and then process the video.

#### Uploading videos from the filesystem

Use this method when you build desktop apps or when you upload videos to vzaar directly from your server.

```php
$guid = Vzaar::uploadVideo("/path/to/file/video.mp4");
Vzaar::processVideo($guid, "Title", "Description", "labels"));
```

#### Uploading videos using a url

Uploading a new video or replacing an existing one from a url

```php
$url = "http://www.mywebsite.com/my_video.mp4";
Vzaar::uploadLink($url, "Title");
```

#### Processing videos

This API call tells the vzaar system to process a newly uploaded video. This will encode it if necessary and then provide a vzaar video ID back.

Typically you only need to do this when performing your own uploads (see _Upload Signature_).

```php
Vzaar::processVideo(GUID, TITLE, DESCRIPTION, LABELS, PROFILE);
```

* `GUID` (string) - Specifies the guid to operate on. Get this from the result of your `getUploadSignature` API call.
* `TITLE` (string) - Specifies the title for the video.
* `DESCRIPTION` (string) - Specifies the description for the video.
* `LABELS` (string) - Comma separated list of labels to be assigned to the video.
* `PROFILE` (integer) - Specifies the size for the video to be encoded in. If not specified, this will use the vzaar default or the user default (if set). See `src/Vzaar.php` for options.


#### Uploading thumbnails

Upload thumbnails for a video by using the video id.

```php
$video_id = 123;
$thumb_path = "/path/to/file/image.jpg";
Vzaar::uploadThumbnail($video_id, $thumb_path);
```

#### Uploading thumbnails

Generate a thumbnail based on frame time.

```php
$video_id = 123;
Vzaar::generateThumbnail($video_id, 3);
```


#### Editing video

This API call allows a user to edit or change details about a video.

```php
Vzaar::editVideo(VIDEO_ID, VIDEO_TITLE, VIDEO_DESCRIPTION, MARK_AS_PRIVATE);
```

The following arguments should be passed to the method:

* `VIDEO_ID` (integer) - Unique vzaar Video ID of the video you are going to modify
* `VIDEO_TITLE` (string) - Specifies the new title for the video
* `VIDEO_DESCRIPTION` (string) - Specifies the new description for the video
* `MARK_AS_PRIVATE` (boolean) (true|false) - Marks the video as private or public


#### Deleting video

This API call allows you to delete a video from your account. If deletion was successful it will return _true_ otherwise _false_.

```php
Vzaar::deleteVideo(VZAAR_VIDEO_ID);
```


### License

Released under the [MIT License](http://www.opensource.org/licenses/MIT).
