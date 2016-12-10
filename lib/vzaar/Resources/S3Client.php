<?php
    namespace VzaarApi\Resources;

    use VzaarApi\Resources\IHttpChannel;
    use VzaarApi\Resources\HttpCurl;
    use VzaarApi\Exceptions\ArgumentTypeEx;
    use VzaarApi\Exceptions\ArgumentValueEx;
    use VzaarApi\Exceptions\S3uploadEx;
    use VzaarApi\Client;
    use VzaarApi\Signature;

class S3Client
{

    protected $httpHandler;

    public static $VERBOSE = false;


    public function __construct($httpHandler = null)
    {

        if (is_null($httpHandler) === true) {
            if (extension_loaded('curl') === false) {
                exit(PHP_EOL."VZAAR_ERROR: CURL extension not loaded".PHP_EOL);
            }

            $this->httpHandler = new HttpCurl();
        } else {
            ArgumentTypeEx::assertInstanceOf(IHttpChannel::class, $httpHandler);

            $this->httpHandler = $httpHandler;
        }

    }//end __construct()


    public function uploadFile($signature, $filepath)
    {

        ArgumentTypeEx::assertInstanceOf(Signature::class, $signature);

        if (file_exists($filepath) === false) {
            throw new ArgumentValueEx('File does not exist: '.$filepath);
        }

        $filename = basename($filepath);
        $filesize = filesize($filepath);

        $cfg['uri']     = $signature->upload_hostname;
        $cfg['method']  = 'POST';
        $cfg['headers'] = array('Enclosure-Type: multipart/form-data');

        $data = array(
                 'x-amz-meta-uploader'   => Client::UPLOADER.Client::VERSION,
                 'AWSAccessKeyId'        => $signature->access_key_id,
                 'Signature'             => $signature->signature,
                 'acl'                   => $signature->acl,
                 'bucket'                => $signature->bucket,
                 'policy'                => $signature->policy,
                 'success_action_status' => $signature->success_action_status,
                 'key'                   => str_replace('${filename}', $filename, $signature->key),
                 'file'                  => $filepath,
                );

        if (isset($signature->parts) === true) {
            $data['chunks'] = $signature->parts;

            $chunk = 0;
            $key   = $data['key'];

            $chunksize = $signature->part_size_in_bytes;

            $file = @fopen($data['file'], "rb");

            if ($file === false) {
                throw new ArgumentValueEx("Unable to open file ($filename)");
            }

            while (feof($file) === false) {
                $data['chunk'] = $chunk;
                $data['key']   = $key.'.'.$chunk;

                $data['file'] = \fread($file, $signature->part_size_in_bytes);

                $cfg['data'] = $data;

                if (self::$VERBOSE === true) {
                    /*
                        Do not show file content in log.
                    */

                    $data['file'] = $filepath;

                    $log  = PHP_EOL."VZAAR_LOG_START".PHP_EOL;
                    $log .= PHP_EOL."*** AWS S3 POST DATA ***".PHP_EOL;
                    $log .= implode("\r\n", $data).PHP_EOL;
                    $log .= PHP_EOL."VZAAR_LOG_END".PHP_EOL;

                    error_log($log);
                }

                $result = $this->httpHandler->httpRequest($cfg);

                if ($result['httpCode'] === 201) {
                    $chunk++;
                } else {
                    /*
                        Throw S3uploadEx
                    */

                    break;
                }
            }//end while

            fclose($file);

            if ($result['httpCode'] === 201) {
                return true;
            } else {
                $msg  = PHP_EOL.'Problem occured with file upload to AWS S3.'.PHP_EOL;
                $msg .= $result['httpResponse'].PHP_EOL;

                throw new S3uploadEx($msg);
            }
        } else {
            $data['file'] = new \CURLFile($data['file']);

            $cfg['data'] = $data;

            if (self::$VERBOSE === true) {
                /*
                    Do not show file content in log.
                */

                $data['file'] = $filepath;

                $log  = PHP_EOL."VZAAR_LOG_START".PHP_EOL;
                $log .= PHP_EOL."*** AWS S3 POST DATA ***".PHP_EOL;
                $log .= implode("\r\n", $data).PHP_EOL;
                $log .= PHP_EOL."VZAAR_LOG_END".PHP_EOL;

                error_log($log);
            }

            $result = $this->httpHandler->httpRequest($cfg);

            if ($result['httpCode'] === 201) {
                return true;
            } else {
                $msg  = PHP_EOL.'Problem occured with file upload to AWS S3.'.PHP_EOL;
                $msg .= $result['httpResponse'].PHP_EOL;

                throw new S3uploadEx($msg);
            }
        }//end if

    }//end uploadFile()


}//end class
