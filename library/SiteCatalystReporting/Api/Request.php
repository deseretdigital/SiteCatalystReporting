<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jcarmony
 * Date: 3/15/13
 * Time: 10:28 PM
 * To change this template use File | Settings | File Templates.
 */

namespace SiteCatalystReporting\Api;

use SiteCatalystReporting\Config;

class Request
{
    const METHOD_Report_QueueTrended = 'Report.QueueTrended';

    /**
     * @var Config
     */
    public $config;

    public $method;

    public $nonce;
    public $nonce_ts;
    public $digest;

    public function __construct()
    {

    }

    public function setConfig($config)
    {
        if($config instanceof Config === false)
        {
            throw new \Exception("Invalid Config Object Passed");
        }
        $config->validate();
        $this->config = $config;
    }

    public function setMethod($method)
    {
        if(!is_string($method))
        {
            throw new \Exception("Invalid Method Name");
        }

        if(stripos($method, '.') === false)
        {
            throw new \Exception("Invalid Method Name, Missing \".\", Expecting Group.MethodName");
        }

        $this->method = $method;
    }

    public function buildNonce()
    {
        $this->nonce = md5(uniqid(php_uname('n'), true));
        $this->nonce_ts = date('c');
        $this->digest = base64_encode(sha1($this->nonce.$this->nonce_ts.$this->config->secret));
    }


}