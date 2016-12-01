<?php
    namespace VzaarApi\Exceptions;
    
    use VzaarApi\Exceptions\VzaarException;
    
    //argument passed to method is not of expected type
    class FunctionArgumentEx extends VzaarException {
        
        public static function assertIsArray($params) {
            
            if(!is_null($params))
                if(!is_array($params))
                    throw new self("Parameter shoud be an array.");
            
        }
        
        public static function assertInstanceOf($class,$instance) {
            
            if(!is_null($instance))
                if(!($instance instanceof $class))
                    throw new self("Parameter should be instance of ".$class);
            
        }
        
    }
    
?>
