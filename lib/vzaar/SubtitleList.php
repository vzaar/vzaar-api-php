<?php
    namespace VzaarApi;

    use VzaarApi\Resources\RecordsList;
    use VzaarApi\Exceptions\ArgumentTypeEx;
    use VzaarApi\Client;
    use VzaarApi\Subtitle;

class SubtitleList extends RecordsList
{
    protected static $endpoint;
    protected static $recordClass;

    public function __construct($client = null)
    {

        self::$endpoint    = '/videos';
        self::$recordClass = Subtitle::class;

        parent::__construct($client);

    }//end __construct()

    public static function each_item_for_vod($vodId, $params = null, $client = null)
    {
        $path = $vodId.'/subtitles';

        return parent::each_item($params, $client, $path);

    }//end each_item()


    public static function each_item($params = null, $client = null, $path = null)
    {

        throw new VzaarError('each_item not supported on SubtitleList, use each_item_for_vod');

    }//end each_item()
}