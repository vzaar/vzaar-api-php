<?php
    namespace VzaarApi\Resources;
    
    use VzaarApi\Resources\iHttpChannel;
    use VzaarApi\Exceptions\ArgumentTypeEx;
    use VzaarApi\Exceptions\ArgumentValueEx;
    use VzaarApi\Exceptions\ConnectionEx;
    
    class HttpCurl implements iHttpChannel {
        
        public static $CURLOPT_VERBOSE = false;
        
        public static $CURLOPT_CAINFO = false;
        public static $CURLOPT_SSL_VERIFYPEER = true;
        public static $CURLOPT_SSL_VERIFYHOST = 2;
        
        public static $CURLOPT_CONNECTTIMEOUT = false;
    
        /**
         * @param array
         *
         * ['method'] : string
         * ['headers'] : array
         * ['uri'] : string
         * ['data'] : applicaiton/json | multipart/form-data (with file upload)
         *
         * @return array
         *
         * ['httpCode'] : string
         * ['httpResponse'] : string
         *
         */
        public function httpRequest($cfg){
            
            ArgumentTypeEx::assertIsArray($cfg);
        
            $method = isset($cfg['method']) ? $cfg['method'] : null;
            $headers = isset($cfg['headers']) ? $cfg['headers'] : array();
            $uri = isset($cfg['uri']) ? $cfg['uri'] : null;
            $data = isset($cfg['data']) ? $cfg['data'] : '';
            
            $options = array();
            
            switch($method){
                case 'GET':
                    $options[CURLOPT_HTTPGET] = true;
                    break;
                case 'POST':
                    $options[CURLOPT_POST] = true;
                    
                    if(!empty($data))
                        $options[CURLOPT_POSTFIELDS] = $data;
                    break;
                case 'PATCH':
                    $options[CURLOPT_CUSTOMREQUEST] = "PATCH";
                    
                    if(!empty($data))
                        $options[CURLOPT_POSTFIELDS] = $data;
                    break;
                case 'DELETE':
                    $options[CURLOPT_CUSTOMREQUEST] = "DELETE";
                    break;
                default:
                    throw new ArgumentValueEx("Http method missing");
            }
            
            
            $options[CURLOPT_URL] = $uri;
            
            $options[CURLOPT_HEADER] = true;
            
            if(!empty($headers))
                $options[CURLOPT_HTTPHEADER] = $headers;
            
            $options[CURLOPT_RETURNTRANSFER] = true;
            
            if(self::$CURLOPT_CONNECTTIMEOUT !== false)
                $options[CURLOPT_CONNECTTIMEOUT] = self::$CURLOPT_CONNECTTIMEOUT;
            
            $options[CURLOPT_FORBID_REUSE] = true;
            $options[CURLOPT_FRESH_CONNECT] = true;
            
            if(self::$CURLOPT_CAINFO !== false)
                $options[CURLOPT_CAINFO] = self::$CURLOPT_CAINFO;

            $options[CURLOPT_SSL_VERIFYPEER] = self::$CURLOPT_SSL_VERIFYPEER;
            $options[CURLOPT_SSL_VERIFYHOST] = self::$CURLOPT_SSL_VERIFYHOST;
            
            $options[CURLOPT_VERBOSE] = self::$CURLOPT_VERBOSE;
            
            
            $cs = curl_init();
            
            if($cs === false){
            
                $no = curl_errno($cs);
                $msg = curl_error($cs);
                
                throw new ConnectionEx('Connetion failed: '.$no.' : '.$msg);
            }
            
            curl_setopt_array($cs,$options);
            
            $result = curl_exec($cs);

            
            if ($result === false)
            {
                $no = curl_errno($cs);
                $msg = curl_error($cs);
                
                curl_close($cs);
                
                throw new ConnectionEx('Connetion failed: '.$no.' : '.$msg);

            }
            
            $response = array();
            $response['httpCode'] = curl_getinfo($cs, CURLINFO_HTTP_CODE);
            $response['httpResponse'] = $result;
            
            curl_close($cs);
            
            
            return $response;
        }
    }
?>
