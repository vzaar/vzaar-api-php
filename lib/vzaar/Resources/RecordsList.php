<?php
    namespace VzaarApi\Resources;

    use VzaarApi\Resources\Record;
    use VzaarApi\Exceptions\RecordEx;
    use VzaarApi\Client;

abstract class RecordsList extends Record implements \Iterator, \Countable
{

    protected $itemCursor;


    protected function __construct($client = null)
    {

        if (isset(static::$recordClass) === false) {
            throw new VzaarError('Record type have to be configred');
        }

        if (class_exists(static::$recordClass) === false) {
            throw new VzaarError('Record type class is missing');
        }

        parent::__construct($client);

        $this->itemCursor = 0;

    }//end __construct()


    protected function updateRecord($data)
    {

        parent::updateRecord($data);

        /*
            After the 'parent::updateRecord' is executed
            the 'recordData' contains dynamically defined
            properties: 'data' and 'meta'.
        */

        foreach ($this->recordData->data as $key => $value) {
            $obj            = new static::$recordClass($this->httpClient);
            $json_obj       = new \stdClass();
            $json_obj->data = $value;
            $obj->updateRecord($json_obj);

            $this->recordData->data[$key] = $obj;
        }

    }//end updateRecord()


    public function firstPage()
    {

        if (isset($this->first) === true) {
            $url = $this->first;
            $this->getPage($url);

            return true;
        } else {
            return false;
        }

    }//end firstPage()


    public function nextPage()
    {

        if (isset($this->next) === true) {
            $url = $this->next;

            $this->getPage($url);

            return true;
        } else {
            return false;
        }

    }//end nextPage()


    public function previousPage()
    {

        if (isset($this->previous) === true) {
            $url = $this->previous;
            $this->getPage($url);

            return true;
        } else {
            return false;
        }

    }//end previousPage()


    public function lastPage()
    {

        if (isset($this->last) === true) {
            $url = $this->last;
            $this->getPage($url);

            return true;
        } else {
            return false;
        }

    }//end lastPage()


    protected function getPage($url)
    {

        $queryString = \parse_url($url, PHP_URL_QUERY);
        $queryParams = \explode('&', $queryString);

        $query = array();
        foreach ($queryParams as $value) {
            if (preg_match('/(.+)=(.*)/', $value, $items) === 1) {
                $query[$items[1]] = $items[2];
            }
        }

        $this->crudRead(null, $query);

    }//end getPage()


    public static function paginate($params = null, $client = null)
    {

        $list = new static($client);
        $list->crudRead(null, $params);

        return $list;

    }//end paginate()


    public static function each_item($params = null, $client = null)
    {

        $list = new static($client);
        $list->crudRead(null, $params);

        do {
            foreach ($list as $key => $value) {
                yield $value;
            }
        } while ($list->nextPage() === true);

    }//end each_item()


    public function count()
    {

        /*
            Countable interface method.

            abstract public int Countable::count ( void )
        */

        return \count($this->recordData->data);

    }//end count()


    public function rewind()
    {

        /*
            Array Iterator method.
        */

        $this->itemCursor = 0;

    }//end rewind()


    public function valid()
    {

        /*
            Array Iterator method.
        */

        return isset($this->recordData->data[$this->itemCursor]);

    }//end valid()


    public function current()
    {

        /*
            Array Iterator method.
        */

        return $this->recordData->data[$this->itemCursor];

    }//end current()


    public function key()
    {

        /*
            Array Iterator method.
        */

        return $this->itemCursor;

    }//end key()


    public function next()
    {

        /*
            Array Iterator method.
        */

        ++$this->itemCursor;

    }//end next()


    public function __get($name)
    {

        /*
            Magic method overriding.
        */

        if (isset($this->recordData->meta->links->{$name}) === true) {
            $value = $this->recordData->meta->links->{$name};
        } else {
            $value = $this->recordData->meta->{$name};
        }

        return $value;

    }//end __get()


    public function __set($name, $value)
    {

        /*
            Magic method overriding.
        */

        RecordEx::isReadonly();

    }//end __set()


    public function __isset($name)
    {

        /*
            Magic method overriding.
        */

        return (isset($this->recordData->meta->links->{$name}) | isset($this->recordData->meta->{$name}));

    }//end __isset()


    public function __unset($name)
    {

        /*
            Magic method overriding.
        */

        RecordEx::isReadonly();

    }//end __unset()


}//end class
