<?php
    namespace VzaarApi\Tests;
    
    use VzaarApi\Tests\VzaarTest;
    use VzaarApi\Tests\Fixtures\DummyList;
    use VzaarApi\Tests\Fixtures\DummyRecord;
    use VzaarApi\Exceptions\RecordEx;
    use VzaarApi\Client;
    
    class RecordsListTest extends VzaarTest {
    
        public function testRecordList_New () {
        
            $dummy = new DummyList();
            
            $class = new \ReflectionClass($dummy);
            $endpoint = $class->getProperty('endpoint');
            $endpoint->setAccessible(true);
            $recordClass = $class->getProperty('recordClass');
            $recordClass->setAccessible(true);
            $itemCursor = $class->getProperty('itemCursor');
            $itemCursor->setAccessible(true);

            $this->assertEquals('/dummy_endpoint', $endpoint->getValue());
            $this->assertEquals(DummyRecord::class, $recordClass->getValue());
            $this->assertEquals(0, $itemCursor->getValue($dummy));

        }
        
        public function testRecordList_updateRecord() {
            
            $dummy = new DummyList();
            
            $data = \json_decode(DummyList::$list);
            
            $updateRecord = new \ReflectionMethod($dummy,'updateRecord');
            $updateRecord->setAccessible(true);
            $updateRecord->invoke($dummy ,$data);
            
            $class = new \ReflectionClass($dummy);
            $recordData = $class->getProperty('recordData');
            $recordData->setAccessible(true);
            
            $this->assertObjectHasAttribute('data', $recordData->getValue($dummy));
            $this->assertObjectHasAttribute('meta', $recordData->getValue($dummy));
            $this->assertInstanceOf(DummyRecord::class, $recordData->getValue($dummy)->data[0]);
        
        }
        
        /**
         * @expectedException         VzaarApi\Exceptions\RecordEx
         * @expectedExceptionMessage  The property is readonly
         */
        public function testRecordList_magic_set() {
            
            $dummy = new DummyList();
            
            $dummy->total_count = 44;
            
        }
        
        /**
         * @expectedException         VzaarApi\Exceptions\RecordEx
         * @expectedExceptionMessage  The property is readonly
         */
        public function testRecordList_magic_unset() {
            
            $dummy = new DummyList();
            
            unset($dummy->total_count);
            
        }
        
        public function testRecordList_magic() {
        
            $dummy = new DummyList();
            
            $data = \json_decode(DummyList::$list);
            
            $updateRecord = new \ReflectionMethod($dummy,'updateRecord');
            $updateRecord->setAccessible(true);
            $updateRecord->invoke($dummy ,$data);
            
            //__isset()
            $this->assertTrue(isset($dummy->total_count));
            $this->assertTrue(isset($dummy->links));
            $this->assertTrue(isset($dummy->first));
            $this->assertTrue(isset($dummy->next));
            $this->assertTrue(isset($dummy->previous));
            $this->assertTrue(isset($dummy->last));
            
            //__get()
            $this->assertEquals(2, $dummy->total_count);
        
        }
        
        public function testRecordList_count() {
        
            $dummy = new DummyList();
            
            $data = \json_decode(DummyList::$list);
            
            $updateRecord = new \ReflectionMethod($dummy,'updateRecord');
            $updateRecord->setAccessible(true);
            $updateRecord->invoke($dummy ,$data);
            
            $this->assertEquals(2, \count($dummy));
        
        }
        
        public function testRecordList_paginate() {
            
            $callback = function($recordRequest) {
                
                $this->assertEquals('GET',$recordRequest['method']);
                $this->assertEquals('/dummy_endpoint', $recordRequest['endpoint']);
                $this->assertEmpty($recordRequest['recordPath']);
                $this->assertEmpty($recordRequest['recordQuery']);
                $this->assertEmpty($recordRequest['recordData']);
                
                return \json_decode(DummyList::$list);
                
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $dummy = DummyList::paginate(null,$client);
            
            $class = new \ReflectionClass($dummy);
            $recordData = $class->getProperty('recordData');
            $recordData->setAccessible(true);
            
            $this->assertObjectHasAttribute('data', $recordData->getValue($dummy));
            $this->assertObjectHasAttribute('meta', $recordData->getValue($dummy));
            $this->assertInstanceOf(DummyRecord::class, $recordData->getValue($dummy)->data[0]);
            $this->assertEquals(2, \count($dummy));
            
        }
        
        public function testRecordList_getPage() {
            
            
            $callback = function($recordRequest) {
                
                $this->assertEquals('GET',$recordRequest['method']);
                $this->assertEquals('/dummy_endpoint', $recordRequest['endpoint']);
                $this->assertEmpty($recordRequest['recordPath']);
                $this->assertNotEmpty($recordRequest['recordQuery']);
                $this->assertEmpty($recordRequest['recordData']);
                
                $this->assertArrayHasKey('page', $recordRequest['recordQuery']);
                $this->assertArrayHasKey('state', $recordRequest['recordQuery']);
                $this->assertArrayHasKey('order', $recordRequest['recordQuery']);
                
                $this->assertEquals('asc', $recordRequest['recordQuery']['order']);
                $this->assertEquals('1', $recordRequest['recordQuery']['page']);
                $this->assertEquals('ready', $recordRequest['recordQuery']['state']);
                
                return \json_decode(DummyList::$list);
                
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $dummy = new DummyList($client);
            
            $url = 'https://api.vzaar.com/api/v2/dummy_endpoint?order=asc&page=1&state=ready';
            
            $getPage = new \ReflectionMethod($dummy,'getPage');
            $getPage->setAccessible(true);
            $getPage->invoke($dummy ,$url);
        
        
        }
        
        public function testRecordList_firstPage() {
            
            $callback = function($recordRequest) {
                
                $this->assertEquals('1', $recordRequest['recordQuery']['page']);
                
                return \json_decode(DummyList::$list);
                
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $dummy = new DummyList($client);
            
            $data = \json_decode(DummyList::$list);
            
            $updateRecord = new \ReflectionMethod($dummy,'updateRecord');
            $updateRecord->setAccessible(true);
            $updateRecord->invoke($dummy ,$data);
            
            $this->assertTrue($dummy->firstPage());
            
        }
        
        public function testRecordList_firstPage_null() {
            
            $dummy = new DummyList();
            
            $data = \json_decode(DummyList::$list);
            $data->meta->links->first = null;
            
            $updateRecord = new \ReflectionMethod($dummy,'updateRecord');
            $updateRecord->setAccessible(true);
            $updateRecord->invoke($dummy ,$data);
            
            $this->assertNotTrue($dummy->firstPage());
            
        }
        
        public function testRecordList_nextPage() {
            
            $callback = function($recordRequest) {
                
                $this->assertEquals('4', $recordRequest['recordQuery']['page']);
                
                return \json_decode(DummyList::$list);
                
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $dummy = new DummyList($client);
            
            $data = \json_decode(DummyList::$list);
            
            $updateRecord = new \ReflectionMethod($dummy,'updateRecord');
            $updateRecord->setAccessible(true);
            $updateRecord->invoke($dummy ,$data);
            
            $this->assertTrue($dummy->nextPage());
            
        }
        
        public function testRecordList_nextPage_null() {
            
            $dummy = new DummyList();
            
            $data = \json_decode(DummyList::$list);
            $data->meta->links->next = null;
            
            $updateRecord = new \ReflectionMethod($dummy,'updateRecord');
            $updateRecord->setAccessible(true);
            $updateRecord->invoke($dummy ,$data);
            
            $this->assertNotTrue($dummy->nextPage());
            
        }
        
        public function testRecordList_previousPage() {
            
            $callback = function($recordRequest) {
                
                $this->assertEquals('2', $recordRequest['recordQuery']['page']);
                
                return \json_decode(DummyList::$list);
                
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $dummy = new DummyList($client);
            
            $data = \json_decode(DummyList::$list);
            
            $updateRecord = new \ReflectionMethod($dummy,'updateRecord');
            $updateRecord->setAccessible(true);
            $updateRecord->invoke($dummy ,$data);
            
            $this->assertTrue($dummy->previousPage());
            
        }
        
        public function testRecordList_previousPage_null() {
            
            $dummy = new DummyList();
            
            $data = \json_decode(DummyList::$list);
            $data->meta->links->previous = null;
            
            $updateRecord = new \ReflectionMethod($dummy,'updateRecord');
            $updateRecord->setAccessible(true);
            $updateRecord->invoke($dummy ,$data);
            
            $this->assertNotTrue($dummy->previousPage());
            
        }
        
        public function testRecordList_lastPage() {
            
            $callback = function($recordRequest) {
                
                $this->assertEquals('5', $recordRequest['recordQuery']['page']);
                
                return \json_decode(DummyList::$list);
                
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $dummy = new DummyList($client);
            
            $data = \json_decode(DummyList::$list);
            
            $updateRecord = new \ReflectionMethod($dummy,'updateRecord');
            $updateRecord->setAccessible(true);
            $updateRecord->invoke($dummy ,$data);
            
            $this->assertTrue($dummy->lastPage());
            
        }
        
        public function testRecordList_lastPage_null() {
            
            $dummy = new DummyList();
            
            $data = \json_decode(DummyList::$list);
            $data->meta->links->last = null;
            
            $updateRecord = new \ReflectionMethod($dummy,'updateRecord');
            $updateRecord->setAccessible(true);
            $updateRecord->invoke($dummy ,$data);
            
            $this->assertNotTrue($dummy->lastPage());
            
        }
        
        public function testRecordList_each_item() {
        
            
            $callback = function($recordRequest) {
                
                $result = \json_decode(DummyList::$list);
                //reset 'next' link
                $result->meta->links->next = null;
                
                return $result;
                
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            foreach(DummyList::each_item(null, $client) as $dummy) {
            
                $this->assertInstanceOf(DummyRecord::class, $dummy);
                
            }
        
        }
        
        public function testRecordList_iterator() {
        
        
            $dummy = new DummyList();
            
            $data = \json_decode(DummyList::$list);
            
            $updateRecord = new \ReflectionMethod($dummy,'updateRecord');
            $updateRecord->setAccessible(true);
            $updateRecord->invoke($dummy ,$data);
            
            foreach($dummy as $item) {
                
                $this->assertInstanceOf(DummyRecord::class, $item);
            
            }
            
        }
    
    }
    
?>
