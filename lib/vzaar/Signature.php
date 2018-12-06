<?php
    namespace VzaarApi;

    use VzaarApi\Resources\Record;
    use VzaarApi\Exceptions\ArgumentTypeEx;
    use VzaarApi\Exceptions\ArgumentValueEx;
    use VzaarApi\Client;

class Signature extends Record
{

    protected static $endpoint;


    public function __construct($client = null)
    {

        self::$endpoint = '/signature';

        parent::__construct($client);

    }//end __construct()


    protected function createSingle($params = null)
    {

        if (is_null($params) === false) {
            ArgumentTypeEx::assertIsArray($params);

            if (array_key_exists('uploader', array_change_key_case($params, CASE_LOWER)) === false)
                $params['uploader'] = Client::UPLOADER.Client::VERSION;
        } else {
            $params['uploader'] = Client::UPLOADER.Client::VERSION;
        }

        self::$endpoint = '/signature/single/2';

        $this->crudCreate($params);

    }//end createSingle()


    protected function createMultipart($params)
    {

        if (is_null($params) === false) {
            ArgumentTypeEx::assertIsArray($params);
        }

        if (array_key_exists('uploader', array_change_key_case($params, CASE_LOWER)) === false) {
            $params['uploader'] = Client::UPLOADER.Client::VERSION;
        }

        self::$endpoint = '/signature/multipart/2';

        $this->crudCreate($params);

    }//end createMultipart()


    protected function createFromFile($filepath)
    {

        if (file_exists($filepath) === false) {
            throw new ArgumentValueEx('File does not exist: '.$filepath);
        }

        $filename = basename($filepath);
        $filesize = filesize($filepath);

        $request['filename'] = $filename;
        $request['filesize'] = $filesize;

        if ($filesize >= Client::MULTIPART_MIN_SIZE) {
            $this->createMultipart($request);
        } else {
            $this->createSingle($request);
        }

    }//end createFromFile()


    public static function create($params,$client = null)
    {
        $signature = new self($client);
        $signature->createFromFile($params);

        return $signature;

    }//end create()


    public static function single($params,$client = null)
    {
        $signature = new self($client);
        $signature->createSingle($params);

        return $signature;

    }//end single()


    public static function multipart($params,$client = null)
    {
        $signature = new self($client);
        $signature->createMultipart($params);

        return $signature;

    }//end multipart()


}//end class
