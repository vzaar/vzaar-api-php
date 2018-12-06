<?php
    namespace VzaarApi\Resources;

    use VzaarApi\Resources\IHttpChannel;
    use VzaarApi\Exceptions\ArgumentTypeEx;
    use VzaarApi\Exceptions\ArgumentValueEx;
    use VzaarApi\Exceptions\ConnectionEx;

class HttpCurl implements IHttpChannel
{

    public static $CURLOPT_VERBOSE = false;

    public static $CURLOPT_CAINFO         = false;
    public static $CURLOPT_SSL_VERIFYPEER = 0;
    public static $CURLOPT_SSL_VERIFYHOST = 0;

    public static $CURLOPT_CONNECTTIMEOUT = false;


    /**
     * @param array $cfg
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
     */
    public function httpRequest($cfg)
    {

        ArgumentTypeEx::assertIsArray($cfg);

        if (isset($cfg['method']) === true) {
            $method = $cfg['method'];
        } else {
            $method = null;
        }

        if (isset($cfg['headers']) === true) {
            $headers = $cfg['headers'];
        } else {
            $headers = array();
        }

        if (isset($cfg['uri']) === true) {
            $uri = $cfg['uri'];
        } else {
            $uri = null;
        }

        if (isset($cfg['data']) === true) {
            $data = $cfg['data'];
        } else {
            $data = '';
        }

        $options = array();

        switch($method){
        case 'GET':
            $options[CURLOPT_HTTPGET] = true;
            break;
        case 'POST':
            $options[CURLOPT_POST] = true;

            if (empty($data) === false) {
                $options[CURLOPT_POSTFIELDS] = $data;
            }
            break;
        case 'PATCH':
            $options[CURLOPT_CUSTOMREQUEST] = "PATCH";

            if (empty($data) === false) {
                $options[CURLOPT_POSTFIELDS] = $data;
            }
            break;
        case 'DELETE':
            $options[CURLOPT_CUSTOMREQUEST] = "DELETE";
            break;
        default:
            throw new ArgumentValueEx("Http method missing");
        }//end switch

        $options[CURLOPT_URL] = $uri;

        $options[CURLOPT_HEADER] = true;

        if (empty($headers) === false) {
            $options[CURLOPT_HTTPHEADER] = $headers;
        }

        $options[CURLOPT_RETURNTRANSFER] = true;

        if (self::$CURLOPT_CONNECTTIMEOUT !== false) {
            $options[CURLOPT_CONNECTTIMEOUT] = self::$CURLOPT_CONNECTTIMEOUT;
        }

        $options[CURLOPT_FORBID_REUSE]  = true;
        $options[CURLOPT_FRESH_CONNECT] = true;

        if (self::$CURLOPT_CAINFO !== false) {
            $options[CURLOPT_CAINFO] = self::$CURLOPT_CAINFO;
        }

        $options[CURLOPT_SSL_VERIFYPEER] = self::$CURLOPT_SSL_VERIFYPEER;
        $options[CURLOPT_SSL_VERIFYHOST] = self::$CURLOPT_SSL_VERIFYHOST;

        $options[CURLOPT_VERBOSE] = self::$CURLOPT_VERBOSE;

        $cs = curl_init();

        if ($cs === false) {
            $no  = curl_errno($cs);
            $msg = curl_error($cs);

            throw new ConnectionEx('Connetion failed: '.$no.' : '.$msg);
        }

        curl_setopt_array($cs, $options);

        $result = curl_exec($cs);

        if ($result === false) {
            $no  = curl_errno($cs);
            $msg = curl_error($cs);

            curl_close($cs);

            throw new ConnectionEx('Connetion failed: '.$no.' : '.$msg);
        }

        $response = array();
        $response['httpCode']     = curl_getinfo($cs, CURLINFO_HTTP_CODE);
        $response['httpResponse'] = $result;

        curl_close($cs);

        return $response;

    }//end httpRequest()


}//end class
