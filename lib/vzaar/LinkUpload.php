<?php
    namespace VzaarApi;
    
    use VzaarApi\Resources\Record;
    use VzaarApi\Exceptions\FunctionArgumentEx;
    use VzaarApi\Client;
    
    class LinkUpload extends Video {
        
        protected static $endpoint;
    
        public function __construct($client = null) {
            
            FunctionArgumentEx::assertInstanceOf(Client::class, $client);
            
            self::$endpoint = '/link_uploads';
            
            parent::__construct($client);
        
        }
        
        protected function linnkCreate($params) {
        
            FunctionArgumentEx::assertIsArray($params);
            
            if(!array_key_exists('uploader', array_change_key_case($params, CASE_LOWER)))
                $params['uploader'] = Client::UPLOADER . Client::VERSION;
            
            $this->crudCreate($params);
            
        }
        
        public static function create($params, $client = null) {
        
            $link = new self($client);
            $link->linnkCreate($params);
            
            return $link;
        
        }
    
    }
    
?>
