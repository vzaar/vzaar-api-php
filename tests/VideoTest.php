<?php
    namespace VzaarApi\Tests;

    use VzaarApi\Tests\VzaarTest;
    use VzaarApi\Exceptions\RecordEx;
    use VzaarApi\Resources\S3Client;
    use VzaarApi\Resources\HttpCurl;
    use VzaarApi\Client;
    use VzaarApi\Video;

    class VideoTest extends VzaarTest
    {
        public static $lookup;
        public static $single;

        public function testVideo_New()
        {

            $video = new Video();

            $class = new \ReflectionClass($video);
            $endpoint = $class->getProperty('endpoint');
            $endpoint->setAccessible(true);
            $s3client = $class->getProperty('s3client');
            $s3client->setAccessible(true);

            $this->assertEquals('/videos',$endpoint->getValue());
            $this->assertInstanceOf(Client::class, $video->getClient());
            $this->assertInstanceOf(S3Client::class, $s3client->getValue($video));

        }

        public function testVideo_Parameter()
        {

            $client = new Client();
            $video = new Video($client);

            $class = new \ReflectionClass($video);
            $endpoint = $class->getProperty('endpoint');
            $endpoint->setAccessible(true);

            $this->assertEquals('/videos',$endpoint->getValue());
            $this->assertInstanceOf(Client::class, $video->getClient());

        }

        public function testVideo_find()
        {
            $callback = function($recordRequest) {

                $this->assertEquals('GET',$recordRequest['method']);
                $this->assertEquals('/videos', $recordRequest['endpoint']);

                $this->assertEquals(7574853, $recordRequest['recordPath']);

                return \json_decode(self::$lookup);
            };

            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));

            $video_id=7574853;
            $video = Video::find($video_id, $client);

            $this->assertInstanceOf(Video::class,$video);
            $this->assertNotNull($video->id);

        }

        public function testVideo_create_from_guid()
        {
            $callback = function($recordRequest) {

                $this->assertEquals('POST',$recordRequest['method']);
                $this->assertEquals('/videos', $recordRequest['endpoint']);

                $this->assertEmpty($recordRequest['recordPath']);

                $this->assertArrayHasKey('guid', $recordRequest['recordData']);
                $this->assertArrayHasKey('title', $recordRequest['recordData']);

                $this->assertEquals("vz91e80db09a494467b265f0c327950825", $recordRequest['recordData']['guid']);
                $this->assertEquals('Test Video', $recordRequest['recordData']['title']);

                return \json_decode(self::$lookup);
            };


            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));


            $param = array('guid' => "vz91e80db09a494467b265f0c327950825",
                           'title' => 'Test Video');

            $video = Video::create($param, $client);

            $this->assertInstanceOf(Video::class,$video);
            $this->assertNotNull($video->id);

        }

        public function testVideo_create_from_file()
        {
            $callback = function($recordRequest) {

                if($recordRequest['endpoint'] == '/signature/single/2')
                    return \json_decode(self::$single);

                $this->assertEquals('POST',$recordRequest['method']);
                $this->assertEquals('/videos', $recordRequest['endpoint']);

                $this->assertEmpty($recordRequest['recordPath']);

                $this->assertArrayHasKey('guid', $recordRequest['recordData']);

                return \json_decode(self::$lookup);
            };

            $callback_handler = function($recordRequest) {

                $result = array('httpCode' => 201);

                return $result;
            };

            $handler = $this->createMock(HttpCurl::class);
            $handler->method('httpRequest')
            ->will($this->returnCallback($callback_handler, $this->returnArgument(0)));

            $s3client = new S3Client($handler);

            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));


            $filepath = 'movie.mp4';

            //cleanup before
            if(file_exists($filepath))
                unlink($filepath);

            //create test file
            file_put_contents($filepath,'filecontent');

            $param = array('filepath' => $filepath);

            $video = Video::create($param, $client, $s3client);

            //cleanup after
            if(file_exists($filepath))
                unlink($filepath);

            //clear file caches
            clearstatcache(true, $filepath);

            $this->assertInstanceOf(Video::class,$video);
            $this->assertNotNull($video->id);

        }

        public function testVideo_create_from_url()
        {

            $callback = function($recordRequest) {

                $this->assertEquals('POST',$recordRequest['method']);
                $this->assertEquals('/link_uploads', $recordRequest['endpoint']);

                $this->assertEmpty($recordRequest['recordPath']);

                $this->assertArrayHasKey('url', $recordRequest['recordData']);
                $this->assertArrayHasKey('uploader', $recordRequest['recordData']);
                $this->assertArrayHasKey('title', $recordRequest['recordData']);

                $this->assertEquals('Test Video', $recordRequest['recordData']['title']);
                $this->assertEquals('https://example.com/video.mov', $recordRequest['recordData']['url']);

                return \json_decode(self::$lookup);
            };


            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));


            $param = array('url' => 'https://example.com/video.mov',
                           'title' => 'Test Video');

            $video = Video::create($param, $client);

            $this->assertInstanceOf(Video::class,$video);
            $this->assertNotNull($video->id);

        }

        /**
         * @expectedException         VzaarApi\Exceptions\ArgumentTypeEx
         * @expectedExceptionMessage  Only one of the parameters: guid or url or filepath expected
         */
        public function testVideo_create_Ex1()
        {

            $param = array('url' => 'https://example.com/video.mov',
                           'guid' => "vz91e80db09a494467b265f0c327950825",
                           'title' => 'Test Video');

            $video = Video::create($param);

        }

        /**
         * @expectedException         VzaarApi\Exceptions\ArgumentTypeEx
         * @expectedExceptionMessage  Only one of the parameters: guid or url or filepath expected
         */
        public function testVideo_create_Ex2()
        {

            $param = array('url' => 'https://example.com/video.mov',
                           'guid' => "vz91e80db09a494467b265f0c327950825",
                           'filepath' => 'video.mp4',
                           'title' => 'Test Video');

            $video = Video::create($param);

        }

        /**
         * @expectedException         VzaarApi\Exceptions\ArgumentTypeEx
         * @expectedExceptionMessage  Only one of the parameters: guid or url or filepath expected
         */
        public function testVideo_create_Ex3()
        {

            $param = array('url' => 'https://example.com/video.mov',
                           'filepath' => 'video.mp4',
                           'title' => 'Test Video');

            $video = Video::create($param);

        }

        /**
         * @expectedException         VzaarApi\Exceptions\ArgumentTypeEx
         * @expectedExceptionMessage  Only one of the parameters: guid or url or filepath expected
         */
        public function testVideo_create_Ex4()
        {

            $param = array('guid' => "vz91e80db09a494467b265f0c327950825",
                           'filepath' => 'video.mp4',
                           'title' => 'Test Video');

            $video = Video::create($param);

        }

        /**
         * @expectedException         VzaarApi\Exceptions\ArgumentTypeEx
         * @expectedExceptionMessage  Only one of the parameters: guid or url or filepath expected
         */
        public function testVideo_create_Ex5()
        {

            $param = array('title' => 'Test Video');

            $video = Video::create($param);

        }

        /**
         * @expectedException         VzaarApi\Exceptions\ArgumentTypeEx
         * @expectedExceptionMessage  Only one of the parameters: guid or url or filepath expected
         */
        public function testVideo_create_Ex6()
        {

            $param = array();

            $video = Video::create($param);

        }

        /**
         * @expectedException         VzaarApi\Exceptions\ArgumentTypeEx
         * @expectedExceptionMessage  Parameter should be an array
         */
        public function testVideo_create_Ex7()
        {

            $param = null;

            $video = Video::create($param);

        }

        /**
         * @expectedException         VzaarApi\Exceptions\ArgumentValueEx
         * @expectedExceptionMessage  File does not exist: movie_fake.mp4
         */
        public function testVideo_create_Ex8() {

            $filepath = 'movie_fake.mp4';

            $param = array('filepath' => $filepath);

            $video = Video::create($param);

        }

        public function testVideo_save()
        {
            $callback = function($recordRequest) {

                $this->assertEquals('PATCH',$recordRequest['method']);
                $this->assertEquals('/videos', $recordRequest['endpoint']);

                $this->assertArrayHasKey('title',$recordRequest['recordData']);

                $result = \json_decode(self::$lookup);
                $result->data->title = $recordRequest['recordData']['title'];

                return $result;
            };

            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));

            $video = new Video($client);

            $jsondata = \json_decode(self::$lookup);

            $updateRecord = new \ReflectionMethod($video,'updateRecord');
            $updateRecord->setAccessible(true);
            $updateRecord->invoke($video ,$jsondata);

            $old_value = $video->title;
            $video->title = !$old_value;
            $new_value = $video->title;

            $this->assertNotEquals($new_value, $old_value);

            $video->save();

            $saved_value = $video->title;

            $this->assertNotEquals($saved_value, $old_value);
            $this->assertEquals($new_value, $saved_value);

        }

        /**
         * @expectedException         VzaarApi\Exceptions\RecordEx
         * @expectedExceptionMessage  Record corrupted, missing id
         */
        public function testVideo_save_params_Ex1() {

            $params = array('id' => 1,
                            'title' => "Mocked Video");

            $video = new Video();
            $id = isset($video->id) ? $video->id : null;

            $this->assertNull($id);

            $video->save($params);

        }


        public function testVideo_save_params() {

            $params = array('name' => "Mocked Video",
                            'title' => "title");

            $callback = function($recordRequest) {

                $this->assertEquals(1, $recordRequest['recordPath']);

                $result = \json_decode(self::$lookup);

                return $result;
            };

            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));

            $video = new Video($client);
            $video->id = 1;

            $video->save($params);

        }

        public function testVideo_delete()
        {
            $callback = function($recordRequest) {

                $this->assertEquals('DELETE',$recordRequest['method']);
                $this->assertEquals('/videos', $recordRequest['endpoint']);

                $this->assertNotEmpty($recordRequest['recordPath']);
                $this->assertEmpty($recordRequest['recordQuery']);
                $this->assertEmpty($recordRequest['recordData']);

                return true;
            };


            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));

            $video = new Video($client);

            $jsondata = \json_decode(self::$lookup);

            $updateRecord = new \ReflectionMethod($video,'updateRecord');
            $updateRecord->setAccessible(true);
            $updateRecord->invoke($video ,$jsondata);

            $video->delete();

            $id = isset($video->id) ? $video->id : null;

            $this->assertNull($id);

        }

        public function testVideoDelete_Params(){

            $callback = function($recordRequest) {

                $this->assertEquals(1, $recordRequest['recordPath']);

                return true;
            };

            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));

            $video = new Video($client);
            $video->id = 1;

            $video->delete();

        }

        public static function setUpBeforeClass()
        {

            self::$lookup = <<<EOD
            {
                "data": {
                    "id": 7574853,
                    "title": "multipart",
                    "user_id": 42,
                    "account_id": 1,
                    "description": null,
                    "duration": 66.7,
                    "created_at": "2016-11-11T11:36:26.000Z",
                    "updated_at": "2016-11-11T11:37:36.000Z",
                    "private": false,
                    "seo_url": "http://example.com/video.mp4",
                    "url": null,
                    "state": "ready",
                    "thumbnail_url": "https://view.vzaar.com/7574853/thumb",
                    "embed_code": "<iframe id=\"vzvd-7574853\" name=\"vzvd-7574853\" title=\"video player\" class=\"video-player\" type=\"text/html\" width=\"448\" height=\"278\" frameborder=\"0\" allowfullscreen allowTransparency=\"true\" mozallowfullscreen webkitAllowFullScreen src=\"//view.vzaar.com/7574853/player\"></iframe>",
                    "renditions": [
                    {
                        "id": 66,
                        "width": 416,
                        "height": 258,
                        "bitrate": 200,
                        "framerate": "12.0",
                        "status": "finished",
                        "size_in_bytes": 12345
                    }
                    ],
                    "legacy_renditions": [
                    {
                        "id": 10567122,
                        "type": "standard",
                        "width": 448,
                        "height": 278,
                        "bitrate": 512,
                        "status": "Finished"
                    }
                    ]
                }
            }
EOD;

            self::$single = <<<'EOD'
            {
                "data": {
                    "access_key_id": "<access-key-id>",
                    "key": "vzaar/vz9/1e8/source/vz91e80db09a494467b265f0c327950825/${filename}",
                    "acl": "private",
                    "policy": "<signed-policy-string>",
                    "signature": "<signature-string>",
                    "success_action_status": "201",
                    "content_type": "binary/octet-stream",
                    "guid": "vz91e80db09a494467b265f0c327950825",
                    "bucket": "vzaar-upload-development",
                    "upload_hostname": "https://vzaar-upload-development.s3.amazonaws.com"
                }
            }
EOD;

        }
    }
?>
