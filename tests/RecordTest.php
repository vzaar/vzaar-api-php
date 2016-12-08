<?php
    namespace VzaarApi\Tests;
    
    use VzaarApi\Tests\VzaarTest;
    use VzaarApi\Tests\Fixtures\DummyRecord;
    use VzaarApi\Exceptions\VzaarError;
    use VzaarApi\Exceptions\FunctionArgumentEx;
    use VzaarApi\Exceptions\RecordEx;
    use VzaarApi\Resources\Record;
    use VzaarApi\Client;
    
    class RecordTest extends VzaarTest
    {
        
        public function testRecord_New() {
        
            $dummy = new DummyRecord();
            
            $class = new \ReflectionClass($dummy);
            $endpoint = $class->getProperty('endpoint');
            $endpoint->setAccessible(true);
            $recordBody = $class->getProperty('recordBody');
            $recordBody->setAccessible(true);
            $recordQuery = $class->getProperty('recordQuery');
            $recordQuery->setAccessible(true);
            $recordPath = $class->getProperty('recordPath');
            $recordPath->setAccessible(true);
            $recordData = $class->getProperty('recordData');
            $recordData->setAccessible(true);
            $httpClient = $class->getProperty('httpClient');
            $httpClient->setAccessible(true);
            
            
            $this->assertInstanceOf(Client::class, $httpClient->getValue($dummy));
            $this->assertEquals('/dummy_endpoint',$endpoint->getValue());
            $this->assertEmpty($recordBody->getValue($dummy));
            $this->assertEmpty($recordQuery->getValue($dummy));
            $this->assertEmpty($recordPath->getValue($dummy));
            $this->assertInstanceOf(\stdClass::class, $recordData->getValue($dummy));
            $this->assertInstanceOf(\stdClass::class, $recordData->getValue($dummy)->data);
            $this->assertEmpty((array)$recordData->getValue($dummy)->data);
        
        }
        
        public function testRecord_New_Param() {
            
            $dummy = new DummyRecord(new Client());
            
            $class = new \ReflectionClass($dummy);
            $endpoint = $class->getProperty('endpoint');
            $endpoint->setAccessible(true);
            $recordBody = $class->getProperty('recordBody');
            $recordBody->setAccessible(true);
            $recordQuery = $class->getProperty('recordQuery');
            $recordQuery->setAccessible(true);
            $recordPath = $class->getProperty('recordPath');
            $recordPath->setAccessible(true);
            $recordData = $class->getProperty('recordData');
            $recordData->setAccessible(true);
            $httpClient = $class->getProperty('httpClient');
            $httpClient->setAccessible(true);
            
            
            $this->assertInstanceOf(Client::class, $httpClient->getValue($dummy));
            $this->assertEquals('/dummy_endpoint',$endpoint->getValue());
            $this->assertEmpty($recordBody->getValue($dummy));
            $this->assertEmpty($recordQuery->getValue($dummy));
            $this->assertEmpty($recordPath->getValue($dummy));
            $this->assertInstanceOf(\stdClass::class, $recordData->getValue($dummy));
            $this->assertInstanceOf(\stdClass::class, $recordData->getValue($dummy)->data);
            $this->assertEmpty((array)$recordData->getValue($dummy)->data);
            
        }
        
        /**
         * @expectedException         VzaarApi\Exceptions\FunctionArgumentEx
         * @expectedExceptionMessage  Parameter should be instance of VzaarApi\Client
         */
        public function testRecord_New_Ex1() {
            
            $client = new \stdClass();
            new DummyRecord($client);
        }
        
        
        public function testRecord_updateRecord() {
            
            $data = \json_decode('{"data": {"name": "foo"}}');
            
            $dummy = new DummyRecord();
            
            $updateRecord = new \ReflectionMethod($dummy,'updateRecord');
            $updateRecord->setAccessible(true);
            $updateRecord->invoke($dummy ,$data);
            
            $class = new \ReflectionClass($dummy);
            $recordData = $class->getProperty('recordData');
            $recordData->setAccessible(true);
            
            $this->assertEquals('foo',$recordData->getValue($dummy)->data->name);
            
        }
        
        /**
         * @expectedException         VzaarApi\Exceptions\VzaarError
         * @expectedExceptionMessage  Received data are not valid
         */
        public function testRecord_updateRecord_Ex1() {
            
            $data = \json_decode('{"name": "foo"}');
            
            $dummy = new DummyRecord();
            
            $updateRecord = new \ReflectionMethod($dummy,'updateRecord');
            $updateRecord->setAccessible(true);
            $updateRecord->invoke($dummy ,$data);
            
        }
        
        /**
         * @expectedException         VzaarApi\Exceptions\FunctionArgumentEx
         * @expectedExceptionMessage  Parameter should be instance of stdClass
         */
        public function testRecord_updateRecord_Ex2() {
            
            $data = true;
            
            $dummy = new DummyRecord();
            
            $updateRecord = new \ReflectionMethod($dummy,'updateRecord');
            $updateRecord->setAccessible(true);
            $updateRecord->invoke($dummy ,$data);
            
        }
        
        /**
         * @expectedException         VzaarApi\Exceptions\FunctionArgumentEx
         * @expectedExceptionMessage  Parameter should be instance of stdClass
         */
        public function testRecord_updateRecord_Ex3() {
            
            $data = null;
            
            $dummy = new DummyRecord();
            
            $updateRecord = new \ReflectionMethod($dummy,'updateRecord');
            $updateRecord->setAccessible(true);
            $updateRecord->invoke($dummy ,$data);
            
        }

        
        public function testRecord_magic() {
            
            $dummy = new DummyRecord();
            
            $class = new \ReflectionClass($dummy);
            $recordBody = $class->getProperty('recordBody');
            $recordBody->setAccessible(true);
            $recordData = $class->getProperty('recordData');
            $recordData->setAccessible(true);
            
            
            //__isset()
            $this->assertNotTrue(isset($dummy->color));
            
            $this->assertEmpty($recordBody->getValue($dummy));
            $this->assertEmpty((array)$recordData->getValue($dummy)->data);
            
            //__set()
            $dummy->color = 'blue';
            
            $this->assertTrue(isset($dummy->color));
            
            $this->assertArrayHasKey('color',$recordBody->getValue($dummy));
            $this->assertArrayNotHasKey('color', (array)$recordData->getValue($dummy)->data);
            
            //__get()
            $color = $dummy->color;
            
            $this->assertEquals('blue', $color);
            
            //__unset
            unset($dummy->color);
            
            $this->assertNotTrue(isset($dummy->color));
            $this->assertEmpty($recordBody->getValue($dummy));
            $this->assertEmpty((array)$recordData->getValue($dummy)->data);
            
            $data = \json_decode('{"data": {"name": "foo", "wind": "cold"}}');
            
            $updateRecord = new \ReflectionMethod(DummyRecord::class, 'updateRecord');
            $updateRecord->setAccessible(true);
            $updateRecord->invoke($dummy, $data);
            
            //__isset()
            $this->assertTrue(isset($dummy->name));
            $this->assertArrayHasKey('name',(array)$recordData->getValue($dummy)->data);
            
            $this->assertNotTrue(isset($dummy->color));
            $this->assertEmpty($recordBody->getValue($dummy));
            $this->assertArrayNotHasKey('color',(array)$recordData->getValue($dummy)->data);
            
            //__set()
            $dummy->color = 'blue';
            
            $this->assertTrue(isset($dummy->name));
            $this->assertArrayHasKey('name',(array)$recordData->getValue($dummy)->data);
            
            $this->assertTrue(isset($dummy->color));
            $this->assertArrayHasKey('color',$recordBody->getValue($dummy));
            $this->assertArrayNotHasKey('color',(array)$recordData->getValue($dummy)->data);
            
            //__get()
            $color = $dummy->color;
            $name = $dummy->name;
            
            $this->assertEquals('blue', $color);
            $this->assertEquals('foo', $name);
            
            //__set()
            $dummy->name = 'bar';
            
            $this->assertEquals('blue', $dummy->color);
            $this->assertEquals('bar', $dummy->name);
            
            $this->assertArrayHasKey('color',$recordBody->getValue($dummy));
            $this->assertArrayHasKey('name',$recordBody->getValue($dummy));
            $this->assertArrayNotHasKey('color',(array)$recordData->getValue($dummy)->data);
            $this->assertArrayHasKey('name',(array)$recordData->getValue($dummy)->data);
           
            //restore the value
            $dummy->name = 'foo';
            
            $this->assertEquals('blue', $dummy->color);
            $this->assertEquals('foo', $dummy->name);
            
            $this->assertArrayHasKey('color',$recordBody->getValue($dummy));
            $this->assertArrayNotHasKey('name',$recordBody->getValue($dummy));
            $this->assertArrayNotHasKey('color',(array)$recordData->getValue($dummy)->data);
            $this->assertArrayHasKey('name',(array)$recordData->getValue($dummy)->data);
            
            //__unset
            unset($dummy->color);
            
            $this->assertNotTrue(isset($dummy->color));
            $this->assertEmpty($recordBody->getValue($dummy));
            $this->assertArrayNotHasKey('color',(array)$recordData->getValue($dummy)->data);
            $this->assertArrayHasKey('name',(array)$recordData->getValue($dummy)->data);
            
            $dummy->name = 'bar';
            
            $this->assertArrayHasKey('name',$recordBody->getValue($dummy));
            $this->assertArrayHasKey('name',(array)$recordData->getValue($dummy)->data);
            
            unset($dummy->name);
            
            $this->assertNotTrue(isset($dummy->name));
            $this->assertEmpty($recordBody->getValue($dummy));
            $this->assertArrayNotHasKey('name',(array)$recordData->getValue($dummy)->data);
            $this->assertNotEmpty((array)$recordData->getValue($dummy)->data);
            
            $this->assertTrue(isset($dummy->wind));
            unset($dummy->wind);
            
            $this->assertNotTrue(isset($dummy->wind));
            $this->assertEmpty($recordBody->getValue($dummy));
            $this->assertEmpty((array)$recordData->getValue($dummy)->data);
            
        }
        
        public function testRecord_getClient() {
        
            $dummy = new DummyRecord();
            $client = $dummy->getClient();
            
            $this->assertInstanceOf(Client::class,$client);
        
        }
        
        public function testRecord_clientChecks() {
        
            $client = $this->getMockBuilder(Client::class)
            ->setMethods(['checkRateLimit',
                         'checkRateRemaining',
                         'checkRateReset'])
            ->getMock();
            
            $client->expects($this->once())
            ->method('checkRateLimit');
            
            $client->expects($this->once())
            ->method('checkRateRemaining');
            
            $client->expects($this->once())
            ->method('checkRateReset');
            
            $dummy = new DummyRecord($client);
            
            $dummy->checkRateLimit();
            $dummy->checkRateRemaining();
            $dummy->checkRateReset();
        
        }
        
        public function testRecord_edited() {
        
            $dummy = new DummyRecord();
            
            $data = \json_decode('{"data": {"name": "foo", "wind": "cold"}}');
            
            $updateRecord = new \ReflectionMethod(DummyRecord::class, 'updateRecord');
            $updateRecord->setAccessible(true);
            $updateRecord->invoke($dummy, $data);
            
            $this->assertNotTrue($dummy->edited());
            
            $dummy->name = 'bar';
            
            $this->assertTrue($dummy->edited());
            
            //restore name value
            $dummy->name = 'foo';
            
            $this->assertNotTrue($dummy->edited());
        
        }
        
        public function testRecord_requestClient() {
            
            $callback = function($recordRequest) {
                
                $this->assertNotEmpty($recordRequest['recordPath']);
                $this->assertNotEmpty($recordRequest['recordQuery']);
                $this->assertNotEmpty($recordRequest['recordData']);
                
                return ;
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
        
            $dummy = new DummyRecord($client);
            
            $class = new \ReflectionClass($dummy);
            $recordBody = $class->getProperty('recordBody');
            $recordBody->setAccessible(true);
            $recordQuery = $class->getProperty('recordQuery');
            $recordQuery->setAccessible(true);
            $recordPath = $class->getProperty('recordPath');
            $recordPath->setAccessible(true);
            
            $body = array('body'=> 'body_test');
            $query = array('query' => 'query_test');
            $path = 'path_test';
            
            $recordBody->setValue($dummy, $body);
            $recordQuery->setValue($dummy, $query);
            $recordPath->setValue($dummy, $path);
            
            $requestClient = new \ReflectionMethod($dummy,'requestClient');
            $requestClient->setAccessible(true);
            $requestClient->invoke($dummy ,'METHOD');

            $this->assertEmpty($recordBody->getValue($dummy));
            $this->assertEmpty($recordQuery->getValue($dummy));
            $this->assertEmpty($recordPath->getValue($dummy));
        
        }
        
        /**
         * @expectedException         VzaarApi\Exceptions\RecordEx
         * @expectedExceptionMessage  Record corrupted, missing id
         */
        public function testRecord_assertRecordValid() {
        
            $dummy = new DummyRecord();
            
            $assertRecordValid = new \ReflectionMethod($dummy,'assertRecordValid');
            $assertRecordValid->setAccessible(true);
            $assertRecordValid->invoke($dummy);
        
        }
        
        public function testRecord_create()
        {
            $callback = function($recordRequest) {
                
                $this->assertEquals('POST',$recordRequest['method']);
                $this->assertEquals('/dummy_endpoint', $recordRequest['endpoint']);
                $this->assertEmpty($recordRequest['recordPath']);
                $this->assertEmpty($recordRequest['recordQuery']);
                
                $this->assertNotEmpty($recordRequest['recordData']);
                $this->assertArrayNotHasKey('id',$recordRequest['recordData']);
                
                return \json_decode(DummyRecord::$lookup);
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $params = array('name' => "Bar");
            
            $dummy = new DummyRecord($client);
            $dummy->create($params,$client);
            
            $this->assertNotNull($dummy->id);
            
        }
        
        /**
         * @expectedException         VzaarApi\Exceptions\FunctionArgumentEx
         * @expectedExceptionMessage  Parameter shoud be an array
         */
        public function testRecord_create_Ex1()
        {
            
            $params = '{"name": "Dummy Record"}';
            
            $dummy = new DummyRecord();
            $dummy->create($params);
            
        }
        
        /**
         * @expectedException         VzaarApi\Exceptions\FunctionArgumentEx
         * @expectedExceptionMessage  Parameter should be instance of VzaarApi\Client
         */
        public function testRecord_create_Ex2()
        {
            
            $params = array();
            $client = new \stdClass();
            
            $dummy = new DummyRecord($client);
            $dummy->create($params,$client);
            
        }
        
        public function testRecord_read()
        {
            $callback = function($recordRequest) {
                
                $this->assertEquals('GET',$recordRequest['method']);
                $this->assertEquals('/dummy_endpoint', $recordRequest['endpoint']);
                $this->assertNotEmpty($recordRequest['recordPath']);
                $this->assertEmpty($recordRequest['recordQuery']);
                $this->assertEmpty($recordRequest['recordData']);
                
                return \json_decode(DummyRecord::$lookup);
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $read_id=1;
            $dummy = new DummyRecord($client);
            $dummy->read($read_id);
            
            $this->assertNotNull($dummy->id);
            
        }
        
        /**
         * @expectedException         VzaarApi\Exceptions\FunctionArgumentEx
         * @expectedExceptionMessage  Parameter shoud be an array
         */
        public function testRecord_read_Ex1()
        {
            
            $client = new Client();
            $params = new \stdClass();
            
            $dummy = new DummyRecord($client);
            $dummy->read($params,$client);
            
        }
        
        public function testRecord_update()
        {
            $callback = function($recordRequest) {
                
                $this->assertEquals('PATCH',$recordRequest['method']);
                $this->assertEquals('/dummy_endpoint', $recordRequest['endpoint']);
                $this->assertNotEmpty($recordRequest['recordPath']);
                $this->assertEmpty($recordRequest['recordQuery']);
                $this->assertNotEmpty($recordRequest['recordData']);
                $this->assertArrayHasKey('name',$recordRequest['recordData']);
                
                $result = \json_decode(DummyRecord::$lookup);
                
                $result->data->name = $recordRequest['recordData']['name'];
                
                return $result;
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $dummy = new DummyRecord($client);
            
            $jsonobj = \json_decode(DummyRecord::$lookup);
            
            $updateRecord = new \ReflectionMethod($dummy,'updateRecord');
            $updateRecord->setAccessible(true);
            $updateRecord->invoke($dummy ,$jsonobj);
            
            $old_value = $dummy->name;
            $dummy->name = 'Bar';
            
            $new_value = $dummy->name;
            
            $this->assertNotEquals($new_value, $old_value);
            
            $dummy->update();
            $saved_value = $dummy->name;
            
            $this->assertNotEquals($saved_value, $old_value);
            $this->assertEquals($new_value, $saved_value);
            
        }
        
        public function testRecord_update_params_empty() {
            
            $client = $this->getMockBuilder(Client::class)
            ->setMethods(['clientSend'])
            ->getMock();
            
            $client->expects($this->never())
            ->method('clientSend');
            
            $dummy = new DummyRecord($client);
            $dummy->id = 1;
            
            $dummy->update();
            
        }
        
        /**
         * @expectedException         VzaarApi\Exceptions\RecordEx
         * @expectedExceptionMessage  Record corrupted, missing id
         */
        public function testRecord_update_Ex1(){
            
            $dummy = new DummyRecord();
            
            $dummy->update();
            
        }

        public function testRecord_delete()
        {
            $callback = function($recordRequest) {
                
                $this->assertEquals('DELETE',$recordRequest['method']);
                $this->assertEquals('/dummy_endpoint', $recordRequest['endpoint']);
                $this->assertNotEmpty($recordRequest['recordPath']);
                $this->assertEmpty($recordRequest['recordQuery']);
                $this->assertEmpty($recordRequest['recordData']);
                
                return true;
            };
            
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $dummy = new DummyRecord($client);
            
            $json = \json_decode(DummyRecord::$lookup);
            
            $jsonobj = \json_decode(DummyRecord::$lookup);
            
            $updateRecord = new \ReflectionMethod($dummy,'updateRecord');
            $updateRecord->setAccessible(true);
            $updateRecord->invoke($dummy ,$jsonobj);
            
            $dummy->delete();
            
            $id = isset($dummy->id) ? $dummy->id : null;
            
            $this->assertNull($id);
            
            $class = new \ReflectionClass($dummy);
            $recordData = $class->getProperty('recordData');
            $recordData->setAccessible(true);
            
            $this->assertInstanceOf(\stdClass::class, $recordData->getValue($dummy));
            $recordData_value = $recordData->getValue($dummy);
            
            $this->assertInstanceOf(\stdClass::class, $recordData_value->data);
            $this->assertEmpty((array)$recordData_value->data);
            
        }
        
        /**
         * @expectedException         VzaarApi\Exceptions\RecordEx
         * @expectedExceptionMessage  Record corrupted, missing id
         */
        public function testRecord_delete_Ex1(){
            
            $dummy = new DummyRecord();
            $id = isset($dummy->id) ? $dummy->id : null;
            
            $this->assertNull($id);
            
            $dummy->delete();
            
        }

        
    }

?>
