<?php
    namespace VzaarApi;
    
    use VzaarApi\Resources\Record;
    use VzaarApi\Exceptions\ArgumentTypeEx;
    use VzaarApi\Client;
    
class Playlist extends Record
{
    
    protected static $endpoint;
    
    
    public function __construct($client = null)
    {
        
        self::$endpoint = '/feeds/playlists';
        
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
        
        $playlist = new self($client);
        $playlist->crudRead($params);
        
        return $playlist;
        
    }//end find()
    
    
    public static function create($params,$client = null)
    {
        $playlist = new self($client);
        $playlist->crudCreate($params);
        
        return $playlist;
        
    }//end create()
    
    
}//end class
