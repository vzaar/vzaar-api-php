<?php
    namespace Vzaar;
    
    use Vzaar\iHttpChannel;
    use Vzaar\Signature;
    use Vzaar\HttpCurl;
    use Vzaar\FunctionArgumentEx;
    use Vzaar\ArgumentValueEx;
    use Vzaar\S3uploadEx;
    
    class S3Client {
        
        protected $httpHandler;
        
        public static $VERBOSE = false;
        
        public function __construct($httpHandler = null) {
            
            if(is_null($httpHandler)){
                
                if (!extension_loaded('curl'))
                    exit("\nERROR: CURL extension not loaded\n\n");
                
                $this->httpHandler = new HttpCurl();
            
            }else {
                FunctionArgumentEx::assertInstanceOf(iHttpChannel::class,$httpHandler);
                
                $this->httpHandler = $httpHandler;
            }
            
        }
        
        
        public function uploadFile($signature, $filepath) {
            
            FunctionArgumentEx::assertInstanceOf(Signature::class,$signature);
            
            if(!file_exists($filepath))
                throw new ArgumentValueEx('File does not exist: '.$filepath);
            
            $filename = basename($filepath);
            $filesize = filesize($filepath);
            
            $cfg['uri'] = $signature->upload_hostname;
            $cfg['method'] = 'POST';
            $cfg['headers'][] = 'Enclosure-Type: multipart/form-data';
            
            //build S3 POST
            
            $data = array('x-amz-meta-uploader' => Client::UPLOADER . Client::VERSION,
                          'AWSAccessKeyId' => $signature->access_key_id,
                          'Signature' => $signature->signature,
                          'acl' => $signature->acl,
                          'bucket' => $signature->bucket,
                          'policy' => $signature->policy,
                          'success_action_status' => $signature->success_action_status,
                          'key' => str_replace('${filename}', $filename, $signature->key),
                          'file' => $filepath);
            
            if(isset($signature->parts)) {
                
                
                $data['chunks'] = $signature->parts;
                
                $chunk = 0;
                $key = $data['key'];
                
                
                $chunksize = $signature->part_size_in_bytes;
                
                $file = @fopen($data['file'], "rb");
                
                if($file === false)
                    throw new ArgumentValueEx("unable to open file ($filename)");
                
                while(!feof($file)){
                    
                    $data['chunk'] = $chunk;
                    $data['key'] = $key. '.' .$chunk;
                    
                    if(self::$VERBOSE) {
                        
                        echo PHP_EOL . "MULTIPARTUPLOAD" . PHP_EOL;
                        var_export($data);
                    }
                    
                    $data['file'] = \fread($file,$signature->part_size_in_bytes);
                    
                    $cfg['data'] = $data;
                    
                    $data['file'] = null;
                    
                    
                    
                    $result = $this->httpHandler->httpRequest($cfg);
                    
                    if($result['httpCode'] == 201) {
                        $chunk++;
                    } else {
                        fclose($file);
                        
                        throw new S3uploadEx('Problem occured with file upload.');
                        //use XMLREADER to get some more details on the error
                    }
                }
                
                fclose($file);
                
                if($result['httpCode'] == 201) {
                    
                    return true;
                    
                } else {
                    
                    throw new S3uploadEx('Problem occured with file upload.');
                    //use XMLREADER to get some more details on the error
                }
                
                
            } else {
                
                if(self::$VERBOSE) {
                    
                    echo PHP_EOL . "SINGLE UPLOAD" . PHP_EOL;
                    var_export($data);
                }
                
                $data['file'] = new \CURLFile($data['file']);
                
                $cfg['data'] = $data;
                
                $result = $this->httpHandler->httpRequest($cfg);
                
                if($result['httpCode'] == 201) {
                    
                    return true;
                    
                } else {
                    
                    throw new S3uploadEx('Problem occured with file upload.');
                    //use XMLREADER to get some more details on the error
                }

            }
            

        }
    }
?>
