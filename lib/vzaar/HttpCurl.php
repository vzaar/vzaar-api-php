<?php
    namespace Vzaar;
    
    use Vzaar\iHttpChannel;
    use Vzaar\FunctionArgumentEx;
    use Vzaar\ConnectionEx;
    use Vzaar\ArgumentValueEx;
    
    class HttpCurl implements iHttpChannel {
        
        public static $VERBOSE = false;
    
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
            
            FunctionArgumentEx::assertIsArray($cfg);
        
            $method = isset($cfg['method']) ? $cfg['method'] : null;
            $headers = isset($cfg['headers']) ? $cfg['headers'] : null;
            $uri = isset($cfg['uri']) ? $cfg['uri'] : null;
            $data = isset($cfg['data']) ? $cfg['data'] : null;
            
            $options = array();
            
            switch($method){
                case 'GET':
                    $options[CURLOPT_HTTPGET] = true;
                    break;
                case 'POST':
                    $options[CURLOPT_POST] = true;
                    
                    if(!is_null($data))
                        $options[CURLOPT_POSTFIELDS] = $data;
                    break;
                case 'PATCH':
                    $options[CURLOPT_CUSTOMREQUEST] = "PATCH";
                    
                    if(!is_null($data))
                        $options[CURLOPT_POSTFIELDS] = $data;
                    break;
                case 'DELETE':
                    $options[CURLOPT_CUSTOMREQUEST] = "DELETE";
                    break;
                default:
                    throw new ArgumentValueEx("Http method missing.");
            }
            
            
            $options[CURLOPT_URL] = $uri;
            
            $options[CURLOPT_HEADER] = true;
            $options[CURLOPT_HTTPHEADER] = $headers;
            $options[CURLOPT_RETURNTRANSFER] = true;
            
            $options[CURLOPT_FORBID_REUSE] = true;
            $options[CURLOPT_FRESH_CONNECT] = true;
            
            $options[CURLOPT_SSL_VERIFYPEER] = false;
            $options[CURLOPT_SSL_VERIFYHOST] = false;
            
            $options[CURLOPT_VERBOSE] = self::$VERBOSE;
            
            
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
