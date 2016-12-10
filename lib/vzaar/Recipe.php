<?php
    namespace VzaarApi;

    use VzaarApi\Resources\Record;
    use VzaarApi\Exceptions\ArgumentTypeEx;
    use VzaarApi\Client;

class Recipe extends Record
{

    protected static $endpoint;


    public function __construct($client = null)
    {

        self::$endpoint = '/ingest_recipes';

        parent::__construct($client);

    }//end __construct()


    public function save($params = null)
    {

        $this->crudUpdate($params);

    }//end save()


    public function delete()
    {

        $this->crudDelete();

    }//end delete()


    public static function find($params,$client = null)
    {

        $recipe = new self($client);
        $recipe->crudRead($params);

        return $recipe;

    }//end find()


    public static function create($params,$client = null)
    {
        $recipe = new self($client);
        $recipe->crudCreate($params);

        return $recipe;

    }//end create()


}//end class
