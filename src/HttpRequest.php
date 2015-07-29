<?php

/**
 * HttpRequest
 *
 * @author Skitsanos
 */
//application/x-www-form-urlencoded
//application/json
//text/xml

class HttpRequest
{

    protected $c;
    protected $url;
    var $method = "GET";
    var $preventCaching = true;
    var $useSsl = true;
    var $headers = array();
    var $verbose = false;
    var $uploadMode = false;

    function __construct($url)
    {
        if (!function_exists('curl_init')) {
            echo "Function curl_init, used by HttpRequest does not exist.\n";
        }
        $this->url = $url;
        $this->c = curl_init($this->url);
    }

    function send($data = null, $filepath = null)
    {
        if (count($this->headers) > 0) {
            curl_setopt($this->c, CURLOPT_HEADER, false);
            curl_setopt($this->c, CURLOPT_HTTPHEADER, $this->headers);
        }

        curl_setopt($this->c, CURLOPT_RETURNTRANSFER, true);

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
                curl_setopt($this->c, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data != null)
                    curl_setopt($this->c, CURLOPT_POSTFIELDS, $data);
                break;
        }

        curl_setopt($this->c, CURLOPT_VERBOSE, $this->verbose);

        if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off')) {
            curl_setopt($this->c, CURLOPT_FOLLOWLOCATION, true);
            $output = curl_exec($this->c);
        } else {
            curl_setopt($this->c, CURLOPT_FOLLOWLOCATION, false);
            $output = $this->curlExec($this->c);
        }

        return $output;
    }

    function curlExec($ch)
    {

        $newUrl = '';
        $maxRedirection = 10;
        do {
            if ($maxRedirection < 1) die('Error: reached the limit of redirections');
            if (!empty($newUrl)) curl_setopt($ch, CURLOPT_URL, $newUrl); // redirect needed

            $curlResult = curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($code == 301 || $code == 302 || $code == 303 || $code == 307) {
                preg_match('/Location:(.*?)\n/', $curlResult, $matches);
                $newUrl = trim(array_pop($matches));
                curl_close($ch);

                $maxRedirection--;
                continue;
            } else // no more redirection
            {
                $code = 0;
                curl_close($ch);
            }
        } while ($code);
        return $curlResult;
    }
}

?>
