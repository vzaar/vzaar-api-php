<?php
    namespace VzaarApi;

    use VzaarApi\Resources\HttpCurl;
    use VzaarApi\Resources\IHttpChannel;
    use VzaarApi\Exceptions\ClientErrorEx;
    use VzaarApi\Exceptions\ArgumentTypeEx;

class Client
{

    public static $client_id  = 'id';
    public static $auth_token = 'token';
    public static $version    = 'v2';
    public static $urlAuth    = false;

    public static $VERBOSE = false;

    const UPLOADER = 'Vzaar PHP SDK';
    const VERSION  = '2.0.0-alpha';

    const ONE_MB = (1024 * 1024);
    // 1 MB in Bytes
    const MULTIPART_MIN_SIZE = (5 * self::ONE_MB);
    // in Bytes
    protected $clientId;
    protected $clientAuthToken;
    protected $clientUrlAuth;

    protected $apiVersion = 'v2';
    protected $apiUrl     = 'https://api.vzaar.com/api';

    protected $httpHandler = null;
    protected $httpCode    = null;
    protected $httpHeaders = null;


    /**
     * @param array        $config
     *
     * ['client_id'] : string
     * ['auth_token'] : string
     * ['version'] : string
     * ['urlAuth'] : bool
     *
     * @param IHttpChannel $httpHandler
     */
    public function __construct($config = null, $httpHandler = null)
    {

        if (is_null($config) === false) {
            ArgumentTypeEx::assertIsArray($config);
        }

        if (isset($config['client_id']) === true) {
            $this->clientId = $config['client_id'];
        } else {
            $this->clientId = self::$client_id;
        }

        if (isset($config['auth_token']) === true) {
            $this->clientAuthToken = $config['auth_token'];
        } else {
            $this->clientAuthToken = self::$auth_token;
        }

        if (isset($config['version']) === true) {
            $this->apiVersion = $config['version'];
        } else {
            $this->apiVersion = self::$version;
        }

        if (isset($config['urlAuth']) === true) {
            $this->clientUrlAuth = $config['urlAuth'];
        } else {
            $this->clientUrlAuth = self::$urlAuth;
        }

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


    public function getClientId()
    {

        return $this->clientId;

    }//end getClientId()


    public function getAuthToken()
    {

        return $this->clientAuthToken;

    }//end getAuthToken()


    public function getApiVersion()
    {

        return $this->apiVersion;

    }//end getApiVersion()


    public function checkUrlAuth()
    {

        return $this->clientUrlAuth;

    }//end checkUrlAuth()


    public function checkRateLimit()
    {

        $value = null;
        if (isset($this->httpHeaders['X-RateLimit-Limit']) === true) {
            $value = $this->httpHeaders['X-RateLimit-Limit'];
        }

        return $value;

    }//end checkRateLimit()


    public function checkRateRemaining()
    {

        $value = null;
        if (isset($this->httpHeaders['X-RateLimit-Remaining']) === true) {
            $value = $this->httpHeaders['X-RateLimit-Remaining'];
        }

        return $value;

    }//end checkRateRemaining()


    public function checkRateReset()
    {

        $value = null;
        if (isset($this->httpHeaders['X-RateLimit-Reset']) === true) {
            $value = $this->httpHeaders['X-RateLimit-Reset'];
        }

        return $value;

    }//end checkRateReset()


    /**
     * @param array $recordRequest
     *
     * ['method'] : string
     * ['endpoint'] : string
     * ['recordPath'] : string
     * ['recordQuery'] : array
     * ['recordData'] : array
     */
    public function clientSend($recordRequest)
    {

        $httpRequest = array();

        $httpRequest['method'] = $recordRequest['method'];

        $httpRequest['headers'] = array();

        if ($this->clientUrlAuth === true) {
            $recordRequest['recordQuery'] = $this->addAuthUri($recordRequest['recordQuery']);
        } else {
            $httpRequest['headers'] = $this->addAuthHeaders();
        }

        $apiUrl = $this->httpUrl();
        $httpRequest['uri'] = $this->params2uri(
            $apiUrl,
            $recordRequest['endpoint'],
            $recordRequest['recordPath'],
            $recordRequest['recordQuery']
        );

        $httpRequest['data'] = '';

        if (empty($recordRequest['recordData']) === false) {
            $httpRequest['data'] = $this->object2json($recordRequest['recordData']);

            $httpRequest['headers'][] = 'Content-Type: application/json';
            $httpRequest['headers'][] = 'Content-Length: '.strlen($httpRequest['data']);
        }

        if (self::$VERBOSE === true) {
            $log  = PHP_EOL."VZAAR_LOG_START".PHP_EOL;
            $log .= PHP_EOL."*** REQUEST QUERY ***".PHP_EOL;
            $log .= $httpRequest['uri'].PHP_EOL;
            $log .= PHP_EOL."*** REQUEST HEADERS ***".PHP_EOL;
            $log .= implode("\r\n", $httpRequest['headers']).PHP_EOL;
            $log .= PHP_EOL."*** REQUEST BODY ***".PHP_EOL;
            $log .= $httpRequest['data'].PHP_EOL;
            $log .= PHP_EOL."VZAAR_LOG_END".PHP_EOL;

            error_log($log);
        }

        $httpResponse = $this->httpHandler->httpRequest($httpRequest);

        /*
            array $httpResponse

            ['httpCode'] : string
            ['httpResponse'] : string
        */

        $result = $this->httpResponse($httpResponse);

        /*
            mixed $result

            [stdClass | bool]

            (the stdClass object is result of json_decode() function,
            and contains record data received as json in http response body)

            (bool - equals true when record is sucessfuly deleted,
            otherwise an exception is thrown)
        */

        return $result;

    }//end clientSend()


    protected function httpResponse($httpResponse)
    {

        $split = explode("\r\n\r\n", $httpResponse['httpResponse'], 2);

        $responseHeaders = $split[0];

        /*
            Initialize as empty string.
        */

        $responseBody = '';

        if (isset($split[1]) === true) {
            $responseBody = $split[1];
        }

        if (strpos($responseHeaders, " 100 Continue") !== false) {
            $split = explode("\r\n\r\n", $responseBody, 2);

            $responseHeaders = $split[0];

            /*
                Initialize as empty string.
             */

            $responseBody = '';

            if (isset($split[1]) === true) {
                $responseBody = $split[1];
            }
        }

        $this->httpCode    = $httpResponse['httpCode'];
        $this->httpHeaders = $this->headers2array($responseHeaders);

        if (self::$VERBOSE === true) {
            $log  = PHP_EOL."VZAAR_LOG_START".PHP_EOL;
            $log .= PHP_EOL."*** RESPONSE HTTP CODE ***".PHP_EOL;
            $log .= $this->httpCode.PHP_EOL;
            $log .= PHP_EOL."*** RESPONSE HTTP HEADERS ***".PHP_EOL;
            $log .= implode("\r\n", $this->array2headers($this->httpHeaders)).PHP_EOL;
            $log .= PHP_EOL."*** RESPONSE HTTP BODY ***".PHP_EOL;
            $log .= $responseBody.PHP_EOL;
            $log .= PHP_EOL."VZAAR_LOG_END".PHP_EOL;

            error_log($log);
        }

        if ($this->httpCode === 200 || $this->httpCode === 201) {
            /*
                Successful Status Codes (2xx).

                200 - OK
                201 - Created
            */

            $response = $this->json2object($responseBody);
        } else if ($this->httpCode === 204) {
            /*
                Successful Status Codes (2xx).

                204 - No Content
            */

            if (empty($responseBody) === false) {
                throw new ClientErrorEx('No content expected with this response');
            }

            $response = true;
        } else if ($this->httpCode === 400
            || $this->httpCode === 401
            || $this->httpCode === 403
            || $this->httpCode === 404
            || $this->httpCode === 422
            || $this->httpCode === 429
            || $this->httpCode === 500
        ) {
            /*
                Client Error Status Codes (4xx).

                400 - Bad Request
                401 - Unauthorized
                403 - Forbidden
                404 - Not Found
                422 - Unprocessable Entity
                429 - To many Requests

                Server Error Status Codes (5xx).

                500 - Server error
            */

            $response = $this->json2object($responseBody);

            if (property_exists($response, 'errors') === false) {
                throw new ClientErrorEx('Response data: response not correct');
            }

            $errors = array();

            foreach ($response->errors as $key => $error) {
                $errors[] = $error->message.' : '.$error->detail;
            }

            throw new ClientErrorEx('HttpCode: '.$this->httpCode.' Details: '.implode("\n", $errors));
        } else {
            if (isset($this->httpCode) === true) {
                $code = $this->httpCode;
            } else {
                $code = 'Unknown';
            }

            throw new ClientErrorEx('Unknown response from server. HttpCode: '.$code);
        }//end if

        return $response;

    }//end httpResponse()


    protected function httpUrl()
    {

        return implode('/', array($this->apiUrl, $this->apiVersion));

    }//end httpUrl()


    protected function addAuthHeaders($headers = null)
    {

        $credentials = array(
                        'X-Client-Id:'.$this->clientId,
                        'X-Auth-Token:'.$this->clientAuthToken,
                       );

        if (is_array($headers) === true) {
            return array_merge($headers, $credentials);
        } else {
            return $credentials;
        }

    }//end addAuthHeaders()


    protected function addAuthUri($queryParams)
    {

        $credentials = array(
                        'client_id'  => $this->clientId,
                        'auth_token' => $this->clientAuthToken,
                       );

        if (is_array($queryParams) === true) {
            return array_merge($queryParams, $credentials);
        } else {
            return $credentials;
        }

    }//end addAuthUri()


    protected function object2json($obj)
    {

        $json = json_encode($obj);

        if (json_last_error() !== \JSON_ERROR_NONE) {
            throw new ClientErrorEx('Request data: JSON encoding failed - '.json_last_error_msg());
        }

        return $json;

    }//end object2json()


    protected function json2object($json)
    {

        $obj = json_decode($json);

        if (json_last_error() !== \JSON_ERROR_NONE) {
            throw new ClientErrorEx('Response data: JSON not valid - '.json_last_error_msg());
        }

        /*
            Bugfix begin.

            Bug#68938: bugs.php.net/bug.php?id=68938

            Issue fixed in PHP7
        */

        if(empty($obj) === true)
            throw new ClientErrorEx('Response data: JSON not valid - Syntax error');

        /*
            Bugfix end.

            Bug#68938: bugs.php.net/bug.php?id=68938

            Issue fixed in PHP7
        */

        return $obj;

    }//end json2object()


    protected function params2uri($httpUrl,$endpointUrn, $pathParams, $queryParams)
    {

        $uri  = $httpUrl;
        $uri .= $endpointUrn;

        if (empty($pathParams) === false) {
            $uri .= '/'.$pathParams;
        }

        if (empty($queryParams) === false) {
            $uri .= '?'.http_build_query($queryParams);
        }

        return $uri;

    }//end params2uri()


    protected function headers2array($headers)
    {

        $headers = explode("\r\n", $headers);

        $result = array();

        foreach ($headers as &$header) {
            if (preg_match('/([^:]+): (.*)/', $header, $matches) === 1) {
                $result[$matches[1]] = $matches[2];
            }
        }

        return $result;

    }//end headers2array()


    protected function array2headers($headers)
    {
        $result = array();
        if (is_array($headers) === true) {
            foreach ($headers as $key => $value) {
                $result[] = $key.': '.$value;
            }
        }

        return $result;

    }//end array2headers()


}//end class
