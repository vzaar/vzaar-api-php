<?php
    namespace VzaarApi\Resources;

    use VzaarApi\Exceptions\VzaarError;
    use VzaarApi\Exceptions\RecordEx;
    use VzaarApi\Exceptions\ArgumentTypeEx;
    use VzaarApi\Client;

abstract class Record
{


    protected $httpClient;

    protected $recordPath;
    protected $recordQuery;
    protected $recordBody;
    protected $recordData;


    protected function __construct($client = null)
    {

        if (is_null($client) === true) {
            $this->httpClient = new Client();
        } else {
            ArgumentTypeEx::assertInstanceOf(Client::class, $client);

            $this->httpClient = $client;
        }

        if (isset(static::$endpoint) === false) {
            throw new VzaarError('Endpoint have to be configred');
        }

        $this->recordData = \json_decode('{"data":{}}');

        $this->recordBody  = array();
        $this->recordQuery = array();
        $this->recordPath  = '';

    }//end __construct()


    public function getClient()
    {

        return $this->httpClient;

    }//end getClient()


    public function edited()
    {

        return !empty($this->recordBody);

    }//end edited()


    public function checkRateLimit()
    {

        return $this->httpClient->checkRateLimit();

    }//end checkRateLimit()


    public function checkRateRemaining()
    {

        return $this->httpClient->checkRateRemaining();

    }//end checkRateRemaining()


    public function checkRateReset()
    {

        return $this->httpClient->checkRateReset();

    }//end checkRateReset()


    protected function requestClient($method)
    {

        $recordRequest           = array();
        $recordRequest['method'] = $method;
        $recordRequest['endpoint']    = static::$endpoint;
        $recordRequest['recordPath']  = $this->recordPath;
        $recordRequest['recordQuery'] = $this->recordQuery;
        $recordRequest['recordData']  = $this->recordBody;

        $result = $this->httpClient->clientSend($recordRequest);

        /*
            Reset request parameters.
        */

        $this->recordBody  = array();
        $this->recordQuery = array();
        $this->recordPath  = '';

        return $result;

    }//end requestClient()


    protected function updateRecord($data)
    {

        ArgumentTypeEx::assertInstanceOf(\stdClass::class, $data);

        if (property_exists($data, 'data') === false) {
            throw new VzaarError("Received data are not valid");
        }

        $this->recordData = $data;

    }//end updateRecord()


    protected function cleanRecord()
    {

        $this->recordData       = new \stdClass();
        $this->recordData->data = new \stdClass();

    }//end cleanRecord()


    protected function crudCreate($params = null)
    {

        if (is_null($params) === false) {
            ArgumentTypeEx::assertIsArray($params);

            $this->recordBody = $params;
        }

        $httpMethod = 'POST';
        $result     = $this->requestClient($httpMethod);

        $this->updateRecord($result);

    }//end crudCreate()


    protected function crudRead($path = null, $params = null)
    {

        if (is_null($path) === false) {
            $this->recordPath = $path;
        }

        if (is_null($params) === false) {
            ArgumentTypeEx::assertIsArray($params);

            $this->recordQuery = $params;
        }

        $httpMethod = 'GET';
        $result     = $this->requestClient($httpMethod);

        $this->updateRecord($result);

    }//end crudRead()


    protected function crudUpdate($params = null)
    {

        $this->assertRecordValid();
        $this->recordPath = $this->id;

        /*
            Unset the 'id' if set as object property.
            The data become now resource path.
            This prevents 'id' from being sent as body parameter.
        */

        unset($this->recordBody['id']);

        if (is_null($params) === false) {
            ArgumentTypeEx::assertIsArray($params);

            /*
                Any attributes chaged using object properties
                will be overwritten with the parameters.
            */

            $this->recordBody = $params;
        }

        if (empty($this->recordBody) === false) {
            $httpMethod = 'PATCH';
            $result     = $this->requestClient($httpMethod);

            $this->updateRecord($result);
        }

    }//end crudUpdate()


    protected function crudDelete()
    {

        $this->assertRecordValid();
        $this->recordPath = $this->id;

        /*
            Clear the recordBody array, in case any object properties
            were modified before the delete.
        */

         $this->recordBody = array();

        $httpMethod = 'DELETE';
        $this->requestClient($httpMethod);

        $this->cleanRecord();

    }//end crudDelete()


    protected function assertRecordValid()
    {

        if (isset($this->id) === false) {
            throw new RecordEx("Record corrupted, missing id");
        }

    }//end assertRecordValid()


    public function __get($name)
    {

        /*
            Magic method overriding.
        */

        if (isset($this->recordBody[$name]) === true)
            return $this->recordBody[$name];

        return $this->recordData->data->{$name};

    }//end __get()


    public function __set($name,$value)
    {

        /*
            Magic method overriding.
        */

        if (isset($this->recordData->data->{$name}) === true) {
            if ($this->recordData->data->{$name} !== $value) {
                $this->recordBody[$name] = $value;
            } else {
                /*
                    If property value restored back to old value
                    remove the argument from parameters to update array.
                */

                unset($this->recordBody[$name]);
            }
        } else {
            $this->recordBody[$name] = $value;
        }

    }//end __set()


    public function __isset($name)
    {

        /*
            Magic method overriding.
        */

        return (isset($this->recordData->data->{$name}) | isset($this->recordBody[$name]));

    }//end __isset()


    public function __unset($name)
    {

        /*
            Magic method overriding.
        */

        unset($this->recordData->data->{$name});
        unset($this->recordBody[$name]);

    }//end __unset()


}//end class
