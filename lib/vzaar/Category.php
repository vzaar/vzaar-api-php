<?php
    namespace VzaarApi;

    use VzaarApi\Resources\Record;
    use VzaarApi\Exceptions\ArgumentTypeEx;
    use VzaarApi\CategoriesList;

class Category extends Record
{

    protected static $endpoint;


    public function __construct($client = null)
    {

        self::$endpoint = '/categories';

        parent::__construct($client);

    }//end __construct()


    public function subtree($params = null)
    {

        $this->assertRecordValid();

        return CategoriesList::subtree($this->id, $params, $this->httpClient);

    }//end subtree()


    public static function find($params, $client = null)
    {
        $category = new self($client);
        $category->crudRead($params);

        return $category;

    }//end find()


}//end class
