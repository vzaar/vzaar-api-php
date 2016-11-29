<?php
    namespace Vzaar;
    
    use Vzaar\Record;
    use Vzaar\Client;
    use Vzaar\FunctionArgumentEx;
    use Vzaar\ArgumentValueEx;
    
    class Signature extends Record {
        
        
        public function __construct($client = null) {
            
            FunctionArgumentEx::assertInstanceOf(Client::class, $client);
            
            //inherited static variable
            $this->endpoint = '/signature';
            
            parent::__construct($client);

        }
        
        
        protected function createSingle($params) {

            $this->endpoint = '/signature/single';
            
            $this->crudCreate($params);

        }
        
        protected function createMultipart($params) {
            
            $this->endpoint = '/signature/multipart';
            
            $this->crudCreate($params);
                     
        }
        
        protected function createFromFile($filepath) {
            
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
