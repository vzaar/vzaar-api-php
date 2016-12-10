<?php
    namespace VzaarApi\Resources;

interface IHttpChannel
{


    /**
     * @param array $cfg
     *
     * ['method'] : string
     * ['headers'] : array
     * ['uri'] : string
     * ['data'] : applicaiton/json | multipart/form-data (with file upload)
     *
     * @return array
     *
     * ['httpCode'] : string
     * ['httpResponse'] : string
     */
    public function httpRequest($cfg);


}//end interface
