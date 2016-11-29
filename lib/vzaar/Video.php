<?php
    namespace VzaarApi;
    
    use VzaarApi\Endpoint;
    use VzaarApi\Signature;
    use VzaarApi\Client;
    use VzaarApi\S3Client;
    use VzaarApi\FunctionArgumentEx;
    use VzaarApi\ArgumentValueEx;
    use VzaarApi\LinkUpload;
    
    class Video extends Record {
        
        protected static $endpoint;
        
        public function __construct($client = null) {
            
            FunctionArgumentEx::assertInstanceOf(Client::class, $client);
            
            self::$endpoint = '/videos';
            
            parent::__construct($client);

        }
        
        /**
         * source : array('filepath' => <value>)
         * source : array('url' => <value>)
         * source : array('guid' => <value>)
         */
        
        protected function createData($params) {
            
            FunctionArgumentEx::assertIsArray($params);
            
            //check: at least one or all expected parameters recieved
            if((isset($params['guid']) xor isset($params['url'])) xor isset($params['filepath'])) {
                
                //check: all parameters are set - throw exception
                if(isset($params['guid']) and isset($params['url']) and isset($params['filepath']))
                    throw new ArgumentValueEx('Only one of the parameters: guid or url or filepath expected.');
                
                $result = null;
                
                if(isset($params['guid']))
                    $this->guidCreate($params);
                elseif (isset($params['url']))
                    $result = $this->urlCreate($params);
                elseif (isset($params['filepath']))
                    $this->uploadCreate($params);
                
            } else //expected parameter is not received
                throw new ArgumentValueEx('Only one of the parameters: guid or url or filepath expected.');
        
            return $result;
        }
        
        protected function guidCreate($params) {
        
            $this->crudCreate($params);
            
        }
        
        protected function urlCreate($params) {
            
            if(!isset($params['uploader']))
                $params['uploader'] = Client::UPLOADER . Client::VERSION;
            
            return LinkUpload::create($params);

        }
        
        protected function uploadCreate($params) {
            
            if(!file_exists($params['filepath']))
                throw new ArgumentValueEx('File does not exist: '.$params['filepath']);
            
            //create signature
            $signature = Signature::create($params['filepath']);
        
            //upload file
            $s3client = new S3Client();
            $s3client->uploadFile($signature,$params['filepath']);

            //create video from guid
            unset($params['filepath']);
            $params['guid'] = $signature->guid;

            $result = $this->createData($params);
             
        }
        
        /**
         * static user methods
         */
        
        public static function find($params, $client = null){
            
            $video = new self($client);
            $video->crudRead($params);
            
            return $video;
            
        }
        
        public static function create($params, $client = null) {
            
            $video = new self($client);
            $result = $video->createData($params);
            
            $linkClass = LinkUpload::class;
            if($result instanceof $linkClass)
                return $result; //Video object created with LinkUpload class
            
            return $video;
        }

    }
?>
