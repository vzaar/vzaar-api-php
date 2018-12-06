<?php
    namespace VzaarApi;
    
    use VzaarApi\Resources\Record;
    use VzaarApi\Exceptions\ArgumentTypeEx;
    use VzaarApi\Client;

class ImageFrame extends Record 
{

    protected static $endpoint;


    public function __construct($client = null)
    {
        
        self::$endpoint = '/videos';
        
        parent::__construct($client);
        
    }//end __construct()

/**
     * @param array $params
     *
     * ['filepath'] : string
     */
    protected function createData($params)
    {

        ArgumentTypeEx::assertIsArray($params);

        if(isset($params['filepath']) === false){
            throw new ArgumentValueEx('Expected parameter filepath');
        }

        $result = [
            'image' => new \CURLFile($params['filepath'])
        ];
        
        return $result;
    }//end createData()


    public static function create($id,$params,$client = null)
    {
        $vod = $id.'/image';

        $imageFrame = new self($client);
        $result = $imageFrame->createData($params);
        $imageFrame->crudCreate($result, $vod, 'multipart/form-data');
        
        return $imageFrame;
        
    }//end create()

    public static function set($id,$params,$client = null)
    {
        $vod = $id.'/image';

        $imageFrame = new self($client);
        $imageFrame->crudPatch($params, $vod);
        
        return $imageFrame;
        
    }//end create()
}