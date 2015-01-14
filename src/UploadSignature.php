<?php
//package com.vzaar;

/**
 * Response from signature request that contains the GUID and an AWS S3
 * signature. With these credentials the user will then be able upload a
 * file into <a href="http://vzaar.com">vzaar</a> video storage area.
 *
 */
class UploadSignature {
    ///////////////////////////////////////////////////////////////////////////
    // Private Members ////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////

    var $guid;
    var $key;
    var $https;
    var $acl;
    var $bucket;
    var $policy;
    var $expirationDate;
    var $accessKeyId;
    var $signature;

    ///////////////////////////////////////////////////////////////////////////
    // Public and Package Protected Methods ///////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////

    /**
     * Package protected constructor.
     *
     * @param guid the vzaar global unique identifier
     * @param key a name for the S3 object that will store the uploaded
     * 		file's data
     * @param https
     * @param acl the access control policy to apply to the uploaded file
     * @param bucket the vzaar bucket that has been allocated for this file
     * @param policy a Base64-encoded policy document that applies rules to
     * 	file uploads sent by the S3 POST form. This document is used to authorise
     * 	the form, and to impose conditions on the files that can be uploaded.
     * @param expirationDate s Greenwich Mean Time (GMT) timestamp that
     * 	specifies when the policy document will expire. Once a policy document
     * 	has expired, the upload will fail
     * @param accessKey the vzaar AWS Access Key Identifier credential
     * @param signature a signature value that authorises the form and proves
     * 	that only vzaar could have created it. This value is calculated by signing
     * 	the policy document
     */
    function __construct($guid, $key, $https, $acl,
            $bucket, $policy, $expirationDate,
            $accessKeyId, $signature) {
        $this->guid = $guid;
        $this->key = $key;
        $this->https = $https;
        $this->acl = $acl;
        $this->bucket = $bucket;
        $this->policy = $policy;
        $this->expirationdate = $expirationDate;
        $this->accesskeyid = $accessKeyId;
        $this->signature = $signature;
    }

    static function fromJson($data) {
	$jo = json_decode($data);
        return $jo;
    }

    static function fromXml ($data) {
        $sig = new XMLToArray( $data, array(), array(), true, false );

        return $sig->getArray();
    }
}
?>