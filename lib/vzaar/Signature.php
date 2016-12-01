<?php
    namespace VzaarApi;
    
    use VzaarApi\Resources\Record;
    use VzaarApi\Exceptions\FunctionArgumentEx;
    use VzaarApi\Exceptions\ArgumentValueEx;
    use VzaarApi\Client;
    
    class Signature extends Record {
        
        protected static $endpoint;
        
        public function __construct($client = null) {
            
            FunctionArgumentEx::assertInstanceOf(Client::class, $client);
            
            self::$endpoint = '/signature';
            
            parent::__construct($client);

        }
        
        protected function createSingle($params) {
            
            FunctionArgumentEx::assertIsArray($params);
            
            if(!array_key_exists('uploader', array_change_key_case($params, CASE_LOWER)))
                $params['uploader'] = Client::UPLOADER . Client::VERSION;

            self::$endpoint = '/signature/single';
            
            $this->crudCreate($params);

        }
        
        protected function createMultipart($params) {
            
            FunctionArgumentEx::assertIsArray($params);
            
            if(!array_key_exists('uploader', array_change_key_case($params, CASE_LOWER)))
                $params['uploader'] = Client::UPLOADER . Client::VERSION;
            
            self::$endpoint = '/signature/multipart';
            
            $this->crudCreate($params);
                     
        }
        
        protected function createFromFile($filepath) {
            
            if(!file_exists($filepath))
                throw new ArgumentValueEx('File does not exist: '.$filepath);
            
            $filename = basename($filepath);
            $filesize = filesize($filepath);
            
            $request['filename'] = $filename;
            $request['filesize'] = $filesize;
            
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
