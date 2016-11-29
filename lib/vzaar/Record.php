<?php
    namespace Vzaar;
    
    use Vzaar\Client;
    use Vzaar\VzaarException;
    use Vzaar\RecordException;
    
    abstract class Record {
        
        
        protected $httpClient;
        
        protected $endpoint;
        
        protected $recordPath;
        protected $recordQuery;
        protected $recordBody;
        protected $recordData;
        
        
        protected function __construct(Client $client = null){
            
            $this->httpClient = is_null($client) ? new Client() : $client;
            
            if(!isset($this->endpoint))
                throw new VzaarError('Endpoint have to be configred.');
            
            $this->recordData = new \stdClass();
            $this->recordData->data = new \stdClass();
            
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
        
        public function checkDeprecated() {
            
            return $this->httpClient->checkDeprecated();
        }
        
        public function checkSunsetDate() {
            
            return $this->httpClient->checkSunsetDate();
        }

        
        /**
         *
         *
         *
         */
        
        protected function requestClient($method) {
            
            $recordRequest = array();
            $recordRequest['method'] = $method;
            $recordRequest['endpoint'] =  $this->endpoint;
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
        
        protected function setRecord($data) {
            
            FunctionArgumentEx::assertInstanceOf(\stdClass::class, $data);
            
            //json record from "list" does not have 'data' root property
            
            $this->recordData->data = $data;
        }
        
        protected function updateRecord($data) {
            
            //json record from read/update/create does have 'data' root property
            
            if(!property_exists($data,'data'))
                throw new VzaarException("Received data are not valid.");
            
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
            
            if(!is_null($params)){
                
                FunctionArgumentEx::assertIsArray($params);
                
                // any attributes chaged as object property
                // will be overwritten with the parameters
                // if given in the argument list
                $this->recordBody = $params;
                
            }
            
            $httpMethod = 'PATCH';
            $result = $this->requestClient($httpMethod);
            
            $this->updateRecord($result);
            
        }
        
        protected function crudDelete() {
            
            $this->assertRecordValid();
            $this->recordPath = $this->id;
            
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
        
            $value = $this->recordData->data->{$name};
            
            if(isset($this->recordBody[$name]))
                $value = $this->recordBody[$name];
            
            return $value;

        }
        
        public function __set($name,$value) {
            
            if(isset($this->recordData->data->{$name})) {
                if($this->recordData->data->{$name} != $value) {
                    $this->recordBody[$name] = $value;
                    
                } else { //for cases when data changed, then reverted back to old value
                    
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
