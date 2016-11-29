<?php
    namespace VzaarApi;
    
    use VzaarApi\Record;
    use VzaarApi\Client;
    use VzaarApi\FunctionArgumentEx;
    use VzaarApi\ArgumentValueEx;
    
    class Signature extends Record {
        
        protected static $endpoint;
        
        public function __construct($client = null) {
            
            FunctionArgumentEx::assertInstanceOf(Client::class, $client);
            
            self::$endpoint = '/signature';
            
            parent::__construct($client);

        }
        
        
        protected function createSingle($params) {

            self::$endpoint = '/signature/single';
            
            $this->crudCreate($params);

        }
        
        protected function createMultipart($params) {
            
            self::$endpoint = '/signature/multipart';
            
            $this->crudCreate($params);
                     
        }
        
        protected function createFromFile($filepath) {
            
            //i think this should be an array, the uploader needs to be
            //as a parameter to keep this conisstent with single(), multiple:
            
            if(!file_exists($filepath))
                throw new ArgumentValueEx('File does not exist: '.$filepath);
            
            $filename = basename($filepath);
            $filesize = filesize($filepath);
            
            $request['filename'] = $filename;
            $request['filesize'] = $filesize;
            $request['uploader'] = Client::UPLOADER . Client::VERSION;
            
            
            if($filesize >= Client::MULTIPART_MIN_SIZE)
                $this->createMultipart($request);
            else
                $this->createSingle($request);
            
        }
        
        public static function create($params,$client = null) {
            $signature = new self($client);
            $signature->createFromFile($params);
            
            return $signature;
        }
        
        public static function single($params,$client = null) {
            $signature = new self($client);
            $signature->createSingle($params);
            
            return $signature;
        }
        
        public static function multipart($params,$client = null) {
            $signature = new self($client);
            $signature->createMultipart($params);
            
            return $signature;
        }
    
    }
    
?>
