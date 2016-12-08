<?php
    namespace VzaarApi\Resources;
    
    use VzaarApi\Exceptions\VzaarError;
    use VzaarApi\Exceptions\RecordEx;
    use VzaarApi\Exceptions\FunctionArgumentEx;
    use VzaarApi\Client;
    
    abstract class Record {
        
        
        protected $httpClient;
        
        protected $recordPath;
        protected $recordQuery;
        protected $recordBody;
        protected $recordData;
        
        
        protected function __construct($client = null){
            
            if(!is_null($client))
                FunctionArgumentEx::assertInstanceOf(Client::class, $client);
            
            $this->httpClient = is_null($client) ? new Client() : $client;
            
            if(!isset(static::$endpoint))
                throw new VzaarError('Endpoint have to be configred');
            
            $this->recordData = \json_decode('{"data":{}}');
            
            $this->recordBody = array();
            $this->recordQuery = array();
            $this->recordPath = '';
            
            
        }
        
        /**
         *
         *
         *
         */
        
        public function getClient() {
            
            return $this->httpClient;
            
        }
        
        public function edited() {
        
            return !empty($this->recordBody);
        }
        
        public function checkRateLimit() {
            
            return $this->httpClient->checkRateLimit();
            
        }
        
        public function checkRateRemaining() {
            
            return $this->httpClient->checkRateRemaining();
            
        }
        
        public function checkRateReset() {
            
            return $this->httpClient->checkRateReset();
            
        }

        
        /**
         *
         *
         *
         */
        
        protected function requestClient($method) {
            
            $recordRequest = array();
            $recordRequest['method'] = $method;
            $recordRequest['endpoint'] =  static::$endpoint;
            $recordRequest['recordPath'] =  $this->recordPath;
            $recordRequest['recordQuery'] =  $this->recordQuery;
            $recordRequest['recordData'] =  $this->recordBody;
            
            $result = $this->httpClient->clientSend($recordRequest);
            
            //reset request parameters
            
            $this->recordBody = array();
            $this->recordQuery = array();
            $this->recordPath = '';
            
            return $result;
            
        }
        
        
        protected function updateRecord($data) {
            
            FunctionArgumentEx::assertInstanceOf(\stdClass::class, $data);
            
            if(!property_exists($data,'data'))
                throw new VzaarError("Received data are not valid");
            
            $this->recordData = $data;
            
        }
        
        protected function cleanRecord() {
            
            $this->recordData = new \stdClass();
            $this->recordData->data = new \stdClass();
            
        }
        
        
        /**
         
         The CRUD object functions will be exposed by overrided by inheritance
         
         */
        
        protected function crudCreate($params = null) {
            
            if(!is_null($params)){
                
                FunctionArgumentEx::assertIsArray($params);
                
                $this->recordBody = $params;
                
            }
            
            $httpMethod = 'POST';
            $result = $this->requestClient($httpMethod);
            
            $this->updateRecord($result);
            
        }
        
        protected function crudRead($path = null, $params = null) {
            
            if(!is_null($path))
                $this->recordPath = $path;
            
            if(!is_null($params)){
                
                FunctionArgumentEx::assertIsArray($params);
                
                $this->recordQuery = $params;
            }
            
            
            $httpMethod = 'GET';
            $result = $this->requestClient($httpMethod);
            
            $this->updateRecord($result);
            
        }
        
        
        protected function crudUpdate($params = null) {
            
            $this->assertRecordValid();
            $this->recordPath = $this->id;
            
            //unset the 'id' if set as object property
            //the data become now resource path
            //this prevents 'id' from being sent as body parameter
            unset($this->recordBody['id']);
            
            if(!is_null($params)){
                
                FunctionArgumentEx::assertIsArray($params);
                
                // any attributes chaged as object property
                // will be overwritten with the parameters
                // if given in the argument list
                $this->recordBody = $params;
                
            }
            
            if(!empty($this->recordBody)) {
                
                $httpMethod = 'PATCH';
                $result = $this->requestClient($httpMethod);
                
                $this->updateRecord($result);
            }
            
        }
        
        protected function crudDelete() {
            
            $this->assertRecordValid();
            $this->recordPath = $this->id;
            
            //clear the array, in case any object properties
            //were modified before the delete
            $this->recordBody = array();
            
            $httpMethod = 'DELETE';
            $this->requestClient($httpMethod);
            
            $this->cleanRecord();
            
        }
        
        /**
         *
         * assertions
         *
         */
        
        protected function assertRecordValid(){
            
            if(!isset($this->id))
                throw new RecordEx("Record corrupted, missing id");
            
        }
        
        /**
         *
         * __set, __get, __isset, __unset overloading
         *
         */
        
        public function __get($name) {
        
            if(isset($this->recordBody[$name]))
                return $this->recordBody[$name];
            
            return $this->recordData->data->{$name};

        }
        
        public function __set($name,$value) {
            
            if(isset($this->recordData->data->{$name})) {
                if($this->recordData->data->{$name} != $value) {
                    $this->recordBody[$name] = $value;
                    
                } else {
                    //for cases when data changed, then reverted back to old value
                    //to remove the argument from parameters to update array
                    
                    unset($this->recordBody[$name]);
                }
            } else {
                $this->recordBody[$name] = $value;
            }
            
        }
        
        public function __isset($name) {
            
            return (isset($this->recordData->data->{$name}) |
                      isset($this->recordBody[$name]));
            
        }
        
        public function __unset($name) {
            
            unset($this->recordData->data->{$name});
            unset($this->recordBody[$name]);
            
        }
        
        
    }
?>
