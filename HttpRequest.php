<?php

/**
 * HttpRequest
 *
 * @author Skitsanos
 */
//application/x-www-form-urlencoded
//application/json
//text/xml

class HttpRequest {

    protected $c;
    protected $url;
    var $method = "GET";
    var $preventCaching = true;
    var $useSsl = true;
    var $headers = array();
    var $verbose = false;
    var $uploadMode = false;

    function __construct($url) {
        if (!function_exists('curl_init')) {
            echo "Function curl_init, used by HttpRequest does not exist.\n";
        }
        $this->url = $url;
        $this->c = curl_init($this->url);
    }

    function send($data=null, $filepath=null) {
        if (count($this->headers) > 0) {
            curl_setopt($this->c, CURLOPT_HEADER, false);
            curl_setopt($this->c, CURLOPT_HTTPHEADER, $this->headers);
        }

        curl_setopt($this->c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->c, CURLOPT_FOLLOWLOCATION, true);

        if ($this->useSsl) {
            curl_setopt($this->c, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($this->c, CURLOPT_SSL_VERIFYHOST, 0);
        }

        if ($this->preventCaching) {
            curl_setopt($this->c, CURLOPT_FORBID_REUSE, true);
            curl_setopt($this->c, CURLOPT_FRESH_CONNECT, true);
        }

        if ($this->uploadMode) {
            //curl_setopt($this->c, CURLOPT_URL, $filepath);
			//curl_setopt($this->c, CURLOPT_UPLOAD, true);
			curl_setopt($this->c, CURLOPT_POST, true);
            $fp = fopen($filepath, 'r');
            curl_setopt($this->c, CURLOPT_INFILE, $fp);
            curl_setopt($this->c, CURLOPT_INFILESIZE, filesize($filepath));
        }

        switch (strtoupper($this->method)) {
            case 'POST':
                curl_setopt($this->c, CURLOPT_POST, true);
                if ($data != null)
                    curl_setopt($this->c, CURLOPT_POSTFIELDS, $data);
                break;

            case 'HEAD':
                curl_setopt($this->c, CURLOPT_NOBODY, true);
                break;

            case 'DELETE':
                curl_setopt($this->c, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;

            case 'PUT':
                curl_setopt($this->c, CURLOPT_PUT, true);
                if ($data != null)
                    curl_setopt($this->c, CURLOPT_POSTFIELDS, $data);
                break;
        }

        curl_setopt($this->c, CURLOPT_VERBOSE, $this->verbose);

        $output = curl_exec($this->c);

        curl_close($this->c);
        return $output;
    }

}
?>