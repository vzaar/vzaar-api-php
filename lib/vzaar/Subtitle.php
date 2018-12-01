<?php
    namespace VzaarApi;
    
    use VzaarApi\Resources\Record;
    use VzaarApi\Exceptions\ArgumentTypeEx;
    use VzaarApi\Exceptions\ArgumentValueEx;
    use VzaarApi\Client;

class Subtitle extends Record
{

    protected static $endpoint;
    private $vodId;

    public function __construct($client = null)
    {
        
        self::$endpoint = '/videos';
        
        parent::__construct($client);
        
    }//end __construct()

    /**
     * @param array $params
     *
     * ['code'] : string
     * ['title'] : string
     * ['content'] : string
     * ['filepath'] : string
     */
    protected function createData($params)
    {

        ArgumentTypeEx::assertIsArray($params);

        /*
            Check: at least one or all expected parameters recieved.
        */

        if (((isset($params['content'])) xor isset($params['filepath'])) === true) {
            /*
                Check: all parameters are set - throw exception.
            */

            if ((isset($params['content']) and isset($params['filepath'])) === true) {
                throw new ArgumentValueEx('Only one of the parameters: content or filepath expected');
            }

            $result = null;

            if (isset($params['content']) === true) {
                $result = $params;
            } else if (isset($params['filepath']) === true) {
                $filepath = $params['filepath'];
                unset($params['filepath']);
                $params['file'] = new \CURLFile($filepath);
                $result = $params;
            }
        } else {
            /*
                Expected parameter is not received.
            */

            throw new ArgumentValueEx('Only one of the parameters: content or filepath expected');
        }//end if

        return $result;

    }//end createData()

    public static function create($id, $params, $client = null)
    {
        $path = $id.'/subtitles';


        $playlist = new self($client);
        $result = $playlist->createData($params);
        if(isset($result['file']) === true){
            $playlist->crudCreate($result, $path, 'multipart/form-data');
        }else{
            $playlist->crudCreate($result, $path);
        }
        
        return $playlist;
        
    }//end create()

    public function save($id, $params = null)
    {
        $this->assertRecordValid();

        $path = $id."/subtitles/".$this->id;
        
        $this->crudUpdate($params, $path);
        
    }//end save()

    public function delete($id)
    {
        $this->assertRecordValid();

        $path = $id."/subtitles/".$this->id;

        $this->crudDelete($path);

    }//end delete()
}