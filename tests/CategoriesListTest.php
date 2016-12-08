<?php
    namespace VzaarApi\Tests;
    
    use VzaarApi\Tests\VzaarTest;
    use VzaarApi\CategoriesList;
    use VzaarApi\Category;
    use VzaarApi\Client;
    
    class CategoriesListTest extends VzaarTest {
        
        public static $list;
        
        public function testCategoriesList_New () {
            
            $categories = new CategoriesList();
            
            $class = new \ReflectionClass($categories);
            $endpoint = $class->getProperty('endpoint');
            $endpoint->setAccessible(true);
            $recordClass = $class->getProperty('recordClass');
            $recordClass->setAccessible(true);
            
            $this->assertEquals('/categories', $endpoint->getValue());
            $this->assertEquals(Category::class, $recordClass->getValue());
            
            $this->assertInstanceOf(CategoriesList::class, $categories);
            
        }
        
        public function testCategoriesList_subtree() {
            
            $callback = function($recordRequest) {
                
                $this->assertEquals('GET',$recordRequest['method']);
                $this->assertEquals('/categories', $recordRequest['endpoint']);
                $this->assertEquals('42/subtree', $recordRequest['recordPath']);
                
                return \json_decode(self::$list);
                
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $subtree = CategoriesList::subtree(42,null,$client);
            
            $class = new \ReflectionClass($subtree);
            $recordData = $class->getProperty('recordData');
            $recordData->setAccessible(true);
            
            $this->assertInstanceOf(Category::class, $recordData->getValue($subtree)->data[0]);
            $this->assertEquals(2, \count($subtree));
            
        }
        
        public static function setUpBeforeClass()
        {
            self::$list=<<<EOD
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
