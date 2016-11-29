<?php
    namespace VzaarApi;
    
    use VzaarApi\Record;
    use VzaarApi\Client;
    use VzaarApi\FunctionArgumentEx;
    
    class LinkUpload extends Video {
        
        protected static $endpoint;
    
        public function __construct($client = null) {
            
            FunctionArgumentEx::assertInstanceOf(Client::class, $client);
            
            self::$endpoint = '/link_uploads';
            
            parent::__construct($client);
        
        }
        
        public static function create($params, $client = null) {
        
            $link = new self($client);
            $link->crudCreate($params);
            
            return $link;
        
        }
    
    }
    
?>
