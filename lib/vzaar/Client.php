<?php
    namespace VzaarApi;
    
    use VzaarApi\Resources\HttpCurl;
    use VzaarApi\Resources\iHttpChannel;
    use VzaarApi\Exceptions\ClientErrorEx;
    use VzaarApi\Exceptions\FunctionArgumentEx;
    
    class Client {
        
        public static $client_id = 'id';
        public static $auth_token = 'token';
        public static $version = 'v2';
        public static $urlAuth = false;
        
        public static $VERBOSE = false;
        
        const UPLOADER = 'Vzaar PHP SDK';
        const VERSION = '2.0.0-alpha';
        
        const ONE_MB = 1024 * 1024; // 1 MB in Bytes
        const MULTIPART_MIN_SIZE = 5 * self::ONE_MB;// in Bytes
        

        
        protected $clientId;
        protected $clientAuthToken;
        protected $clientUrlAuth;
        
        protected $apiVersion = 'v2';
        protected $apiUrl = 'https://api.vzaar.com/api';
        
        protected $httpHandler = null;
        protected $httpCode = null;
        protected $httpHeaders = null;
        
        /**
         * @param array $config
         * ['id'] string
         * ['token'] string
         * ['version'] string
         *
         * @param iHttpChannel $httpClient
         *
         */
        public function __construct($config = null,
                                    $httpHandler = null) {
            
            FunctionArgumentEx::assertIsArray($config);
            
            $this->clientId = isset($config['client_id']) ? $config['client_id'] : self::$client_id;
            $this->clientAuthToken = isset($config['auth_token']) ? $config['auth_token'] : self::$auth_token;
            $this->apiVersion = isset($config['version']) ? $config['version'] : self::$version;
            $this->clientUrlAuth = isset($config['urlAuth']) ? $config['urlAuth'] : self::$urlAuth;
            
            if(is_null($httpHandler)) {
                
                if (!extension_loaded('curl'))
                    exit(PHP_EOL. "VZAAR_ERROR: CURL extension not loaded". PHP_EOL);
                
                $this->httpHandler = new HttpCurl();
            
            } else {
                
                FunctionArgumentEx::assertInstanceOf(iHttpChannel::class,$httpHandler);
                
                $this->httpHandler = $httpHandler;
           
            }
            
        }
        
        public function checkRateLimit() {
        
            $value = null;
            if(isset($this->httpHeaders['X-RateLimit-Limit']))
                $value = $this->httpHeaders['X-RateLimit-Limit'];
            
            return $value;
        
        }
        
        public function checkRateRemaining() {
            
            $value = null;
            if(isset($this->httpHeaders['X-RateLimit-Remaining']))
                $value = $this->httpHeaders['X-RateLimit-Remaining'];
            
            return $value;
            
        }
        
        public function checkRateReset() {
            
            $value = null;
            if(isset($this->httpHeaders['X-RateLimit-Reset']))
                $value = $this->httpHeaders['X-RateLimit-Reset'];
            
            return $value;
            
        }
        
        public function checkDeprecated() {
            
            $value = false;
            if(isset($this->httpHeaders['X-vzaar-Deprecated']))
                $value = true;
            
            return $value;
        }
        
        public function checkSunsetDate() {
            
            $value = null;
            if(isset($this->httpHeaders['X-vzaar-Sunset-Date']))
                $value = $this->httpHeaders['X-vzaar-Sunset-Date'];
            
            return $value;
        }
        
        /*
         $recordRequest: array
         
         ['method'] : string
         ['endpoint'] : string
         ['recordPath'] : string
         ['recordQuery'] : array
         ['recordData'] : array
         */
        
        public function clientSend($recordRequest) {
            
            $httpRequest['method'] = $recordRequest['method'];
            
            
            if($this->clientUrlAuth)
                $recordRequest['recordQuery'] = $this->addAuthUri($recordRequest['recordQuery']);
            else
                $httpRequest['headers'] = $this->addAuthHeaders();
            
            $apiUrl = $this->httpUrl();
            $httpRequest['uri'] = $this->params2uri($apiUrl,
                                                    $recordRequest['endpoint'],
                                                    $recordRequest['recordPath'],
                                                    $recordRequest['recordQuery']);
            
            $httpRequest['data'] = null;
            if(!empty($recordRequest['recordData'])) {
                
                $httpRequest['data'] = $this->object2json($recordRequest['recordData']);
                
                $httpRequest['headers'][] = 'Content-Type: application/json';
                $httpRequest['headers'][] = 'Content-Length: ' . strlen($httpRequest['data']);
                
            }
            
            if(self::$VERBOSE) {
                
                $log = PHP_EOL. "VZAAR_LOG_START". PHP_EOL;
                $log .= PHP_EOL."*** REQUEST QUERY ***".PHP_EOL;
                $log .= $httpRequest['uri'].PHP_EOL;
                $log .= PHP_EOL."*** REQUEST HEADERS ***".PHP_EOL;
                $log .= implode("\r\n", $httpRequest['headers']).PHP_EOL;
                $log .= PHP_EOL."*** REQUEST BODY ***".PHP_EOL;
                $log .= $httpRequest['data'].PHP_EOL;
                $log .= PHP_EOL. "VZAAR_LOG_END". PHP_EOL;
                
                error_log($log);
            }
            
            $httpResponse = $this->httpHandler->httpRequest($httpRequest);
            
            /*
             $httpResponse : array
             
             ['httpCode'] : string
             ['httpResponse'] : string
             */
            
            $result = $this->httpResponse($httpResponse);
            
            /**
             $result : [stdClass | bool]
             
             (the stdClass object is result of json_decode() function,
             and contains record data received as json in http response body)
             
             (bool - is true when record is sucessfuly deleted)
             */
            return $result;
        }
        
        protected function httpResponse($httpResponse) {
            
            $split = explode("\r\n\r\n", $httpResponse['httpResponse'], 2);
            
            $responseHeaders = $split[0];
            $responseBody = ''; //initialize as empty string
            
            if(isset($split[1]))
                $responseBody = $split[1];
            
            if (strpos($responseHeaders," 100 Continue") !== false ) {
                
                $split = explode("\r\n\r\n", $responseBody, 2);
                
                $responseHeaders = $split[0];
                $responseBody = ''; //initialize as empty string
                
                if(isset($split[1]))
                    $responseBody = $split[1];
                
            }
            
            $this->httpCode =  $httpResponse['httpCode'];
            $this->httpHeaders = $this->headers2array($responseHeaders);
            
            if(self::$VERBOSE) {
                
                $log = PHP_EOL. "VZAAR_LOG_START". PHP_EOL;
                $log .= PHP_EOL."*** RESPONSE HTTP CODE ***".PHP_EOL;
                $log .= $this->httpCode.PHP_EOL;
                $log .= PHP_EOL."*** RESPONSE HTTP HEADERS ***".PHP_EOL;
                $log .= implode("\r\n", $this->array2headers($this->httpHeaders)).PHP_EOL;
                $log .= PHP_EOL."*** RESPONSE HTTP BODY ***".PHP_EOL;
                $log .= $responseBody.PHP_EOL;
                $log .= PHP_EOL. "VZAAR_LOG_END". PHP_EOL;
                
                error_log($log);
            }
            
            if($this->httpCode == 200 || //OK
               $this->httpCode == 201)   //Created
            {
                
                $response = $this->json2object($responseBody);
                
                if(!property_exists($response,'data'))
                    throw new ClientErrorEx('Response data: response not correct');
                
            }
            elseif($this->httpCode == 204) //No Content
            {
                if(!empty($responseBody))
                    throw new ClientErrorEx('No content expected with this request');
                
                $response = true;
                
            }
            elseif ($this->httpCode == 400 || //Bad Request
                    $this->httpCode == 401 || //Unauthorized
                    $this->httpCode == 403 || //Forbidden
                    $this->httpCode == 404 || //Not Found
                    $this->httpCode == 422 || //Unprocessable Entity
                    $this->httpCode == 429 || //To many Requests
                    $this->httpCode == 500)   //Server error
            {
                
                $response = $this->json2object($responseBody);
                
                if(!property_exists($response,'errors'))
                    throw new ClientErrorEx('Response data: response not correct'.'\n');
                
                $errors = array();
                foreach($response->errors as $key => $error)
                {
                    $errors[] = $error->message .' : '. $error->detail;
                }
                
                throw new ClientErrorEx('HttpCode: '. $this->httpCode .' Details: '. implode('\n',$errors).'\n');
                
            }
            else
                throw new ClientErrorEx('Unknown response from server. HttpCode: '. $this->httpCode .'\n');
            
            
            return $response;
            
        }
        
        protected function httpUrl() {
            
            return implode('/',array($this->apiUrl,$this->apiVersion));
        }
        
        protected function addAuthHeaders($headers = null){
            
            $credentials = array('X-Client-Id:' . $this->clientId,
                                 'X-Auth-Token:' . $this->clientAuthToken);
            
            if(is_array($headers))
                return array_merge($headers,$credentials);
            else
                return $credentials;
        }
        
        protected function addAuthUri($queryParams) {
            
            $credentials = array('client_id' => $this->clientId,
                                 'auth_token' => $this->clientAuthToken);
            
            if(is_array($queryParams))
                return array_merge($queryParams,$credentials);
            else
                return $credentials;
        }
        
        protected function object2json($obj) {
            
            if(is_array($obj))
                return json_encode($obj);
            
        }
        
        protected function json2object($json) {
            
            $obj = json_decode($json);
            
            if(json_last_error() != \JSON_ERROR_NONE)
                throw new ClientErrorEx(json_last_error_msg());
            
            return $obj;
            
        }
        
        protected function params2uri($httpUrl,$endpointUrn, $pathParams, $queryParams) {
            
            $uri = $httpUrl;
            $uri .= $endpointUrn;
            
            if(!empty($pathParams))
                $uri .= '/'. $pathParams;
            
            if(!empty($queryParams))
                $uri .= '?'. http_build_query($queryParams);
            
            return $uri;
        }
        
        
        protected function headers2array($headers)
        {
                
            $headers = explode("\r\n", $headers);
            
            $result = array();
            
            foreach ($headers as &$header) {
                
                if (preg_match('/([^:]+): (.*)/', $header, $matches) === 1)
                    $result[$matches[1]] = $matches[2];
                
            }
            
            return $result;
            
        }
        
        protected function array2headers($headers)
        {
            $result = array();
            if(is_array($headers)){
                
                foreach($headers as $key=>$value){
                    
                    $result[] = $key.': '.$value;
                }
            }
            
            return $result;
            
        }
        
}
?>
