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


    public static function create($params, $client = null)
    {

        $category = new self($client);
        $category->crudCreate($params);

        return $category;

    }//end create()


    public function save($params = null)
    {

        $this->crudUpdate($params);

    }//end save()


    public function delete()
    {

        $this->crudDelete();

    }//end delete()


}//end class
