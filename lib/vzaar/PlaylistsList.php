<?php
    namespace VzaarApi;
    
    use VzaarApi\Resources\RecordsList;
    use VzaarApi\Exceptions\ArgumentTypeEx;
    use VzaarApi\Client;
    use VzaarApi\Playlist;
    
class PlaylistsList extends RecordsList
{
    
    protected static $endpoint;
    protected static $recordClass;
    
    
    public function __construct($client = null)
    {
        
        self::$endpoint    = '/feeds/playlists';
        self::$recordClass = Playlist::class;
        
        parent::__construct($client);
        
    }//end __construct()
    
    
}//end class
