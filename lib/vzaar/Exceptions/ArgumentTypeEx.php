<?php
    namespace VzaarApi\Exceptions;
    
    use VzaarApi\Exceptions\VzaarException;
    
    /*
     
     Thrown when argument passed to method is not of expected type
     
     */
    
    class ArgumentTypeEx extends VzaarException {
        
        public static function assertIsArray($params) {
            
            if(!is_array($params))
                throw new self("Parameter should be an array");
            
        }
        
        public static function assertInstanceOf($class,$instance) {
            
            if(!($instance instanceof $class))
                throw new self("Parameter should be instance of ".$class);
            
        }
        
    }
    
?>
