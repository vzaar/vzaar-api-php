<?php
    namespace VzaarApi;

    use VzaarApi\Resources\Record;
    use VzaarApi\Resources\S3Client;
    use VzaarApi\Exceptions\ArgumentTypeEx;
    use VzaarApi\Exceptions\ArgumentValueEx;
    use VzaarApi\Client;
    use VzaarApi\Signature;
    use VzaarApi\LinkUpload;


class Video extends Record
{

    protected static $endpoint;

    protected $s3client;


    public function __construct($client = null, $s3client = null)
    {

        self::$endpoint = '/videos';

        parent::__construct($client);

        if (is_null($s3client) === true) {
            $this->s3client = new S3Client();
        } else {
            ArgumentTypeEx::assertInstanceOf(S3Client::class, $s3client);

            $this->s3client = $s3client;
        }

    }//end __construct()


    /**
     * @param array $params
     *
     * ['filepath'] : string
     * ['url'] : string
     * ['guid'] : string
     */
    protected function createData($params)
    {

        ArgumentTypeEx::assertIsArray($params);

        /*
            Check: at least one or all expected parameters recieved.
        */

        if (((isset($params['guid']) xor isset($params['url'])) xor isset($params['filepath'])) === true) {
            /*
                Check: all parameters are set - throw exception.
            */

            if ((isset($params['guid']) and isset($params['url']) and isset($params['filepath'])) === true) {
                throw new ArgumentValueEx('Only one of the parameters: guid or url or filepath expected');
            }

            $result = null;

            if (isset($params['guid']) === true) {
                $this->guidCreate($params);
            } else if (isset($params['url']) === true) {
                $result = $this->urlCreate($params);
            } else if (isset($params['filepath']) === true) {
                $this->uploadCreate($params);
            }
        } else {
            /*
                Expected parameter is not received.
            */

            throw new ArgumentValueEx('Only one of the parameters: guid or url or filepath expected');
        }//end if

        return $result;

    }//end createData()


    protected function guidCreate($params)
    {

        $this->crudCreate($params);

    }//end guidCreate()


    protected function urlCreate($params)
    {

        if (array_key_exists('uploader', array_change_key_case($params, CASE_LOWER)) === false)
            $params['uploader'] = Client::UPLOADER.Client::VERSION;

        return LinkUpload::create($params, $this->httpClient);

    }//end urlCreate()


    protected function uploadCreate($params)
    {

        if(file_exists($params['filepath']) === false)
            throw new ArgumentValueEx('File does not exist: '.$params['filepath']);

        /*
            Create signature.
        */

        $signature = Signature::create($params['filepath'], $this->httpClient);

        /*
            Upload file.
        */

        $this->s3client->uploadFile($signature, $params['filepath']);

        /*
            Create video from guid.
        */

        unset($params['filepath']);
        $params['guid'] = $signature->guid;

        $result = $this->createData($params);

    }//end uploadCreate()


    public static function find($params, $client = null)
    {

        $video = new self($client);
        $video->crudRead($params);

        return $video;

    }//end find()


    public static function create($params, $client = null, $s3client = null)
    {

        $video  = new self($client, $s3client);
        $result = $video->createData($params);

        /*
            Check if Video object created by LinkUpload class.
        */

        $linkClass = LinkUpload::class;
        if (($result instanceof $linkClass) === true)
            return $result;

        return $video;

    }//end create()


}//end class
