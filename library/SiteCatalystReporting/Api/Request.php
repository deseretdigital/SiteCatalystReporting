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
use SiteCatalystReporting\SimpleRestClient;

class Request
{
    const METHOD_Report_QueueTrended = 'Report.QueueTrended';

    /**
     * @var Config
     */
    public $config;

    public $method;
    public $dataObj;

    public $nonce;
    public $nonce_ts;
    public $digest;

    public $client;

    public $status_code;
    public $response;
    public $info;

    public function __construct()
    {
        $this->client = new SimpleRestClient();
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

    public function setDataObject($obj)
    {
        if(!is_object($obj) && !is_array($obj))
        {
            throw new \Exception("Invalid Data Object, Expecting Object or Array");
        }

        $this->dataObj = $obj;
    }

    public function buildNonce()
    {
        $this->nonce = md5(uniqid(php_uname('n'), true));
        $this->nonce_ts = date('c');
        $this->digest = base64_encode(sha1($this->nonce.$this->nonce_ts.$this->config->secret));
    }

    public function buildHeaders()
    {
        $this->client->setOption(CURLOPT_HTTPHEADER, array("X-WSSE: UsernameToken Username=\"{$this->config->username}\", PasswordDigest=\"{$this->digest}\", Nonce=\"{$this->nonce}\", Created=\"{$this->nonce_ts}\""));
    }

    /**
     * @return Response
     */
    public function send()
    {
        $this->buildNonce();
        $this->buildHeaders();
        $json = json_encode($this->dataObj);

        $this->client->postWebRequest($this->config->server.$this->config->path.'?method='.$this->method, $json);

        $factory = new ResponseFactory();
        $response = $factory->buildResponseFromClient($this->client);

        return $response;
    }


}