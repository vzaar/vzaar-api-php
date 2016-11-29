<?php
    namespace Vzaar;
    
    use Vzaar\RecordList;
    use Vzaar\Video;
    use Vzaar\Client;
    use Vzaar\FunctionArgumentEx;

    class VideosList extends RecordList {


        public function __construct($client = null) {
            
            FunctionArgumentEx::assertInstanceOf(Client::class, $client);
        
            //inherited static variable
            $this->endpoint = '/videos';
            
            //inherited static variable
            $this->recordClass = Video::class;
            
            parent::__construct($client);
            
        }
        
    }
    
?>
