<?php
    namespace Vzaar;
    
    use Vzaar\Record;
    use Vzaar\Client;
    use Vzaar\FunctionArgumentEx;
    
    class LinkUpload extends Video {
    
        public function __construct($client = null) {
            
            FunctionArgumentEx::assertInstanceOf(Client::class, $client);
            
            parent::__construct($client);
            
            //inherited static variable
            $this->endpoint = '/link_uploads';
        
        }
        
        public static function create($params, $client = null) {
        
            $link = new self($client);
            $link->crudCreate($params);
            
            return $link;
        
        }
    
    }
    
?>
