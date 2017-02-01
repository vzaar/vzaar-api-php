<?php
    namespace VzaarApi\Tests;

    use VzaarApi\Tests\VzaarTest;
    use VzaarApi\Exceptions\RecordEx;
    use VzaarApi\Client;
    use VzaarApi\Category;
    use VzaarApi\CategoriesList;

    class CategoryTest extends VzaarTest
    {
        public static $lookup;
        public static $subtree;

        public function testCategory_New()
        {

            $category = new Category();

            $class = new \ReflectionClass($category);
            $endpoint = $class->getProperty('endpoint');
            $endpoint->setAccessible(true);

            $this->assertEquals('/categories',$endpoint->getValue());
            $this->assertInstanceOf(Client::class, $category->getClient());

        }

        public function testCategory_Parameter()
        {

            $client = new Client();
            $category = new Category($client);

            $class = new \ReflectionClass($category);
            $endpoint = $class->getProperty('endpoint');
            $endpoint->setAccessible(true);

            $this->assertEquals('/categories',$endpoint->getValue());
            $this->assertInstanceOf(Client::class, $category->getClient());

        }

        public function testCategory_find()
        {
            $callback = function($recordRequest) {

                $this->assertEquals('GET',$recordRequest['method']);
                $this->assertEquals('/categories', $recordRequest['endpoint']);

                $this->assertEquals(1, $recordRequest['recordPath']);

                return \json_decode(self::$lookup);
            };

            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));

            $category_id=1;
            $category = Category::find($category_id, $client);

            $this->assertInstanceOf(Category::class, $category);
            $this->assertNotNull($category->id);

        }

        public function testCategory_subtree()
        {
            $callback = function($recordRequest) {

                $this->assertEquals('GET',$recordRequest['method']);
                $this->assertEquals('/categories', $recordRequest['endpoint']);

                $this->assertEquals('1/subtree', $recordRequest['recordPath']);

                return \json_decode(self::$subtree);
            };

            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));

            $category = new Category($client);

            $jsondata = \json_decode(self::$lookup);

            $updateRecord = new \ReflectionMethod($category,'updateRecord');
            $updateRecord->setAccessible(true);
            $updateRecord->invoke($category ,$jsondata);

            $this->assertNotNull($category->id);

            $subtree = $category->subtree();

            $this->assertInstanceOf(Category::class, $category);
            $this->assertInstanceOf(CategoriesList::class, $subtree);

            $this->assertNotNull($category->id);

            $this->assertEquals(2, \count($subtree));

        }

        public function testCategory_create()
        {
            $callback = function($recordRequest) {

                $this->assertEquals('POST',$recordRequest['method']);
                $this->assertEquals('/categories', $recordRequest['endpoint']);

                $this->assertEmpty($recordRequest['recordPath']);

                $this->assertArrayHasKey('name', $recordRequest['recordData']);

                $this->assertEquals('Test Category', $recordRequest['recordData']['name']);

                return \json_decode(self::$lookup);
            };


            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));


            $param = array('name' => 'Test Category');

            $category = Category::create($param, $client);

            $this->assertInstanceOf(Category::class,$category);
            $this->assertNotNull($category->id);
            $this->assertNotNull($category->name);

        }

        public function testCategory_save()
        {
            $callback = function($recordRequest) {

                $this->assertEquals('PATCH',$recordRequest['method']);
                $this->assertEquals('/categories', $recordRequest['endpoint']);

                $this->assertArrayHasKey('name',$recordRequest['recordData']);

                $result = \json_decode(self::$lookup);
                $result->data->name = $recordRequest['recordData']['name'];

                return $result;
            };

            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));

            $category = new Category($client);

            $jsondata = \json_decode(self::$lookup);

            $updateRecord = new \ReflectionMethod($category,'updateRecord');
            $updateRecord->setAccessible(true);
            $updateRecord->invoke($category ,$jsondata);

            $old_value = $category->name;
            $category->name = "Arts";
            $new_value = $category->name;

            $this->assertNotEquals($new_value, $old_value);

            $category->save();

            $saved_value = $category->name;

            $this->assertNotEquals($saved_value, $old_value);
            $this->assertEquals($new_value, $saved_value);

        }



        public static function setUpBeforeClass()
        {

            self::$lookup = <<<EOD
            {
                "data": {
                    "id": 1,
                    "account_id": 1,
                    "user_id": 1,
                    "name": "Sciences",
                    "description": null,
                    "parent_id": null,
                    "depth": 0,
                    "node_children_count": 3,
                    "tree_children_count": 5,
                    "node_video_count": 3,
                    "tree_video_count": 6,
                    "created_at": "2015-04-06T22:03:24.000Z",
                    "updated_at": "2016-01-06T12:08:38.000Z"
                }
            }
EOD;

            self::$subtree=<<<EOD
            {
                "data": [
                {
                    "id": 42,
                    "account_id": 1,
                    "user_id": 1,
                    "name": "Biology",
                    "description": null,
                    "parent_id": 1,
                    "depth": 0,
                    "node_children_count": 3,
                    "tree_children_count": 5,
                    "node_video_count": 3,
                    "tree_video_count": 6,
                    "created_at": "2015-04-06T22:03:24.000Z",
                    "updated_at": "2016-01-06T12:08:38.000Z"
                },
                {
                    "id": 2,
                    "account_id": 1,
                    "user_id": 1,
                    "name": "Chemistry",
                    "description": null,
                    "parent_id": 42,
                    "depth": 0,
                    "node_children_count": 3,
                    "tree_children_count": 5,
                    "node_video_count": 3,
                    "tree_video_count": 6,
                    "created_at": "2015-04-06T22:03:24.000Z",
                    "updated_at": "2016-01-06T12:08:38.000Z"
                }
                ],
                "meta": {
                    "links": {
                        "first": "http://api.vzaar.com/api/v2/categories/42/subtree?page=1",
                        "last": "http://api.vzaar.com/api/v2/categories/42/subtree?page=4",
                        "next": "http://api.vzaar.com/api/v2/categories/42/subtree?page=2",
                        "previous": null
                    },
                    "total_count": 4
                }
            }
EOD;
        }
    }
?>
